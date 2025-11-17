<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get vouchers from database with pagination (10 per page)
        $vouchers = DB::table('vouchers')
            ->leftJoin('data_programs', 'vouchers.program_id', '=', 'data_programs.id')
            ->select(
                'vouchers.*',
                'data_programs.program as program_name'
            )
            ->orderBy('vouchers.created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $vouchers->getCollection()->transform(function($voucher) {
            $discount = $voucher->discount_type === 'percentage' 
                ? $voucher->discount_value . '%' 
                : 'Rp ' . number_format($voucher->discount_value, 0, ',', '.');

            return [
                'id' => $voucher->id,
                'name' => $voucher->name ?? 'N/A',
                'discount' => $discount,
                'program_event' => $voucher->program_name ?? 'Semua Program',
                'code' => $voucher->code ?? 'N/A',
                'status' => $voucher->is_active ? 'Aktif' : 'Non-Aktif'
            ];
        });

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

        return redirect()->route('admin.vouchers.index')
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

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::table('vouchers')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Voucher berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus voucher'], 500);
        }
    }
}

