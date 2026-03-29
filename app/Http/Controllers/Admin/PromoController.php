<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('promos')
            ->select('id', 'title', 'poster_image', 'is_active', 'created_at');

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'title', 'label' => 'Judul Promo', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'poster', 'label' => 'Poster', 'type' => 'image'],
                ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'status'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['title'],
            'sortable' => ['title', 'is_active', 'created_at'],
            'actions' => ['edit', 'delete'],
            'route' => 'admin.promos',
            'title' => 'Manajemen Promo',
            'entity' => 'promo',
            'createLabel' => 'Tambah Promo',
            'searchPlaceholder' => 'Cari judul promo...',
            'filter' => [
                'key' => 'status',
                'column' => 'is_active',
                'options' => [
                    '' => 'Semua Status',
                    'active' => 'Aktif',
                    'inactive' => 'Non-Aktif',
                ]
            ],
            'transformer' => function($promo) {
                return [
                    'id' => $promo->id,
                    'title' => $promo->title ?? 'N/A',
                    'poster' => $promo->poster_image ? asset($promo->poster_image) : null,
                    'is_active' => $promo->is_active,
                    'status' => ($promo->is_active == 1)
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.promos.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'poster' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $file = $request->file('poster');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/promos'), $filename);
            $posterPath = 'uploads/promos/' . $filename;
        }

        // Jika promo baru disetel aktif, nonaktifkan promo yang lain
        if ($request->is_active) {
            DB::table('promos')->update(['is_active' => 0]);
        }

        DB::table('promos')->insert([
            'title' => $request->title,
            'is_active' => $request->is_active,
            'poster_image' => $posterPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promo = DB::table('promos')->where('id', $id)->first();
        
        if (!$promo) {
            return redirect()->route('admin.promos.index')->with('error', 'Promo tidak ditemukan');
        }


        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $promo = DB::table('promos')->where('id', $id)->first();
        if (!$promo) {
            return redirect()->route('admin.promos.index')->with('error', 'Promo tidak ditemukan');
        }

        $posterPath = $promo->poster_image;
        if ($request->hasFile('poster')) {
            if ($posterPath && file_exists(public_path($posterPath))) {
                unlink(public_path($posterPath));
            }
            $file = $request->file('poster');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/promos'), $filename);
            $posterPath = 'uploads/promos/' . $filename;
        }

        // Jika promo ini disetel aktif, nonaktifkan promo yang lain
        if ($request->is_active) {
            DB::table('promos')->where('id', '!=', $id)->update(['is_active' => 0]);
        }

        DB::table('promos')->where('id', $id)->update([
            'title' => $request->title,
            'is_active' => $request->is_active,
            'poster_image' => $posterPath,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $promo = DB::table('promos')->where('id', $id)->first();
            if ($promo) {
                // Delete poster file if exists
                if ($promo->poster_image) {
                    $posterPath = public_path($promo->poster_image);
                    if (file_exists($posterPath)) {
                        unlink($posterPath);
                    }
                }
            }
            DB::table('promos')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Promo berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus promo'], 500);
        }
    }
}

