<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TruckQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_ref',
        'requester_id',
        'carrier',
        'vehicle_type',
        'pickup',
        'delivery',
        'distance_km',
        'total_price',
        'currency',
        'transit_hours',
        'breakdown',
        'inclusions',
        'valid_until',
        'status',
        'notes',
    ];

    protected $casts = [
        'pickup' => 'array',
        'delivery' => 'array',
        'breakdown' => 'array',
        'inclusions' => 'array',
        'valid_until' => 'datetime',
        'total_price' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'transit_hours' => 'integer',
    ];

    /**
     * Get the requester
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get bookings for this quote
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(TruckBooking::class, 'quote_id');
    }

    /**
     * Check if quote is still valid
     */
    public function isValid(): bool
    {
        return $this->status === 'active' && $this->valid_until->isFuture();
    }

    /**
     * Get pickup address
     */
    public function getPickupAddressAttribute(): ?string
    {
        return $this->pickup['address'] ?? null;
    }

    /**
     * Get delivery address
     */
    public function getDeliveryAddressAttribute(): ?string
    {
        return $this->delivery['address'] ?? null;
    }

    /**
     * Scope active quotes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('valid_until', '>', now());
    }

    /**
     * Scope by vehicle type
     */
    public function scopeByVehicleType($query, string $type)
    {
        return $query->where('vehicle_type', $type);
    }
}
