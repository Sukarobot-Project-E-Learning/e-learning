<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $type = $request->input('type', '');
        $perPage = (int) $request->input('per_page', 10);
        $sortKey = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');

        // Validate sort direction
        $sortDir = in_array(strtolower($sortDir), ['asc', 'desc']) ? strtolower($sortDir) : 'desc';

        // Map sortable columns
        $sortableColumns = [
            'title' => 'quizzes.title',
            'instructor' => 'data_trainers.nama',
            'program' => 'data_programs.program',
            'type' => 'quizzes.type',
            'created_at' => 'quizzes.created_at',
        ];
        $sortColumn = $sortableColumns[$sortKey] ?? 'quizzes.created_at';

        // Build query
        $query = DB::table('quizzes')
            ->leftJoin('data_programs', 'quizzes.program_id', '=', 'data_programs.id')
            ->leftJoin('data_trainers', 'quizzes.instructor_id', '=', 'data_trainers.id')
            ->select(
                'quizzes.*',
                'data_programs.program as program_name',
                'data_trainers.nama as instructor_name'
            );

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('quizzes.title', 'like', "%{$search}%")
                  ->orWhere('data_programs.program', 'like', "%{$search}%")
                  ->orWhere('data_trainers.nama', 'like', "%{$search}%");
            });
        }

        // Apply type filter
        if ($type) {
            $query->where('quizzes.type', $type);
        }

        // Apply sorting and pagination
        $quizzes = $query->orderBy($sortColumn, $sortDir)
            ->paginate($perPage)
            ->withQueryString();

        // Transform data after pagination
        $quizzes->getCollection()->transform(function($quiz) {
            // Get total questions count
            $totalQuestions = DB::table('quiz_questions')
                ->where('quiz_id', $quiz->id)
                ->count();

            // Get total responses count
            $totalResponses = DB::table('quiz_responses')
                ->where('quiz_id', $quiz->id)
                ->count();

            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'instructor' => $quiz->instructor_name ?? 'N/A',
                'program' => $quiz->program_name ?? 'N/A',
                'type' => $quiz->type ?? 'Postest',
                'status' => $quiz->status ?? 'draft',
                'total_questions' => $totalQuestions,
                'total_responses' => $totalResponses,
                'created_at' => $quiz->created_at ? date('Y-m-d', strtotime($quiz->created_at)) : '-'
            ];
        });

        // Return JSON for AJAX requests
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'data' => $quizzes->items(),
                'current_page' => $quizzes->currentPage(),
                'last_page' => $quizzes->lastPage(),
                'per_page' => $quizzes->perPage(),
                'total' => $quizzes->total(),
            ]);
        }

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // Store logic here
        return redirect()->route('admin.quizzes.index')->with('success', 'Tugas/Postest berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // TODO: Get quiz by id with responses
        return view('admin.quizzes.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Get quiz by id
        return view('admin.quizzes.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation and update logic here
        // Update logic here
        return redirect()->route('admin.quizzes.index')->with('success', 'Tugas/Postest berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // Delete quiz questions
            DB::table('quiz_questions')->where('quiz_id', $id)->delete();
            // Delete quiz responses
            DB::table('quiz_responses')->where('quiz_id', $id)->delete();
            // Delete quiz
            DB::table('quizzes')->where('id', $id)->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tugas/Postest berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus tugas/postest'], 500);
        }
    }
}

