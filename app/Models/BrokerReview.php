<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrokerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'broker_id',
        'reviewer_id',
        'source',
        'rating',
        'comments',
        'evidence_links',
        'criteria_scores',
        'is_verified',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'evidence_links' => 'array',
        'criteria_scores' => 'array',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the broker
     */
    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    /**
     * Get the reviewer
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get average criteria score
     */
    public function getAverageCriteriaScoreAttribute(): float
    {
        if (!$this->criteria_scores) {
            return 0.0;
        }

        $scores = array_values($this->criteria_scores);
        return array_sum($scores) / count($scores);
    }

    /**
     * Scope approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope by source
     */
    public function scopeFromSource($query, string $source)
    {
        return $query->where('source', $source);
    }

    /**
     * Scope by minimum rating
     */
    public function scopeMinimumRating($query, int $rating)
    {
        return $query->where('rating', '>=', $rating);
    }
}
