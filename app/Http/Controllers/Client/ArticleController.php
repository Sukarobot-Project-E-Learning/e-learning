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

        return view('client.page.artikel', compact('articles'));
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

        // Increment views
        $article->incrementViews();

        // Get related articles from same category
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('client.page.artikel-detail', compact('article', 'relatedArticles'));
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

        return view('client.page.artikel-search', compact('articles', 'keyword'));
    }
}

