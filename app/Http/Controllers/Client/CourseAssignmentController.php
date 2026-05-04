<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CourseAssignment;
use App\Models\CourseSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseAssignmentController extends Controller
{
	public function store(Request $request, string $slug, int $assignmentId)
	{
		$program = DB::table('data_programs')
			->select('id', 'slug', 'category')
			->where('slug', $slug)
			->where('status', 'published')
			->first();

		if (!$program) {
			return back()->with('error', 'Program tidak ditemukan.');
		}

		$enrollment = DB::table('enrollments')
			->where('student_id', Auth::id())
			->where('program_id', $program->id)
			->first();

		if (!$enrollment) {
			return back()->with('error', 'Anda belum terdaftar pada kelas ini.');
		}

		$assignment = CourseAssignment::where('program_id', $program->id)
			->findOrFail($assignmentId);

		if ($assignment->due_date && now()->greaterThan($assignment->due_date)) {
			return back()->with('error', 'Batas pengumpulan sudah lewat.');
		}

		$allowedExtensions = $this->parseAllowedExtensions($assignment->allowed_extensions);
		$rules = ['assignment_file' => ['required', 'file', 'max:10240']];
		if (!empty($allowedExtensions)) {
			$rules['assignment_file'][] = 'mimes:' . implode(',', $allowedExtensions);
		}

		$messages = [
			'assignment_file.required' => 'File tugas wajib diunggah.',
			'assignment_file.mimes' => 'Format file tidak sesuai dengan ketentuan.',
			'assignment_file.max' => 'Ukuran file terlalu besar (maks 10MB).'
		];

		$validated = $request->validate($rules, $messages);

		$file = $validated['assignment_file'];
		$originalName = $file->getClientOriginalName();
		$safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
		$extension = $file->getClientOriginalExtension();
		$fileName = $safeName . '-' . time() . '.' . $extension;
		$filePath = $file->storeAs('lms-submissions', $fileName, 'public');

		$submission = CourseSubmission::where('assignment_id', $assignment->id)
			->where('user_id', Auth::id())
			->first();

		if ($submission && $submission->file_path) {
			Storage::disk('public')->delete($submission->file_path);
		}

		CourseSubmission::updateOrCreate(
			[
				'assignment_id' => $assignment->id,
				'user_id' => Auth::id(),
			],
			[
				'file_path' => $filePath,
				'file_name' => $originalName,
				'submitted_at' => now(),
				'grade' => $submission?->grade,
				'feedback' => $submission?->feedback,
			]
		);

		return back()->with('success', 'Tugas berhasil dikirim.');
	}

	private function parseAllowedExtensions(?string $allowedExtensions): array
	{
		if (!$allowedExtensions) {
			return [];
		}

		return collect(explode(',', $allowedExtensions))
			->map(function ($ext) {
				$clean = trim($ext);
				return ltrim($clean, '.');
			})
			->filter()
			->unique()
			->values()
			->all();
	}
}
