<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get certificates from database with pagination (5 per page)
        $certificates = DB::table('certificates')
            ->leftJoin('data_siswas', 'certificates.student_id', '=', 'data_siswas.id')
            ->leftJoin('users', function($join) {
                $join->on('certificates.student_id', '=', 'users.id')
                     ->where('users.role', '=', 'user');
            })
            ->leftJoin('data_programs', 'certificates.program_id', '=', 'data_programs.id')
            ->select(
                'certificates.*',
                DB::raw('COALESCE(data_siswas.nama_lengkap, users.name) as student_name'),
                'data_programs.program as program_name'
            )
            ->orderBy('certificates.created_at', 'desc')
            ->paginate(5);

        // Transform data after pagination
        $certificates->getCollection()->transform(function($certificate) {
            return [
                'id' => $certificate->id,
                'name' => $certificate->student_name ?? 'N/A',
                'title' => $certificate->program_name ?? 'N/A',
                'credential_number' => $certificate->certificate_number ?? 'N/A',
                'blanko' => $certificate->certificate_file,
                'issued_at' => $certificate->issued_at ? date('d F Y', strtotime($certificate->issued_at)) : '-'
            ];
        });

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation
        // TODO: Handle file upload for blanko
        // TODO: Save to database

        return redirect()->route('elearning.admin.certificates.index')
            ->with('success', 'Sertifikat berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Dummy data untuk sementara
        $certificate = [
            'id' => $id,
            'name' => 'Nama User',
            'title' => 'Workshop Branding',
            'credential_number' => 'CERT-001',
            'blanko' => null
        ];

        return view('admin.certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation
        // TODO: Handle file upload for blanko if changed
        // TODO: Update in database

        return redirect()->route('elearning.admin.certificates.index')
            ->with('success', 'Sertifikat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // TODO: Delete file if exists

        return redirect()->route('elearning.admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus');
    }
}

