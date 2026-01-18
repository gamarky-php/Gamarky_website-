<?php

namespace App\Models;

/**
 * Container Operation Model
 * 
 * Purpose: إدارة عمليات الحاويات (حجز، تتبع، تسليم)
 * Relations: User
 * Policies: ContainerOperationPolicy
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContainerOperation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'operation_type',
        'status',
        'container_number',
        'container_type',
        'seal_number',
        'shipping_line',
        'vessel_name',
        'voyage_number',
        'loading_port',
        'discharge_port',
        'etd',
        'eta',
        'actual_departure',
        'actual_arrival',
        'freight_cost',
        'handling_charges',
        'detention_charges',
        'total_cost',
        'tracking_history',
        'current_location',
        'notes',
    ];

    protected $casts = [
        'etd' => 'date',
        'eta' => 'date',
        'actual_departure' => 'date',
        'actual_arrival' => 'date',
        'freight_cost' => 'decimal:2',
        'handling_charges' => 'decimal:2',
        'detention_charges' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'tracking_history' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeBooking($query)
    {
        return $query->where('operation_type', 'booking');
    }

    public function scopeTracking($query)
    {
        return $query->where('operation_type', 'tracking');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    // Methods
    public function addTrackingUpdate(string $location, string $status, string $notes = null): void
    {
        $history = $this->tracking_history ?? [];
        
        $history[] = [
            'timestamp' => now()->toIso8601String(),
            'location' => $location,
            'status' => $status,
            'notes' => $notes,
        ];

        $this->tracking_history = $history;
        $this->current_location = $location;
        $this->save();
    }

    public function calculateTotalCost(): void
    {
        $this->total_cost = ($this->freight_cost ?? 0)
                          + ($this->handling_charges ?? 0)
                          + ($this->detention_charges ?? 0);
        $this->save();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'booked' => 'محجوز',
            'in_transit' => 'قيد النقل',
            'at_port' => 'في الميناء',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            default => 'غير معروف',
        };
    }

    public function getContainerTypeLabelAttribute(): string
    {
        return match($this->container_type) {
            '20ft' => '20 قدم',
            '40ft' => '40 قدم',
            '40ft_hc' => '40 قدم عالي',
            'reefer' => 'مبرد',
            default => $this->container_type,
        };
    }
}
