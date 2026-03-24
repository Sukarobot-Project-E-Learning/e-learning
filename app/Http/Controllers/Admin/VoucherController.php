<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Voucher::query()
            ->select(
                'id',
                'name',
                'code',
                'discount_type',
                'discount_value',
                'max_usages',
                'start_date',
                'end_date',
                'is_active',
                'created_at'
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'name', 'label' => 'Nama Voucher', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'code', 'label' => 'Kode', 'sortable' => true],
                ['key' => 'discount', 'label' => 'Diskon', 'sortable' => true],
                ['key' => 'usage', 'label' => 'Terpakai'],
                ['key' => 'duration', 'label' => 'Masa Berlaku'],
                ['key' => 'status', 'label' => 'Status', 'type' => 'status'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['name', 'code'],
            'sortable' => ['name', 'code', 'discount_value', 'is_active', 'created_at'],
            'sortColumns' => [
                'name' => 'name',
                'code' => 'code',
                'discount' => 'discount_value',
            ],
            'actions' => ['edit', 'delete'],
            'route' => 'admin.vouchers',
            'title' => 'Manajemen Voucher',
            'entity' => 'voucher',
            'createLabel' => 'Tambah Voucher',
            'searchPlaceholder' => 'Cari nama, kode...',
            'filter' => [
                'key' => 'status',
                'column' => 'is_active',
                'options' => [
                    '' => 'Semua Status',
                    'active' => 'Aktif',
                    'inactive' => 'Non-Aktif',
                ]
            ],
            'transformer' => function($voucher) {
                $discount = $voucher->discount_type === 'percentage' 
                    ? rtrim(rtrim(number_format($voucher->discount_value, 2, ',', '.'), '0'), ',') . '%' 
                    : 'Rp ' . number_format($voucher->discount_value, 0, ',', '.');

                $usage = $voucher->transactions()
                    ->whereIn('status', ['paid', 'pending'])
                    ->count();

                $usageText = $voucher->max_usages ? "$usage / {$voucher->max_usages}" : "$usage (Tanpa Batas)";

                $duration = '-';
                if ($voucher->start_date || $voucher->end_date) {
                    $start = $voucher->start_date ? $voucher->start_date->format('d M y') : 'Seterusnya';
                    $end = $voucher->end_date ? $voucher->end_date->format('d M y') : 'Seterusnya';
                    $duration = "$start - $end";
                }

                return [
                    'id' => $voucher->id,
                    'name' => $voucher->name ?? '-',
                    'discount' => $discount,
                    'discount_value' => $voucher->discount_value,
                    'usage' => $usageText,
                    'duration' => $duration,
                    'code' => $voucher->code ?? '-',
                    'is_active' => $voucher->is_active,
                    'status' => $voucher->is_active ? 'Aktif' : 'Non-Aktif'
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.vouchers.index', compact('data'));
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
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'code' => 'required|string|unique:vouchers,code|max:50',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_usages' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        \App\Models\Voucher::create($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $voucher = \App\Models\Voucher::findOrFail($id);

        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $voucher = \App\Models\Voucher::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'max_usages' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
        ]);

        $voucher->update($validated);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $voucher = \App\Models\Voucher::findOrFail($id);
            $voucher->delete();
            return response()->json(['success' => true, 'message' => 'Voucher berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus voucher'], 500);
        }
    }
}

