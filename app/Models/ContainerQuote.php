<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContainerQuote extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_ref',
        'requester_id',
        'carrier',
        'origin_port',
        'destination_port',
        'incoterm',
        'container_type',
        'price',
        'currency',
        'transit_days',
        'breakdown',
        'inclusions',
        'exclusions',
        'valid_until',
        'status',
        'notes',
    ];

    protected $casts = [
        'breakdown' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'valid_until' => 'datetime',
        'price' => 'decimal:2',
        'transit_days' => 'integer',
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
        return $this->hasMany(ContainerBooking::class, 'quote_id');
    }

    /**
     * Check if quote is still valid
     */
    public function isValid(): bool
    {
        return $this->status === 'active' && $this->valid_until->isFuture();
    }

    /**
     * Check if quote is expired
     */
    public function isExpired(): bool
    {
        return $this->valid_until->isPast();
    }

    /**
     * Get breakdown item
     */
    public function getBreakdownItem(string $key): ?float
    {
        return $this->breakdown[$key] ?? null;
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
     * Scope expired quotes
     */
    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<=', now());
    }

    /**
     * Scope by route
     */
    public function scopeByRoute($query, string $origin, string $destination)
    {
        return $query->where('origin_port', $origin)
            ->where('destination_port', $destination);
    }

    /**
     * Scope by container type
     */
    public function scopeByContainerType($query, string $type)
    {
        return $query->where('container_type', $type);
    }
}
