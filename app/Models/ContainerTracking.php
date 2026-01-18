<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContainerTracking extends Model
{
    use HasFactory;

    protected $table = 'container_tracking';

    protected $fillable = [
        'booking_id',
        'container_no',
        'bol',
        'status',
        'progress',
        'position',
        'eta',
        'actual_arrival',
        'current_location',
        'vessel_name',
        'voyage_number',
    ];

    protected $casts = [
        'progress' => 'array',
        'position' => 'array',
        'eta' => 'datetime',
        'actual_arrival' => 'datetime',
    ];

    /**
     * Get the booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(ContainerBooking::class, 'booking_id');
    }

    /**
     * Get last event from progress
     */
    public function getLastEventAttribute(): ?array
    {
        if (!$this->progress || count($this->progress) === 0) {
            return null;
        }

        return end($this->progress);
    }

    /**
     * Get latitude
     */
    public function getLatitudeAttribute(): ?float
    {
        return $this->position['lat'] ?? null;
    }

    /**
     * Get longitude
     */
    public function getLongitudeAttribute(): ?float
    {
        return $this->position['lng'] ?? null;
    }

    /**
     * Add tracking event
     */
    public function addEvent(string $location, string $event, ?string $notes = null): void
    {
        $progress = $this->progress ?? [];
        
        $progress[] = [
            'location' => $location,
            'event' => $event,
            'timestamp' => now()->toDateTimeString(),
            'notes' => $notes,
        ];

        $this->progress = $progress;
        $this->current_location = $location;
        $this->save();
    }

    /**
     * Update position
     */
    public function updatePosition(float $lat, float $lng, ?string $locationName = null): void
    {
        $this->position = [
            'lat' => $lat,
            'lng' => $lng,
            'location_name' => $locationName,
            'updated_at' => now()->toDateTimeString(),
        ];

        if ($locationName) {
            $this->current_location = $locationName;
        }

        $this->save();
    }

    /**
     * Scope by container number
     */
    public function scopeByContainerNo($query, string $containerNo)
    {
        return $query->where('container_no', $containerNo);
    }

    /**
     * Scope by BOL
     */
    public function scopeByBol($query, string $bol)
    {
        return $query->where('bol', $bol);
    }

    /**
     * Scope in transit
     */
    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }
}
