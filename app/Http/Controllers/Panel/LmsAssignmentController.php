<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LmsAssignmentController extends Controller
{
    /**
     * Get all assignments for a program
     */
    public function index($programId)
    {
        $assignments = CourseAssignment::where('program_id', $programId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    /**
     * Store a new assignment
     */
    public function store(Request $request, $programId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'allowed_extensions' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $assignment = CourseAssignment::create([
            'program_id' => $programId,
            'title' => $request->title,
            'description' => $request->description,
            'allowed_extensions' => $request->allowed_extensions,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post-Test berhasil ditambahkan',
            'data' => $assignment
        ]);
    }

    /**
     * Update an assignment
     */
    public function update(Request $request, $programId, $assignmentId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'allowed_extensions' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $assignment = CourseAssignment::where('program_id', $programId)->findOrFail($assignmentId);
        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'allowed_extensions' => $request->allowed_extensions,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post-Test berhasil diperbarui',
            'data' => $assignment
        ]);
    }

    /**
     * Delete an assignment
     */
    public function destroy($programId, $assignmentId)
    {
        $assignment = CourseAssignment::where('program_id', $programId)->findOrFail($assignmentId);
        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post-Test berhasil dihapus'
        ]);
    }
}
