<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassGroupController extends Controller
{
    /**
     * Display a listing of class groups.
     */
    public function index()
    {
        $title = 'Class Groups';
        $classGroups = ClassGroup::all();

        return view('pages.class_groups.class_groups_view', compact('title', 'classGroups'));
    }

    /**
     * Store a newly created class group.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 
            Rule::unique('class_groups', 'name')->whereNull('deleted_at'),
            ],
        ]);

        $classGroup = ClassGroup::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Class Group created successfully.',
            'data' => $classGroup,
        ]);
    }

    /**
     * Display the specified class group.
     */
    public function show(string $id)
    {
        $classGroup = ClassGroup::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $classGroup,
        ]);
    }

    /**
     * Update the specified class group.
     */
    public function update(Request $request, string $id)
    {
        $classGroup = ClassGroup::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 
                Rule::unique('class_groups', 'name')->whereNull('deleted_at')->ignore($classGroup->id),]
        ]);

        $classGroup->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Class Group updated successfully.',
            'data' => $classGroup,
        ]);
    }

    /**
     * Remove the specified class group (soft delete).
     */
    public function destroy(string $id)
    {
        $classGroup = ClassGroup::findOrFail($id);
        $classGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Class Group deleted successfully.',
        ]);
    }
}
