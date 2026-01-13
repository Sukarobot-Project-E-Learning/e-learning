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
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['name', 'code', 'discount_value', 'is_active', 'created_at'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = DB::table('vouchers')
            ->leftJoin('data_programs', 'vouchers.program_id', '=', 'data_programs.id')
            ->select(
                'vouchers.id',
                'vouchers.name',
                'vouchers.code',
                'vouchers.discount_type',
                'vouchers.discount_value',
                'vouchers.program_id',
                'vouchers.is_active',
                'vouchers.created_at',
                'data_programs.program as program_name'
            )
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->input('search');
                $q->where(function ($query) use ($s) {
                    $query->where('vouchers.name', 'like', '%' . $s . '%')
                        ->orWhere('vouchers.code', 'like', '%' . $s . '%')
                        ->orWhere('data_programs.program', 'like', '%' . $s . '%');
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('vouchers.is_active', $request->input('status') === 'active' ? 1 : 0);
            });

        $vouchers = $query->orderBy('vouchers.' . $sortKey, $dir)
            ->paginate($perPage)
            ->withQueryString();

        // Transform data after pagination
        $vouchers->getCollection()->transform(function($voucher) {
            $discount = $voucher->discount_type === 'percentage' 
                ? $voucher->discount_value . '%' 
                : 'Rp ' . number_format($voucher->discount_value, 0, ',', '.');

            return [
                'id' => $voucher->id,
                'name' => $voucher->name ?? 'N/A',
                'discount' => $discount,
                'discount_value' => $voucher->discount_value,
                'program_event' => $voucher->program_name ?? 'Semua Program',
                'code' => $voucher->code ?? 'N/A',
                'is_active' => $voucher->is_active,
                'status' => $voucher->is_active ? 'Aktif' : 'Non-Aktif'
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($vouchers);
        }

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

