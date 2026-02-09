<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('articles')
            ->leftJoin('users', 'articles.author_id', '=', 'users.id')
            ->select(
                'articles.id',
                'articles.title',
                'articles.category',
                'articles.image',
                'articles.is_published',
                'articles.views',
                'articles.created_at',
                'users.name as author_name'
            );

        $data = app(DataTableService::class)->make($query, [
            'columns' => [
                ['key' => 'title', 'label' => 'Judul', 'sortable' => true, 'type' => 'primary'],
                ['key' => 'image', 'label' => 'Gambar', 'type' => 'image'],
                ['key' => 'category', 'label' => 'Kategori', 'sortable' => true],
                ['key' => 'author_name', 'label' => 'Penulis'],
                ['key' => 'views', 'label' => 'Views', 'sortable' => true],
                ['key' => 'status', 'label' => 'Status', 'type' => 'badge'],
                ['key' => 'date', 'label' => 'Tanggal', 'sortable' => true, 'type' => 'date'],
                ['key' => 'actions', 'label' => 'Aksi', 'type' => 'actions'],
            ],
            'searchable' => ['articles.title', 'articles.category', 'users.name'],
            'sortable' => ['title', 'category', 'created_at', 'is_published', 'views'],
            'sortColumns' => [
                'title' => 'articles.title',
                'category' => 'articles.category',
                'created_at' => 'articles.created_at',
                'is_published' => 'articles.is_published',
                'views' => 'articles.views',
            ],
            'actions' => ['edit', 'delete'],
            'route' => 'admin.articles',
            'title' => 'Artikel Management',
            'entity' => 'artikel',
            'createLabel' => 'Tambah Artikel',
            'searchPlaceholder' => 'Cari judul, kategori, penulis...',
            'filter' => [
                'key' => 'status',
                'column' => 'articles.is_published',
                'options' => [
                    '' => 'Semua Status',
                    'published' => 'Aktif',
                    'draft' => 'Draft',
                ]
            ],
            'transformer' => function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title ?? 'N/A',
                    'category' => $article->category ?? '-',
                    'date' => $article->created_at ? date('d F Y', strtotime($article->created_at)) : '-',
                    'image' => $article->image ? (str_starts_with($article->image, 'images/') ? $article->image : $article->image) : null,
                    'status' => $article->is_published ? 'Aktif' : 'Draft',
                    'is_published' => $article->is_published,
                    'views' => $article->views ?? 0,
                    'author_name' => $article->author_name ?? 'Admin'
                ];
            },
        ], $request);

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('admin.articles.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get existing categories from articles table
        $categories = DB::table('articles')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('admin.articles.create', compact('categories'));
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
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ], [
            'title.required' => 'Judul artikel harus diisi',
            'category.required' => 'Kategori artikel harus diisi',
            'content.required' => 'Konten artikel harus diisi',
            'status.required' => 'Status artikel harus dipilih',
            'image.required' => 'Gambar artikel harus diunggah',
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
                $imagePath = $image->storeAs('articles', $imageName, 'public');
            }

            // Create article
            $article = Article::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
                'category' => $validated['category'],
                'image' => $imagePath,
                'is_published' => $validated['status'] === 'Publish',
                'published_at' => $validated['status'] === 'Publish' ? now() : null,
                'meta_title' => $validated['meta_title'],
                'meta_description' => $validated['meta_description'],
                'meta_keywords' => $validated['meta_keywords'],
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

        // Get existing categories from articles table
        $categories = DB::table('articles')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category')
            ->toArray();

        $article = [
            'id' => $articleModel->id,
            'status' => $articleModel->is_published ? 'Publish' : 'Draft',
            'title' => $articleModel->title,
            'category' => $articleModel->category,
            'date' => $articleModel->created_at ? $articleModel->created_at->format('Y-m-d') : now()->format('Y-m-d'),
            'content' => $articleModel->content,
            'excerpt' => $articleModel->excerpt,
            'meta_title' => $articleModel->meta_title,
            'meta_description' => $articleModel->meta_description,
            'meta_keywords' => $articleModel->meta_keywords,
            'image' => $articleModel->image ? (str_starts_with($articleModel->image, 'images/') ? asset($articleModel->image) : asset('storage/' . $articleModel->image)) : null    
        ];

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // Validation (image is optional on edit)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string',
            'status' => 'required|in:Publish,Draft',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // Optional on edit, 5MB max
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
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
                if ($article->image) {
                    if (Storage::disk('public')->exists($article->image)) {
                        Storage::disk('public')->delete($article->image);
                    } elseif (file_exists(public_path($article->image))) {
                        unlink(public_path($article->image));
                    }
                }

                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('articles', $imageName, 'public');
            }

            // Update article
            $article->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'content' => $validated['content'],
                'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
                'category' => $validated['category'],
                'image' => $imagePath,
                'is_published' => $validated['status'] === 'Publish',
                'published_at' => $validated['status'] === 'Publish' && !$article->published_at ? now() : $article->published_at,
                'meta_title' => $validated['meta_title'],
                'meta_description' => $validated['meta_description'],
                'meta_keywords' => $validated['meta_keywords'],
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

    /**
     * Handle CKEditor image upload
     */
    public function uploadImage(Request $request)
    {
        try {
            if ($request->hasFile('upload')) {
                $image = $request->file('upload');

                // Validate image
                $request->validate([
                    'upload' => 'required|image|mimes:jpeg,jpg,png,gif|max:5120'
                ]);

                // Generate filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Save to public/images/articles/content
                $image->move(public_path('images/articles/content'), $filename);

                // Generate URL
                $url = asset('images/articles/content/' . $filename);

                // Return success response for CKEditor
                return response()->json([
                    'url' => $url
                ]);
            }

            return response()->json(['error' => 'No file uploaded'], 400);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

