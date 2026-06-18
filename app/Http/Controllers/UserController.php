<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Users';
        $users = User::with(['roles', 'student.classGroup', 'teacher.classGroup'])->get();

        $classGroups  = ClassGroup::all();
        $teacherRoles = Role::where('name', 'like', '%Teacher%')->get();
        $otherRoles   = Role::where('name', 'not like', '%Teacher%')
            ->where('name', '!=', 'Student')
            ->get();

        return view('pages.users.users_view', compact(
            'title', 'users', 'classGroups', 'teacherRoles', 'otherRoles'
        ));
    }

    private function detectType(User $user): string
    {
        if ($user->student) return 'student';
        if ($user->teacher) return 'teacher';
        return 'other';
    }

    private function generatePassword(string $name): string
    {
        $prefix = strtolower(substr(str_replace(' ', '', $name), 0, 3));
        return "{$prefix}123";
    }

    public function store(Request $request)
    {
        $type = $request->input('user_type', 'other');

        $commonRules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
        ];

        if ($type === 'student') {
            $rules = array_merge($commonRules, [
                'email'          => ['required', 'email', 'unique:users,email', 'unique:students,email'],
                'admission_no'   => ['required', 'string', 'max:50', 'unique:students,admission_no'],
                'class_group_id' => ['nullable', 'exists:class_groups,id'],
            ]);
        } elseif ($type === 'teacher') {
            $rules = array_merge($commonRules, [
                'email'   => ['required', 'email', 'unique:users,email', 'unique:teachers,email'],
                'role_id' => ['required', 'exists:roles,id'],
                'subject' => ['nullable', 'string', 'max:255'],
            ]);
        } else {
            $rules = array_merge($commonRules, [
                'role_id' => ['nullable', 'exists:roles,id'],
            ]);
        }

        $data     = $request->validate($rules);
        $password = $this->generatePassword($data['name']);

        DB::beginTransaction();
        try {
            if ($type === 'student') {
                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($password),
                ]);
                $user->assignRole('Student');

                Student::create([
                    'name'           => $data['name'],
                    'email'          => $data['email'],
                    'admission_no'   => $data['admission_no'],
                    'class_group_id' => $data['class_group_id'] ?? null,
                    'user_id'        => $user->id,
                ]);
            } elseif ($type === 'teacher') {
                $role = Role::findOrFail($data['role_id']);
                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($password),
                ]);
                $user->assignRole($role->name);

                Teacher::create([
                    'name'    => $data['name'],
                    'email'   => $data['email'],
                    'subject' => $data['subject'] ?? null,
                    'user_id' => $user->id,
                ]);
            } else {
                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($password),
                ]);
                if (!empty($data['role_id'])) {
                    $role = Role::find($data['role_id']);
                    if ($role) {
                        $user->assignRole($role->name);
                    }
                }
            }

            DB::commit();
            $user->load(['roles', 'student.classGroup', 'teacher.classGroup']);

            return response()->json([
                'success' => true,
                'message' => "User created successfully. Login password: {$password}",
                'data'    => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $user = User::with(['roles', 'student.classGroup', 'teacher.classGroup'])->findOrFail($id);

        return response()->json([
            'success'   => true,
            'data'      => $user,
            'user_type' => $this->detectType($user),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::with(['student', 'teacher'])->findOrFail($id);
        $type = $request->input('user_type', $this->detectType($user));

        $commonRules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:8'],
        ];

        if ($type === 'student') {
            $studentId = $user->student?->id;
            $rules = array_merge($commonRules, [
                'email'          => ['required', 'email',
                    Rule::unique('users', 'email')->ignore($id),
                    Rule::unique('students', 'email')->ignore($studentId),
                ],
                'admission_no'   => ['required', 'string', 'max:50',
                    Rule::unique('students', 'admission_no')->ignore($studentId),
                ],
                'class_group_id' => ['nullable', 'exists:class_groups,id'],
            ]);
        } elseif ($type === 'teacher') {
            $teacherId = $user->teacher?->id;
            $rules = array_merge($commonRules, [
                'email'   => ['required', 'email',
                    Rule::unique('users', 'email')->ignore($id),
                    Rule::unique('teachers', 'email')->ignore($teacherId),
                ],
                'role_id' => ['required', 'exists:roles,id'],
                'subject' => ['nullable', 'string', 'max:255'],
            ]);
        } else {
            $rules = array_merge($commonRules, [
                'role_id' => ['nullable', 'exists:roles,id'],
            ]);
        }

        $data = $request->validate($rules);

        DB::beginTransaction();
        try {
            $userUpdate = ['name' => $data['name'], 'email' => $data['email']];
            if (!empty($data['password'])) {
                $userUpdate['password'] = Hash::make($data['password']);
            }
            $user->update($userUpdate);

            if ($type === 'student') {
                $user->syncRoles(['Student']);
                if ($user->student) {
                    $user->student->update([
                        'name'           => $data['name'],
                        'email'          => $data['email'],
                        'admission_no'   => $data['admission_no'],
                        'class_group_id' => $data['class_group_id'] ?? null,
                    ]);
                }
            } elseif ($type === 'teacher') {
                $role = Role::findOrFail($data['role_id']);
                $user->syncRoles([$role->name]);
                if ($user->teacher) {
                    $user->teacher->update([
                        'name'    => $data['name'],
                        'email'   => $data['email'],
                        'subject' => $data['subject'] ?? null,
                    ]);
                }
            } else {
                if (!empty($data['role_id'])) {
                    $role = Role::find($data['role_id']);
                    $user->syncRoles($role ? [$role->name] : []);
                } else {
                    $user->syncRoles([]);
                }
            }

            DB::commit();
            $user->load(['roles', 'student.classGroup', 'teacher.classGroup']);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data'    => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $user = User::with(['student', 'teacher'])->findOrFail($id);

        DB::beginTransaction();
        try {
            $user->student?->delete();
            $user->teacher?->delete();
            $user->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }
}
