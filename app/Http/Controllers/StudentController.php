<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\ClassGroup;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $title = 'Students';
        $query = Student::with(['classGroup', 'standard']);

        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            $classGroupId = $user->teacher?->class_group_id;
            $query = $classGroupId
                ? $query->where('class_group_id', $classGroupId)
                : $query->whereRaw('0 = 1');
        }

        // Search by name, admission number or email (applied within the user's scope).
        $search = trim((string) request('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('admission_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students    = $query->get();
        $classGroups = $this->allowedClassGroups($user);
        $standards   = Standard::when(
            $user->hasAnyRole(['Primary Teacher', 'High School Teacher']),
            fn($q) => $q->where('classgroup_id', $user->teacher?->class_group_id)
        )->get();

        return view('pages.students.students_view', compact('title', 'students', 'classGroups', 'standards', 'search'));
    }

    private function generatePassword(string $name): string
    {
        $prefix = strtolower(substr(preg_replace('/\s+/', '', $name), 0, 3));
        return $prefix . '123';
    }

    private function allowedClassGroups(User $user): \Illuminate\Database\Eloquent\Collection
    {
        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            $classGroupId = $user->teacher?->class_group_id;
            return $classGroupId
                ? ClassGroup::where('id', $classGroupId)->get()
                : ClassGroup::whereRaw('0 = 1')->get();
        }
        return ClassGroup::all();
    }

    public function store(Request $request)
    {
        $this->authorize('create', Student::class);

        $user = auth()->user();

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:students,email'],
            'admission_no'   => ['required', 'string', 'max:50'],
            'class_group_id' => ['nullable', 'exists:class_groups,id'],
            'class_id'       => ['nullable', 'exists:standards,id'],
        ]);

        // Teachers can only create students in their own class group
        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            $data['class_group_id'] = $user->teacher?->class_group_id;
        }

        DB::beginTransaction();
        try {
            $password = $this->generatePassword($data['name']);

            $newUser = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($password),
            ]);
            $newUser->assignRole('Student');

            $data['user_id'] = $newUser->id;
            $student = Student::create($data);
            $student->load(['classGroup', 'standard']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully. Login password: ' . $password,
                'data'    => $student,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create student: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        $student = Student::with(['classGroup', 'standard'])->findOrFail($id);
        $this->authorize('view', $student);

        return response()->json([
            'success' => true,
            'data'    => $student,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);
        $this->authorize('update', $student);

        $user = auth()->user();

        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:students,email,' . $id],
            'admission_no'   => ['required', 'string', 'max:50'],
            'class_group_id' => ['nullable', 'exists:class_groups,id'],
            'class_id'       => ['nullable', 'exists:standards,id'],
        ]);

        // Teachers cannot move students to a different class group
        if ($user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            $data['class_group_id'] = $user->teacher?->class_group_id;
        }

        DB::beginTransaction();
        try {
            $student->update($data);

            if ($student->user_id) {
                User::where('id', $student->user_id)->update([
                    'name'  => $data['name'],
                    'email' => $data['email'],
                ]);
            }

            $student->load(['classGroup', 'standard']);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully.',
                'data'    => $student,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update student: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $this->authorize('delete', $student);

        DB::beginTransaction();
        try {
            if ($student->user_id) {
                User::where('id', $student->user_id)->delete();
            }
            $student->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete student: ' . $e->getMessage(),
            ], 500);
        }
    }
}
