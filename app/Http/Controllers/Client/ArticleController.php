<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(Request $request)
    {
        $query = Article::published()
            ->with('author')
            ->orderBy('published_at', 'desc');

        // Filter by category if provided
        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        // Get articles
        $articles = $query->get();

        return view('client.artikel.index', compact('articles'));
    }

    /**
     * Get articles as JSON for AJAX requests.
     */
    public function getArticles(Request $request)
    {
        $query = Article::published()
            ->with('author')
            ->orderBy('published_at', 'desc');

        // Filter by category if provided
        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        // Sort by date or views
        if ($request->has('sort')) {
            if ($request->sort === 'oldest') {
                $query->orderBy('published_at', 'asc');
            } elseif ($request->sort === 'popular') {
                $query->orderBy('views', 'desc');
            }
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $articles = $query->paginate($perPage);

        // Transform data for JSON response
        $transformedArticles = $articles->map(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'slug' => $article->slug,
                'excerpt' => $article->excerpt,
                'category' => $article->category,
                'image' => $article->image_url,
                'published_at' => $article->formatted_published_date,
                'views' => $article->views,
                'author' => $article->author->name ?? 'Admin',
            ];
        });

        return response()->json([
            'data' => $transformedArticles,
            'current_page' => $articles->currentPage(),
            'last_page' => $articles->lastPage(),
            'per_page' => $articles->perPage(),
            'total' => $articles->total(),
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show($slug)
    {
        $article = Article::published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        // Track view in database (unique per user or IP)
        $userId = auth()->id(); // NULL for guests
        $ipAddress = request()->ip();
        
        // Check if this user/IP already viewed this article
        $alreadyViewed = \App\Models\ArticleView::where('article_id', $article->id)
            ->where(function($query) use ($userId, $ipAddress) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('ip_address', $ipAddress)
                          ->whereNull('user_id');
                }
            })
            ->exists();

        // If not viewed yet, record the view
        if (!$alreadyViewed) {
            \App\Models\ArticleView::create([
                'article_id' => $article->id,
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => request()->userAgent(),
            ]);
            
            // Increment the views counter
            $article->incrementViews();
        }

        // Get related articles from same category
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('client.artikel.detail', compact('article', 'relatedArticles'));
    }

    /**
     * Search articles.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');

        $articles = Article::published()
            ->with('author')
            ->where(function($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                      ->orWhere('content', 'like', '%' . $keyword . '%')
                      ->orWhere('excerpt', 'like', '%' . $keyword . '%');
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('client.artikel.search', compact('articles', 'keyword'));
    }
}


