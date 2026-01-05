<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BecomeInstructorController extends Controller
{
    /**
     * Show the form for applying to become an instructor.
     */
    public function create()
    {
        // Check if user already has a pending or approved application
        $existingApplication = DB::table('instructor_applications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($existingApplication && $existingApplication->status === 'pending') {
            return redirect()->route('client.dashboard')->with('info', 'Anda sudah mengajukan permohonan menjadi instruktur. Mohon tunggu konfirmasi admin.');
        }

        if ($existingApplication && $existingApplication->status === 'approved') {
             return redirect()->route('client.dashboard')->with('info', 'Anda sudah terdaftar sebagai instruktur.');
        }

        return view('client.instructor.create', compact('existingApplication'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'skills' => 'required|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'ktp' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'bio' => 'required|string',
        ]);

        $user = Auth::user();
        $cvPath = null;
        $ktpPath = null;

        // Upload CV
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            $cvName = time() . '_CV_' . $user->id . '.' . $cv->getClientOriginalExtension();
            $cv->move(public_path('uploads/documents/cv'), $cvName);
            $cvPath = 'uploads/documents/cv/' . $cvName;
        }

        // Upload KTP
        if ($request->hasFile('ktp')) {
            $ktp = $request->file('ktp');
            $ktpName = time() . '_KTP_' . $user->id . '.' . $ktp->getClientOriginalExtension();
            $ktp->move(public_path('uploads/documents/ktp'), $ktpName);
            $ktpPath = 'uploads/documents/ktp/' . $ktpName;
        }

        DB::table('instructor_applications')->insert([
            'user_id' => $user->id,
            'skills' => $request->skills,
            'cv_path' => $cvPath,
            'ktp_path' => $ktpPath,
            'bio' => $request->bio,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('client.dashboard')->with('success', 'Pengajuan menjadi instruktur berhasil dikirim. Kami akan meninjau data Anda.');
    }
}
