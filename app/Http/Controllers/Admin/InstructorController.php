<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     * Halaman untuk konfirmasi akun instruktur yang mendaftar
     */
    public function index()
    {
        // Dummy data untuk sementara - instruktur yang mendaftar (pending approval)
        $instructors = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '081234567890',
                'job' => 'Social Media Manager',
                'experience' => '7 Tahun',
                'photo' => null,
                'expertise' => 'Social Media',
                'status' => 'Pending', // Pending, Approved, Rejected
                'created_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '081234567891',
                'job' => 'Digital Marketing Specialist',
                'experience' => '5 Tahun',
                'photo' => null,
                'expertise' => 'Digital Marketing',
                'status' => 'Pending',
                'created_at' => '2024-01-16'
            ],
            [
                'id' => 3,
                'name' => 'Bob Johnson',
                'email' => 'bob.johnson@example.com',
                'phone' => '081234567892',
                'job' => 'Web Developer',
                'experience' => '10 Tahun',
                'photo' => null,
                'expertise' => 'Web Development',
                'status' => 'Approved',
                'created_at' => '2024-01-10'
            ],
            [
                'id' => 4,
                'name' => 'Alice Williams',
                'email' => 'alice.williams@example.com',
                'phone' => '081234567893',
                'job' => 'Content Creator',
                'experience' => '3 Tahun',
                'photo' => null,
                'expertise' => 'Social Media',
                'status' => 'Rejected',
                'created_at' => '2024-01-12'
            ],
            [
                'id' => 5,
                'name' => 'Charlie Brown',
                'email' => 'charlie.brown@example.com',
                'phone' => '081234567894',
                'job' => 'UI/UX Designer',
                'experience' => '6 Tahun',
                'photo' => null,
                'expertise' => 'Design',
                'status' => 'Pending',
                'created_at' => '2024-01-17'
            ],
        ];

        return view('admin.instructors.index', compact('instructors'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Add delete logic here
        // Delete logic here
        return redirect()->route('elearning.admin.instructors.index')->with('success', 'Instruktur berhasil dihapus');
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
        
        return redirect()->route('elearning.admin.instructors.index')->with('success', 'Akun instruktur berhasil disetujui');
    }

    /**
     * Reject instructor registration
     */
    public function reject($id)
    {
        // TODO: Add reject logic here
        // Update instructor status to 'Rejected'
        // Send notification email with rejection reason
        
        return redirect()->route('elearning.admin.instructors.index')->with('success', 'Pendaftaran instruktur ditolak');
    }
}

