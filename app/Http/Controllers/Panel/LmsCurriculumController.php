<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Models\CourseSection;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LmsCurriculumController extends Controller
{
    /**
     * Get all sections and lessons for a program
     */
    public function index($programId)
    {
        $sections = CourseSection::with('lessons')
            ->where('program_id', $programId)
            ->orderBy('order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    /**
     * Store a new section
     */
    public function storeSection(Request $request, $programId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $order = CourseSection::where('program_id', $programId)->max('order') + 1;

        $section = CourseSection::create([
            'program_id' => $programId,
            'title' => $request->title,
            'order' => $order
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bab berhasil ditambahkan',
            'data' => clone $section->load('lessons')
        ]);
    }

    /**
     * Update a section
     */
    public function updateSection(Request $request, $programId, $sectionId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $section = CourseSection::where('program_id', $programId)->findOrFail($sectionId);
        $section->update([
            'title' => $request->title
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bab berhasil diperbarui',
            'data' => $section->load('lessons')
        ]);
    }

    /**
     * Delete a section
     */
    public function destroySection($programId, $sectionId)
    {
        $section = CourseSection::where('program_id', $programId)->findOrFail($sectionId);
        $section->delete();

        return response()->json([
            'success' => true,
            'message' => 'Bab berhasil dihapus'
        ]);
    }

    /**
     * Reorder sections
     */
    public function reorderSections(Request $request, $programId)
    {
        $orderData = $request->input('sections', []);
        
        foreach ($orderData as $item) {
            CourseSection::where('id', $item['id'])->where('program_id', $programId)->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Store a new lesson
     */
    public function storeLesson(Request $request, $programId, $sectionId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,video',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $section = CourseSection::where('program_id', $programId)->findOrFail($sectionId);
        $order = CourseLesson::where('section_id', $section->id)->max('order') + 1;

        $lesson = CourseLesson::create([
            'section_id' => $section->id,
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'video_url' => $request->video_url,
            'order' => $order
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil ditambahkan',
            'data' => $lesson
        ]);
    }

    /**
     * Update a lesson
     */
    public function updateLesson(Request $request, $programId, $sectionId, $lessonId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:text,video',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $section = CourseSection::where('program_id', $programId)->findOrFail($sectionId);
        $lesson = CourseLesson::where('section_id', $section->id)->findOrFail($lessonId);
        
        $lesson->update([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'video_url' => $request->video_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diperbarui',
            'data' => $lesson
        ]);
    }

    /**
     * Delete a lesson
     */
    public function destroyLesson($programId, $sectionId, $lessonId)
    {
        $section = CourseSection::where('program_id', $programId)->findOrFail($sectionId);
        $lesson = CourseLesson::where('section_id', $section->id)->findOrFail($lessonId);
        $lesson->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus'
        ]);
    }

    /**
     * Reorder lessons
     */
    public function reorderLessons(Request $request, $programId, $sectionId)
    {
        $section = CourseSection::where('program_id', $programId)->findOrFail($sectionId);
        $orderData = $request->input('lessons', []);
        
        foreach ($orderData as $item) {
            CourseLesson::where('id', $item['id'])->where('section_id', $section->id)->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }
}
