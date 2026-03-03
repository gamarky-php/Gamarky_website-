<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * JourneyEntitlement Model
 * 
 * Represents user's active entitlement to use journey services
 * Active until journey is completed (not time-based)
 * 
 * Innovation: "الاشتراك مقابل الوظيفة" - Subscription per function/journey
 */
class JourneyEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'journey_id',
        'user_id',
        'active',
        'activated_at',
        'expires_at',
        'services_accessed',
        'last_accessed_at',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'activated_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    protected $attributes = [
        'active' => false,
        'services_accessed' => 0,
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function journey(): BelongsTo
    {
        return $this->belongsTo(Journey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ========================================
    // STATUS CHECKS
    // ========================================

    public function isActive(): bool
    {
        return $this->active === true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // ========================================
    // ACTIVATION/DEACTIVATION
    // ========================================

    public function activate(): bool
    {
        $this->active = true;
        $this->activated_at = $this->activated_at ?? now();
        return $this->save();
    }

    public function deactivate(): bool
    {
        $this->active = false;
        $this->expires_at = $this->expires_at ?? now();
        return $this->save();
    }

    // ========================================
    // USAGE TRACKING
    // ========================================

    public function incrementAccess(): bool
    {
        $this->services_accessed++;
        $this->last_accessed_at = now();
        return $this->save();
    }

    public function recordAccess(): bool
    {
        $this->last_accessed_at = now();
        return $this->save();
    }

    // ========================================
    // DISPLAY HELPERS
    // ========================================

    public function getDurationInDays(): ?int
    {
        if (!$this->activated_at) {
            return null;
        }

        $endDate = $this->expires_at ?? now();
        return $this->activated_at->diffInDays($endDate);
    }

    public function getStatusLabel(): string
    {
        if ($this->active) {
            return 'نشط';
        }

        if ($this->expires_at) {
            return 'منتهي';
        }

        return 'غير نشط';
    }
}
