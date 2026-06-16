<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassGroup;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $title = 'Students';
        $students = Student::with('classGroup')->get();
        $classGroups = ClassGroup::all();

        return view('pages.students.students_view', compact('title', 'students', 'classGroups'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:students,email'],
            'roll_number' => ['nullable', 'string', 'max:50'],
            'class_group_id' => ['nullable', 'exists:class_groups,id'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $student = Student::create($data);
        $student->load('classGroup');

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully.',
            'data' => $student,
        ]);
    }

    /**
     * Display the specified student.
     */
    public function show(string $id)
    {
        $student = Student::with('classGroup')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $student,
        ]);
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:students,email,' . $id],
            'roll_number' => ['nullable', 'string', 'max:50'],
            'class_group_id' => ['nullable', 'exists:class_groups,id'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $student->update($data);
        $student->load('classGroup');

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
            'data' => $student,
        ]);
    }

    /**
     * Remove the specified student (soft delete).
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully.',
        ]);
    }
}
