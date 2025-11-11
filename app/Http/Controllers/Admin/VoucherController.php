<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $vouchers = [
            [
                'id' => 1,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
            [
                'id' => 3,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
            [
                'id' => 4,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
            [
                'id' => 5,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
            [
                'id' => 6,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
            [
                'id' => 7,
                'name' => 'Nama Voucher',
                'discount' => '10%',
                'program_event' => 'Workshop Branding',
                'code' => 'NCEFLAT20',
                'status' => 'Aktif'
            ],
        ];

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation
        // TODO: Save to database

        return redirect()->route('elearning.admin.vouchers.index')
            ->with('success', 'Voucher berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Dummy data untuk sementara
        $voucher = [
            'id' => $id,
            'name' => 'Nama Voucher',
            'discount' => '10%',
            'program_event' => 'Workshop Branding',
            'code' => 'NCEFLAT20',
            'status' => 'Aktif'
        ];

        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation
        // TODO: Update in database

        return redirect()->route('elearning.admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database

        return redirect()->route('elearning.admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus');
    }
}

