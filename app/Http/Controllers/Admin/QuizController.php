<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('quizzes')
            ->leftJoin('data_programs', 'quizzes.program_id', '=', 'data_programs.id')
            ->leftJoin('data_trainers', 'quizzes.instructor_id', '=', 'data_trainers.id')
            ->select(
                'quizzes.id',
                'quizzes.title',
                'quizzes.type',
                'quizzes.status',
                'quizzes.created_at',
                'data_programs.program as program_name',
                'data_trainers.nama as instructor_name'
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'title', 'label' => 'Judul Quiz', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'instructor', 'label' => 'Instruktur', 'sortable' => true],
                ['key' => 'program', 'label' => 'Program', 'sortable' => true],
                ['key' => 'type', 'label' => 'Tipe', 'sortable' => true, 'type' => 'badge'],
                ['key' => 'total_questions', 'label' => 'Soal'],
                ['key' => 'total_responses', 'label' => 'Respons'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['quizzes.title', 'data_programs.program', 'data_trainers.nama'],
            'sortable' => ['title', 'instructor', 'program', 'type', 'created_at'],
            'sortColumns' => [
                'title' => 'quizzes.title',
                'instructor' => 'data_trainers.nama',
                'program' => 'data_programs.program',
                'type' => 'quizzes.type',
                'created_at' => 'quizzes.created_at',
            ],
            'actions' => ['edit', 'delete'],
            'route' => 'admin.quizzes',
            'routeParam' => 'id',
            'title' => 'Quiz Management',
            'entity' => 'quiz',
            'createLabel' => 'Tambah Quiz',
            'searchPlaceholder' => 'Cari quiz, program, instruktur...',
            'filter' => [
                'key' => 'type',
                'column' => 'quizzes.type',
                'options' => [
                    '' => 'Semua Tipe',
                    'pretest' => 'Pretest',
                    'posttest' => 'Posttest',
                ]
            ],
            'badgeClasses' => [
                'Pretest' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                'Posttest' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            ],
            'transformer' => function($quiz) {
                $totalQuestions = DB::table('quiz_questions')
                    ->where('quiz_id', $quiz->id)
                    ->count();

                $totalResponses = DB::table('quiz_responses')
                    ->where('quiz_id', $quiz->id)
                    ->count();

                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'instructor' => $quiz->instructor_name ?? 'N/A',
                    'program' => $quiz->program_name ?? 'N/A',
                    'type' => ucfirst($quiz->type ?? 'Posttest'),
                    'status' => $quiz->status ?? 'draft',
                    'total_questions' => $totalQuestions,
                    'total_responses' => $totalResponses,
                    'created_at' => $quiz->created_at ? date('d F Y', strtotime($quiz->created_at)) : '-'
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.quizzes.index', compact('data'));
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

