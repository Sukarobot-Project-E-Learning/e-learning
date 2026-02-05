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
            ->select('id', 'title', 'poster_image', 'carousel_image', 'is_active', 'created_at');

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'title', 'label' => 'Judul Promo', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'poster', 'label' => 'Poster', 'type' => 'image'],
                ['key' => 'carousel', 'label' => 'Carousel', 'type' => 'image'],
                ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'status'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['title'],
            'sortable' => ['title', 'is_active', 'created_at'],
            'actions' => ['edit', 'delete'],
            'route' => 'admin.promos',
            'title' => 'Promo Management',
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
                    'poster' => $promo->poster_image,
                    'carousel' => $promo->carousel_image,
                    'is_active' => $promo->is_active,
                    'status' => $promo->is_active ? 'Aktif' : 'Non-Aktif'
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
        // TODO: Add validation
        // TODO: Handle file upload for poster and carousel
        // TODO: Save to database

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Dummy data untuk sementara
        $promo = [
            'id' => $id,
            'title' => 'Promo Workshop Branding',
            'poster' => null,
            'carousel' => null,
            'status' => 'Aktif'
        ];

        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation
        // TODO: Handle file upload for poster and carousel if changed
        // TODO: Update in database

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
                // Delete carousel file if exists
                if ($promo->carousel_image) {
                    $carouselPath = public_path($promo->carousel_image);
                    if (file_exists($carouselPath)) {
                        unlink($carouselPath);
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

