<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'category',
        'author_id',
        'media_ids',
        'status',
        'seo',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'media_ids' => 'array',
        'seo' => 'array',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    /**
     * Get the author of the article
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get media items for this article
     */
    public function media()
    {
        if (!$this->media_ids) {
            return collect();
        }

        return Media::whereIn('id', $this->media_ids)->get();
    }

    /**
     * Check if article is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
            && $this->published_at 
            && $this->published_at->isPast();
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get SEO meta title
     */
    public function getMetaTitleAttribute(): ?string
    {
        return $this->seo['meta_title'] ?? $this->title;
    }

    /**
     * Get SEO meta description
     */
    public function getMetaDescriptionAttribute(): ?string
    {
        return $this->seo['meta_description'] ?? $this->excerpt;
    }

    /**
     * Get SEO keywords
     */
    public function getKeywordsAttribute(): ?array
    {
        return $this->seo['keywords'] ?? [];
    }

    /**
     * Scope published articles
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope draft articles
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope recent articles
     */
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit);
    }

    /**
     * Scope popular articles
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->published()
            ->orderBy('views_count', 'desc')
            ->limit($limit);
    }
}
