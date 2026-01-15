<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgramProofController extends Controller
{
    public function create($slug)
    {
        $user = Auth::user();
        $program = DB::table('data_programs')->where('slug', $slug)->first();

        if (!$program) {
            return redirect()->route('client.dashboard.program')->with('error', 'Program tidak ditemukan.');
        }

        // Check if user is enrolled
        $isEnrolled = DB::table('enrollments')
            ->where('student_id', $user->id)
            ->where('program_id', $program->id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('client.program.detail', $slug)->with('error', 'Anda belum terdaftar di program ini.');
        }

        // Check if proof already exists
        $proof = ProgramProof::where('student_id', $user->id)
            ->where('program_id', $program->id)
            ->first();

        // If proof exists and is accepted or pending, maybe show a different view or redirect?
        // Requirement: "Setelah user memberikan ulasan dan bukti program, barulah tombol kembali menjadi lihat detail"
        // This implies if proof exists, they shouldn't be here? Or maybe they can edit?
        // For now, if proof exists, we show the existing proof or allow re-upload if rejected?
        // Let's pass the proof to the view.
        
        return view('client.program.submit-proof', compact('program', 'proof'));
    }

    public function store(Request $request, $slug)
    {
        $user = Auth::user();
        $program = DB::table('data_programs')->where('slug', $slug)->first();

        if (!$program) {
            return back()->with('error', 'Program tidak ditemukan.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'proof_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        // Find active schedule (Approximation)
        // We pick the first active schedule or just null if not found.
        $schedule = DB::table('schedules')
            ->where('id_program', $program->id)
            ->where('ket', 'Aktif')
            ->first();
            
        $scheduleId = $schedule ? $schedule->id : null;

        // Handle file upload
        $documentationPath = null;
        if ($request->hasFile('proof_file')) {
            $file = $request->file('proof_file');
            $filename = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $uploadPath = public_path('images/bukti-program');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            $file->move($uploadPath, $filename);
            $documentationPath = 'images/bukti-program/' . $filename;
        }

        ProgramProof::updateOrCreate(
            [
                'student_id' => $user->id,
                'program_id' => $program->id,
            ],
            [
                'schedule_id' => $scheduleId,
                'documentation' => $documentationPath,
                'rating' => $request->rating,
                'review' => $request->review,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Update Program Rating & Review Count
        $programStats = DB::table('program_proofs')
            ->where('program_id', $program->id)
            ->whereNotNull('rating')
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_reviews')
            ->first();

        DB::table('data_programs')
            ->where('id', $program->id)
            ->update([
                'rating' => $programStats->avg_rating ?? 0,
                'total_reviews' => $programStats->total_reviews ?? 0,
            ]);

        return redirect()->route('client.dashboard.program')->with('success', 'Bukti program dan ulasan berhasil dikirim!');
    }
}
