<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $promos = [
            [
                'id' => 1,
                'title' => 'Promo Workshop Branding',
                'poster' => null,
                'carousel' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'title' => 'Promo Workshop Branding',
                'poster' => null,
                'carousel' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 3,
                'title' => 'Promo Workshop Branding',
                'poster' => null,
                'carousel' => null,
                'status' => 'Non-Aktif'
            ],
        ];

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

        return redirect()->route('elearning.admin.promos.index')
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

        return redirect()->route('elearning.admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // TODO: Delete files if exists

        return redirect()->route('elearning.admin.promos.index')
            ->with('success', 'Promo berhasil dihapus');
    }
}

