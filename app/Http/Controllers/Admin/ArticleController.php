<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
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
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50]) ? $perPage : 10;

        $sortKey = $request->input('sort', 'created_at');
        $allowedSorts = ['title', 'category', 'created_at', 'is_published', 'views'];
        if (!in_array($sortKey, $allowedSorts)) {
            $sortKey = 'created_at';
        }
        $dir = strtolower($request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = DB::table('articles')
            ->leftJoin('users', 'articles.author_id', '=', 'users.id')
            ->select('articles.*', 'users.name as author_name')
            ->when($request->filled('search'), function ($query) use ($request) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('articles.title', 'like', '%' . $s . '%')
                        ->orWhere('articles.category', 'like', '%' . $s . '%')
                        ->orWhere('users.name', 'like', '%' . $s . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('articles.is_published', $request->input('status') === 'published' ? 1 : 0);
            });

        $articles = $query->orderBy('articles.' . $sortKey, $dir)
            ->paginate($perPage)
            ->withQueryString();

        // Transform data after pagination
        $articles->getCollection()->transform(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title ?? 'N/A',
                'category' => $article->category ?? '-',
                'date' => $article->created_at ? date('d F Y', strtotime($article->created_at)) : '-',
                'image' => $article->image ? (str_starts_with($article->image, 'images/') ? asset($article->image) : asset('storage/' . $article->image)) : null,
                'status' => $article->is_published ? 'Aktif' : 'Draft',
                'is_published' => $article->is_published,
                'views' => $article->views ?? 0,
                'author' => $article->author_name ?? 'Admin'
            ];
        });

        if ($request->wantsJson()) {
            return response()->json($articles);
        }

        return view('admin.articles.index', compact('articles'));
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
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
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
            'image' => $articleModel->image ? asset($articleModel->image) : null
        ];

        return view('admin.articles.edit', compact('article', 'categories'));
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

