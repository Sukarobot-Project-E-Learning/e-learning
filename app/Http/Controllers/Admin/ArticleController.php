<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get articles from database with pagination (10 per page)
        $articles = DB::table('articles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $articles->getCollection()->transform(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title ?? 'N/A',
                'category' => $article->category ?? '-',
                'date' => $article->created_at ? date('d F Y', strtotime($article->created_at)) : '-',
                'image' => $article->image,
                'status' => $article->is_published ? 'Aktif' : 'Draft'
            ];
        });

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

        return redirect()->route('admin.articles.index')
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

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $article = DB::table('articles')->where('id', $id)->first();
            if ($article && $article->image) {
                $filePath = public_path($article->image);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            DB::table('articles')->where('id', $id)->delete();
            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus artikel'], 500);
        }
    }
}

