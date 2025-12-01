<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleView extends Model
{
    protected $table = 'article_views';
    
    public $timestamps = false; // Using viewed_at instead
    
    protected $fillable = [
        'article_id',
        'user_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the article that this view belongs to.
     */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Get the user who viewed the article.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
