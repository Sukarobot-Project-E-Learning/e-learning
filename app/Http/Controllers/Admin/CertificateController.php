<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $certificates = [
            [
                'id' => 1,
                'name' => 'Nama User',
                'title' => 'Workshop Branding',
                'credential_number' => 'CERT-001',
                'blanko' => null
            ],
            [
                'id' => 2,
                'name' => 'Nama User',
                'title' => 'Workshop Branding',
                'credential_number' => 'CERT-002',
                'blanko' => null
            ],
            [
                'id' => 3,
                'name' => 'Nama User',
                'title' => 'Workshop Branding',
                'credential_number' => 'CERT-003',
                'blanko' => null
            ],
            [
                'id' => 4,
                'name' => 'Nama User',
                'title' => 'Workshop Branding',
                'credential_number' => 'CERT-004',
                'blanko' => null
            ],
            [
                'id' => 5,
                'name' => 'Nama User',
                'title' => 'Workshop Branding',
                'credential_number' => 'CERT-005',
                'blanko' => null
            ],
        ];

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

