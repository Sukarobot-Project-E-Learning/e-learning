<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data untuk sementara
        $articles = [
            [
                'id' => 1,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 2,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 3,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 4,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Aktif'
            ],
            [
                'id' => 5,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Draft'
            ],
            [
                'id' => 6,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Draft'
            ],
            [
                'id' => 7,
                'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
                'category' => 'Produk',
                'date' => '20 September 2025',
                'image' => null,
                'status' => 'Draft'
            ],
        ];

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Add validation
        // TODO: Handle file upload for image
        // TODO: Save to database

        return redirect()->route('elearning.admin.articles.index')
            ->with('success', 'Artikel berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Dummy data untuk sementara
        $article = [
            'id' => $id,
            'status' => 'Publish',
            'title' => 'Lengan Robotik Baru untuk Industri Otomotif',
            'category' => 'Produk',
            'date' => '20 September 2025',
            'content' => 'The three greatest things you learn from traveling...',
            'image' => null
        ];

        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Add validation
        // TODO: Handle file upload for image if changed
        // TODO: Update in database

        return redirect()->route('elearning.admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // TODO: Delete from database
        // TODO: Delete file if exists

        return redirect()->route('elearning.admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus');
    }
}

