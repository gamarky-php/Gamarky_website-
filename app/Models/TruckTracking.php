<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TruckTracking extends Model
{
    use HasFactory;

    protected $table = 'truck_tracking';

    protected $fillable = [
        'booking_id',
        'status',
        'eta',
        'actual_delivery',
        'last_position',
        'route',
        'events',
        'distance_traveled_km',
    ];

    protected $casts = [
        'eta' => 'datetime',
        'actual_delivery' => 'datetime',
        'last_position' => 'array',
        'route' => 'array',
        'events' => 'array',
        'distance_traveled_km' => 'decimal:2',
    ];

    /**
     * Get the booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(TruckBooking::class, 'booking_id');
    }

    /**
     * Get latitude
     */
    public function getLatitudeAttribute(): ?float
    {
        return $this->last_position['lat'] ?? null;
    }

    /**
     * Get longitude
     */
    public function getLongitudeAttribute(): ?float
    {
        return $this->last_position['lng'] ?? null;
    }

    /**
     * Get current address
     */
    public function getCurrentAddressAttribute(): ?string
    {
        return $this->last_position['address'] ?? null;
    }

    /**
     * Get last event
     */
    public function getLastEventAttribute(): ?array
    {
        if (!$this->events || count($this->events) === 0) {
            return null;
        }

        return end($this->events);
    }

    /**
     * Update position
     */
    public function updatePosition(float $lat, float $lng, ?string $address = null): void
    {
        $this->last_position = [
            'lat' => $lat,
            'lng' => $lng,
            'address' => $address,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->save();
    }

    /**
     * Add event
     */
    public function addEvent(string $event, ?string $location = null, ?string $notes = null): void
    {
        $events = $this->events ?? [];
        
        $events[] = [
            'event' => $event,
            'location' => $location,
            'notes' => $notes,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->events = $events;
        $this->save();
    }

    /**
     * Update distance traveled
     */
    public function updateDistance(float $km): void
    {
        $this->distance_traveled_km = $km;
        $this->save();
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
