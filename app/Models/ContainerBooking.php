<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ContainerBooking extends Model
{
    use HasFactory;

    protected $table = 'container_bookings';

    protected $fillable = [
        'quote_id',
        'user_id',
        'shipper_id',
        'booking_ref',
        'container_no',
        'seal_no',
        'container_type',
        'schedule',
        'docs',
        'cargo_details',
        'payment',
        'status',
        'notes',
    ];

    protected $casts = [
        'schedule' => 'array',
        'docs' => 'array',
        'cargo_details' => 'array',
        'payment' => 'array',
    ];

    /**
     * Get the quote
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(ContainerQuote::class, 'quote_id');
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
        return $this->hasOne(ContainerTracking::class, 'booking_id');
    }

    /**
     * Get ETD (Estimated Time of Departure)
     */
    public function getEtdAttribute(): ?string
    {
        return $this->schedule['etd'] ?? null;
    }

    /**
     * Get ETA (Estimated Time of Arrival)
     */
    public function getEtaAttribute(): ?string
    {
        return $this->schedule['eta'] ?? null;
    }

    /**
     * Get vessel name
     */
    public function getVesselNameAttribute(): ?string
    {
        return $this->schedule['vessel'] ?? null;
    }

    /**
     * Check if payment is completed
     */
    public function isPaymentCompleted(): bool
    {
        return isset($this->payment['status']) && $this->payment['status'] === 'completed';
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
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
}
