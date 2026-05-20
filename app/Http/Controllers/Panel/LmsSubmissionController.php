<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LmsSubmissionController extends Controller
{
    /**
     * List all post-test submissions for a program.
     */
    public function index($programId)
    {
        $assignments = CourseAssignment::where(
            'program_id',
            $programId
        )
            ->where(function ($query) {
                $query->where('type', 'post-test')
                    ->orWhereNull('type');
            })
            ->with([
                'submissions' => function ($q) {
                    $q->with('user')
                        ->orderBy('submitted_at', 'desc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $program = DB::table('data_programs')
            ->select('id', 'program', 'slug')
            ->where('id', $programId)
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        // Determine role-based prefix
        $isAdmin = request()->is('admin*');
        $prefix = $isAdmin
            ? 'admin'
            : 'instructor';

        return view(
            'panel.programs.submissions',
            compact(
                'assignments',
                'program',
                'prefix',
                'isAdmin'
            )
        );
    }

    /**
     * Show a single submission detail.
     */
    public function show(
        $programId,
        $submissionId
    ) {
        $submission = CourseSubmission::with([
            'assignment',
            'user',
        ])
            ->where('id', $submissionId)
            ->whereHas(
                'assignment',
                function ($q) use ($programId) {
                    $q->where(
                        'program_id',
                        $programId
                    );
                }
            )
            ->firstOrFail();

        $program = DB::table('data_programs')
            ->select('id', 'program', 'slug')
            ->where('id', $programId)
            ->first();

        $isAdmin = request()->is('admin*');
        $prefix = $isAdmin
            ? 'admin'
            : 'instructor';

        return view(
            'panel.programs.submission-detail',
            compact(
                'submission',
                'program',
                'prefix',
                'isAdmin'
            )
        );
    }

    /**
     * Grade a submission (score + feedback).
     */
    public function grade(
        Request $request,
        $programId,
        $submissionId
    ) {
        $validator = Validator::make(
            $request->all(),
            [
                'score' => 'required|integer|min:0|max:100',
                'feedback' => 'nullable|string|max:1000',
            ],
            [
                'score.required' => 'Nilai wajib diisi.',
                'score.integer' => 'Nilai harus berupa angka.',
                'score.min' => 'Nilai minimal 0.',
                'score.max' => 'Nilai maksimal 100.',
                'feedback.max' => 'Feedback maks 1000 karakter.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $submission = CourseSubmission::whereHas(
            'assignment',
            function ($q) use ($programId) {
                $q->where(
                    'program_id',
                    $programId
                );
            }
        )->findOrFail($submissionId);

        $submission->update([
            'score' => $request->score,
            'grade' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil disimpan.',
            'data' => $submission->fresh(),
        ]);
    }
}
