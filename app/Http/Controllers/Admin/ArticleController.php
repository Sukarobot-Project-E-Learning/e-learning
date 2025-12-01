<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get articles from database with pagination (10 per page)
        $articles = Article::with('author')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform data after pagination
        $articles->getCollection()->transform(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title ?? 'N/A',
                'category' => $article->category ?? '-',
                'date' => $article->formatted_created_date,
                'image' => $article->image_url,
                'status' => $article->is_published ? 'Aktif' : 'Draft',
                'views' => $article->views ?? 0,
                'author' => $article->author->name ?? 'Admin'
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
        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'status' => 'required|in:Publish,Draft',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ], [
            'title.required' => 'Judul artikel harus diisi',
            'category.required' => 'Kategori artikel harus diisi',
            'content.required' => 'Konten artikel harus diisi',
            'status.required' => 'Status artikel harus dipilih',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'image.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/articles'), $imageName);
                $imagePath = 'uploads/articles/' . $imageName;
            }

            // Create article
            $article = Article::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => Str::limit(strip_tags($validated['content']), 150),
                'category' => $validated['category'],
                'image' => $imagePath,
                'is_published' => $validated['status'] === 'Publish',
                'published_at' => $validated['status'] === 'Publish' ? now() : null,
                'author_id' => Auth::id(),
                'views' => 0,
            ]);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil ditambahkan');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan artikel: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $articleModel = Article::findOrFail($id);
        
        $article = [
            'id' => $articleModel->id,
            'status' => $articleModel->is_published ? 'Publish' : 'Draft',
            'title' => $articleModel->title,
            'category' => $articleModel->category,
            'date' => $articleModel->created_at ? $articleModel->created_at->format('Y-m-d') : now()->format('Y-m-d'),
            'content' => $articleModel->content,
            'image' => $articleModel->image ? asset($articleModel->image) : null
        ];

        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'status' => 'required|in:Publish,Draft',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ], [
            'title.required' => 'Judul artikel harus diisi',
            'category.required' => 'Kategori artikel harus diisi',
            'content.required' => 'Konten artikel harus diisi',
            'status.required' => 'Status artikel harus dipilih',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'image.max' => 'Ukuran gambar maksimal 5MB',
        ]);

        try {
            // Handle image upload
            $imagePath = $article->image;
            if ($request->hasFile('image')) {
                // Delete old image
                if ($article->image && file_exists(public_path($article->image))) {
                    unlink(public_path($article->image));
                }

                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/articles'), $imageName);
                $imagePath = 'uploads/articles/' . $imageName;
            }

            // Update article
            $article->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => Str::limit(strip_tags($validated['content']), 150),
                'category' => $validated['category'],
                'image' => $imagePath,
                'is_published' => $validated['status'] === 'Publish',
                'published_at' => $validated['status'] === 'Publish' && !$article->published_at ? now() : $article->published_at,
            ]);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil diperbarui');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate artikel: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $article = Article::findOrFail($id);
            
            // Delete image file
            if ($article->image && file_exists(public_path($article->image))) {
                unlink(public_path($article->image));
            }
            
            // Delete article
            $article->delete();
            
            return response()->json(['success' => true, 'message' => 'Artikel berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus artikel: ' . $e->getMessage()], 500);
        }
    }
}

