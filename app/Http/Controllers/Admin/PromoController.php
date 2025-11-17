<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get promos from database with pagination (10 per page)
        $promos = DB::table('promos')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $promos->getCollection()->transform(function($promo) {
            return [
                'id' => $promo->id,
                'title' => $promo->title ?? 'N/A',
                'poster' => $promo->poster_image,
                'carousel' => $promo->carousel_image,
                'status' => $promo->is_active ? 'Aktif' : 'Non-Aktif'
            ];
        });

        return view('admin.promos.index', compact('promos'));
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

