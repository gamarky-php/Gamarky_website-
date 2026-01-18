<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TruckBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'user_id',
        'booking_ref',
        'pickup',
        'delivery',
        'driver',
        'vehicle_registration',
        'vehicle_type',
        'cargo_details',
        'docs',
        'status',
        'total_cost',
        'currency',
        'notes',
    ];

    protected $casts = [
        'pickup' => 'array',
        'delivery' => 'array',
        'driver' => 'array',
        'cargo_details' => 'array',
        'docs' => 'array',
        'total_cost' => 'decimal:2',
    ];

    /**
     * Get the quote
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(TruckQuote::class, 'quote_id');
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get tracking information
     */
    public function tracking(): HasOne
    {
        return $this->hasOne(TruckTracking::class, 'booking_id');
    }

    /**
     * Get driver name
     */
    public function getDriverNameAttribute(): ?string
    {
        return $this->driver['name'] ?? null;
    }

    /**
     * Get driver phone
     */
    public function getDriverPhoneAttribute(): ?string
    {
        return $this->driver['phone'] ?? null;
    }

    /**
     * Get pickup datetime
     */
    public function getPickupDatetimeAttribute(): ?string
    {
        return $this->pickup['datetime'] ?? null;
    }

    /**
     * Get delivery datetime
     */
    public function getDeliveryDatetimeAttribute(): ?string
    {
        return $this->delivery['datetime'] ?? null;
    }

    /**
     * Check if driver is assigned
     */
    public function hasDriverAssigned(): bool
    {
        return !empty($this->driver['name']);
    }

    /**
     * Scope confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope in transit
     */
    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
