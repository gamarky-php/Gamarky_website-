<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * JourneyItem Model
 * 
 * Represents a service/item within a journey
 * Each item can be free or paid
 */
class JourneyItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'journey_id',
        'service_key',
        'service_name',
        'service_description',
        'provider_fee',
        'platform_fee',
        'item_total',
        'is_free',
        'free_reason',
        'status',
        'service_params',
        'notes',
    ];

    protected $casts = [
        'service_params' => 'array',
        'provider_fee' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'item_total' => 'decimal:2',
        'is_free' => 'boolean',
    ];

    protected $attributes = [
        'status' => 'selected',
        'is_free' => false,
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function journey(): BelongsTo
    {
        return $this->belongsTo(Journey::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeSelected($query)
    {
        return $query->where('status', 'selected');
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePaidServices($query)
    {
        return $query->where('is_free', false);
    }

    // ========================================
    // STATUS CHECKS
    // ========================================

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isSelected(): bool
    {
        return $this->status === 'selected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isFree(): bool
    {
        return $this->is_free;
    }

    // ========================================
    // PRICE CALCULATIONS
    // ========================================

    public function calculateTotal(): void
    {
        if ($this->is_free) {
            $this->item_total = 0;
        } else {
            $this->item_total = $this->provider_fee + $this->platform_fee;
        }
    }

    public function recalculateAndSave(): bool
    {
        $this->calculateTotal();
        return $this->save();
    }

    // ========================================
    // STATUS TRANSITIONS
    // ========================================

    public function markAsPaid(): bool
    {
        $this->status = 'paid';
        return $this->save();
    }

    public function markAsCancelled(): bool
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    // ========================================
    // BOOT METHOD - Auto Calculate Total
    // ========================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->calculateTotal();
        });
    }

    // ========================================
    // DISPLAY HELPERS
    // ========================================

    public function getFormattedProviderFeeAttribute(): string
    {
        return number_format($this->provider_fee, 2) . ' EGP';
    }

    public function getFormattedPlatformFeeAttribute(): string
    {
        return number_format($this->platform_fee, 2) . ' EGP';
    }

    public function getFormattedItemTotalAttribute(): string
    {
        return number_format($this->item_total, 2) . ' EGP';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'selected' => 'محدد',
            'paid' => 'مدفوع',
            'cancelled' => 'ملغى',
            default => $this->status,
        };
    }
}
