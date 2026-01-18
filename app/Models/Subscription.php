<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'start_at',
        'end_at',
        'status',
        'features',
        'price',
        'currency',
        'payment_method',
    ];

    protected $casts = [
        'features' => 'array',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' 
            && $this->end_at 
            && $this->end_at->isFuture();
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' 
            || ($this->end_at && $this->end_at->isPast());
    }

    /**
     * Check if user has specific feature
     */
    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }

    /**
     * Get days remaining
     */
    public function daysRemaining(): int
    {
        if (!$this->end_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_at, false));
    }

    /**
     * Scope active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_at', '>', now());
    }

    /**
     * Scope expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere('end_at', '<=', now());
    }
}
