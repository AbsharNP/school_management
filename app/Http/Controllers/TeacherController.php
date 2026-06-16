<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $title = 'Teachers';

        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            $classGroupId = $user->teacher?->class_group_id;
            $teachers    = $classGroupId
                ? Teacher::with('classGroup')->where('class_group_id', $classGroupId)->get()
                : Teacher::whereRaw('0 = 1')->get();
            $classGroups = $classGroupId
                ? ClassGroup::where('id', $classGroupId)->get()
                : ClassGroup::whereRaw('0 = 1')->get();
        } else {
            $teachers    = Teacher::with('classGroup')->get();
            $classGroups = ClassGroup::all();
        }

        return view('pages.teachers.teachers_view', compact('title', 'teachers', 'classGroups'));
    }

    private function generatePassword(string $name): string
    {
        $prefix = strtolower(substr(preg_replace('/\s+/', '', $name), 0, 3));
        return $prefix . '123';
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'subject'        => ['nullable', 'string', 'max:255'],
            'class_group_id' => ['nullable', 'exists:class_groups,id'],
            'email'          => ['required', 'email', 'unique:teachers,email'],
            'role_id'        => ['required', 'exists:roles,id'],
        ]);

        DB::beginTransaction();
        try {
            $password    = $this->generatePassword($data['name']);
            $role        = Role::findOrFail($data['role_id']);

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($password),
            ]);
            $user->assignRole($role->name);

            unset($data['role_id']);
            $data['user_id'] = $user->id;
            $teacher = Teacher::create($data);
            $teacher->load('classGroup');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully. Login password: ' . $password,
                'data'    => $teacher,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create teacher: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $teacher = Teacher::with('classGroup')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $teacher,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'subject'        => ['nullable', 'string', 'max:255'],
            'class_group_id' => ['nullable', 'exists:class_groups,id'],
            'email'          => ['required', 'email', 'unique:teachers,email,' . $id],
        ]);

        DB::beginTransaction();
        try {
            $teacher->update($data);

            if ($teacher->user_id) {
                $linkedUser = User::find($teacher->user_id);
                if ($linkedUser) {
                    $linkedUser->update([
                        'name'  => $data['name'],
                        'email' => $data['email'],
                    ]);
                }
            }

            $teacher->load('classGroup');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully.',
                'data'    => $teacher,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update teacher: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $teacher = Teacher::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($teacher->user_id) {
                User::where('id', $teacher->user_id)->delete();
            }
            $teacher->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete teacher: ' . $e->getMessage(),
            ], 500);
        }
    }
}
