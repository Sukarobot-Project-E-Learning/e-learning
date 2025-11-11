<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgramProofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $proofs = [
            [
                'id' => 1,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
            [
                'id' => 2,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
            [
                'id' => 3,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
            [
                'id' => 4,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
            [
                'id' => 5,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
            [
                'id' => 6,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
            [
                'id' => 7,
                'name' => 'Nama User',
                'program_title' => 'Workshop Branding',
                'schedule' => '20 September 2025',
                'documentation' => null
            ],
        ];

        return view('admin.program-proofs.index', compact('proofs'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Get proof by id
        $proof = (object)[
            'id' => $id,
            'user_name' => 'Nama User',
            'program_title' => 'Judul Program',
            'type' => 'Online',
            'start_date' => '20/09/2025',
            'start_time' => '09:00 AM',
            'end_date' => '20/09/2025',
            'end_time' => '11:00 AM',
            'documentation' => null
        ];

        return view('admin.program-proofs.show', compact('proof'));
    }

    /**
     * Accept the program proof.
     */
    public function accept($id)
    {
        // TODO: Add accept logic here
        return redirect()->route('elearning.admin.program-proofs.index')->with('success', 'Bukti program berhasil diterima');
    }

    /**
     * Reject the program proof.
     */
    public function reject($id)
    {
        // TODO: Add reject logic here
        return redirect()->route('elearning.admin.program-proofs.index')->with('success', 'Bukti program berhasil ditolak');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('elearning.admin.program-proofs.index')->with('success', 'Bukti program berhasil dihapus');
    }
}

