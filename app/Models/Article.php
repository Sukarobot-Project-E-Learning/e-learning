<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'category',
        'image',
        'is_published',
        'published_at',
        'author_id',
        'views',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'views' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from title when creating
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            // Auto-generate excerpt from content if not provided
            if (empty($article->excerpt) && !empty($article->content)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 150);
            }
        });

        // Auto-update slug when title changes
        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            // Auto-update excerpt if content changes and excerpt is empty
            if ($article->isDirty('content') && empty($article->excerpt)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 150);
            }
        });
    }

    /**
     * Get the author of the article.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope a query to only include draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Increment the views count.
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get formatted published date.
     */
    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d F Y') : '-';
    }

    /**
     * Get formatted created date.
     */
    public function getFormattedCreatedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d F Y') : '-';
    }

    /**
     * Get article image URL.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('assets/elearning/client/img/blogilustrator.jpeg');
        }
        
        // Handle old images/ path
        if (str_starts_with($this->image, 'images/')) {
            return asset($this->image);
        }
        
        // Handle storage path
        return asset('storage/' . $this->image);
    }
}


