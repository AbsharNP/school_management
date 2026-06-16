<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StandardController extends Controller
{
    /**
     * Display a listing of class.
     */
    public function index()
    {
        $title = 'Class Groups';
        $standard = Standard::all();
        $classGroups = ClassGroup::all();

        return view('pages.standard.standard_view', compact('title', 'classGroups','standard'));
    }

    /**
     * Store a newly created class group.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('standards', 'name')->whereNull('deleted_at')],
            'classgroup_id' => ['required', 'exists:class_groups,id'],
        ], [
            'classgroup_id.required' => 'The class group field is required.',
            'classgroup_id.exists' => 'Please select a valid class group.',
        ]);

        $standard = Standard::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Class Group created successfully.',
            'data' => $standard,
        ]);
    }

    /**
     * Display the specified class group.
     */
    public function show(string $id)
    {
        $standard = Standard::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $standard,
        ]);
    }

    /**
     * Update the specified class group.
     */
    public function update(Request $request, string $id)
    {
        $standard = Standard::findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('standards', 'name')->whereNull('deleted_at')->ignore($standard->id)],
            'classgroup_id' => ['required', 'exists:class_groups,id'],
        ], [
            'classgroup_id.required' => 'The class group field is required.',
            'classgroup_id.exists' => 'Please select a valid class group.',
        ]);

        $standard->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Class Group updated successfully.',
            'data' => $standard,
        ]);
    }

    /**
     * Remove the specified class group (soft delete).
     */
    public function destroy(string $id)
    {
        $standard = Standard::findOrFail($id);
        $standard->delete();

        return response()->json([
            'success' => true,
            'message' => 'Class Group deleted successfully.',
        ]);
    }
}
