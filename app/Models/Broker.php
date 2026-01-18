<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'country',
        'ports',
        'activities',
        'experience_years',
        'score',
        'certifications',
        'email',
        'phone',
        'address',
        'website',
        'status',
        'contact_person',
    ];

    protected $casts = [
        'ports' => 'array',
        'activities' => 'array',
        'certifications' => 'array',
        'contact_person' => 'array',
        'experience_years' => 'integer',
        'score' => 'decimal:2',
    ];

    /**
     * Get broker documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(BrokerDocument::class);
    }

    /**
     * Get broker reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(BrokerReview::class);
    }

    /**
     * Get clearance jobs
     */
    public function clearanceJobs(): HasMany
    {
        return $this->hasMany(ClearanceJob::class);
    }

    /**
     * Get average rating from reviews
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()
            ->where('status', 'approved')
            ->avg('rating') ?? 0.0;
    }

    /**
     * Get total reviews count
     */
    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()
            ->where('status', 'approved')
            ->count();
    }

    /**
     * Check if broker operates in specific port
     */
    public function operatesInPort(string $portCode): bool
    {
        return in_array($portCode, $this->ports ?? []);
    }

    /**
     * Check if broker has specific activity
     */
    public function hasActivity(string $activity): bool
    {
        return in_array($activity, $this->activities ?? []);
    }

    /**
     * Check if broker has certification
     */
    public function hasCertification(string $certification): bool
    {
        return in_array($certification, $this->certifications ?? []);
    }

    /**
     * Scope active brokers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope by country
     */
    public function scopeByCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope by minimum score
     */
    public function scopeMinimumScore($query, float $score)
    {
        return $query->where('score', '>=', $score);
    }

    /**
     * Scope by port
     */
    public function scopeByPort($query, string $portCode)
    {
        return $query->whereJsonContains('ports', $portCode);
    }

    /**
     * Scope by activity
     */
    public function scopeByActivity($query, string $activity)
    {
        return $query->whereJsonContains('activities', $activity);
    }
}
