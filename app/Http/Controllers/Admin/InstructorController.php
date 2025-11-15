<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Halaman untuk konfirmasi akun instruktur yang mendaftar
     */
    public function index()
    {
        // Get instructors from data_trainers table with pagination (5 per page)
        $instructors = DB::table('data_trainers')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Transform data after pagination
        $instructors->getCollection()->transform(function($trainer) {
            // Check if trainer has user account
            $user = DB::table('users')
                ->where('email', $trainer->email)
                ->first();

            return [
                'id' => $trainer->id,
                'name' => $trainer->nama,
                'email' => $trainer->email ?? '-',
                'phone' => $trainer->telephone ?? '-',
                'job' => $trainer->lulusan ?? '-',
                'experience' => '-',
                'photo' => $trainer->profile ?? null,
                'expertise' => '-',
                'status' => $trainer->status_trainer, // Aktif, Tidak Aktif
                'created_at' => $trainer->created_at ? date('Y-m-d', strtotime($trainer->created_at)) : '-',
                'has_account' => $user ? true : false
            ];
        });

        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new instructor.
     */
    public function create()
    {
        return view('admin.instructors.create');
    }

    /**
     * Store a newly created instructor in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation and store logic here
        // For now, just redirect back with success message

        return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('admin.instructors.index')->with('success', 'Instruktur berhasil dihapus');
    }

    /**
     * Approve instructor registration
     */
    public function approve($id)
    {
        // TODO: Add approve logic here
        // Update instructor status to 'Approved'
        // Activate instructor account
        // Send notification email
        
        return redirect()->route('admin.instructors.index')->with('success', 'Akun instruktur berhasil disetujui');
    }

    /**
     * Reject instructor registration
     */
    public function reject($id)
    {
        // TODO: Add reject logic here
        // Update instructor status to 'Rejected'
        // Send notification email with rejection reason
        
        return redirect()->route('admin.instructors.index')->with('success', 'Pendaftaran instruktur ditolak');
    }
}

