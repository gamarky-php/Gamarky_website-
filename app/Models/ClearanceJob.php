<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClearanceJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'broker_id',
        'shipment_ref',
        'bl_number',
        'stages',
        'sla_days',
        'expected_clearance_date',
        'actual_clearance_date',
        'status',
        'total_fees',
        'currency',
        'fees_breakdown',
        'notes',
    ];

    protected $casts = [
        'stages' => 'array',
        'fees_breakdown' => 'array',
        'sla_days' => 'integer',
        'expected_clearance_date' => 'date',
        'actual_clearance_date' => 'date',
        'total_fees' => 'decimal:2',
    ];

    /**
     * Get the client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the broker
     */
    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    /**
     * Check if job is overdue
     */
    public function isOverdue(): bool
    {
        return $this->expected_clearance_date 
            && $this->expected_clearance_date->isPast() 
            && !in_array($this->status, ['cleared', 'released', 'cancelled']);
    }

    /**
     * Get days until clearance
     */
    public function getDaysUntilClearanceAttribute(): int
    {
        if (!$this->expected_clearance_date) {
            return 0;
        }

        return now()->diffInDays($this->expected_clearance_date, false);
    }

    /**
     * Get current stage
     */
    public function getCurrentStageAttribute(): ?array
    {
        if (!$this->stages) {
            return null;
        }

        $incomplete = array_filter($this->stages, fn($stage) => 
            !isset($stage['status']) || $stage['status'] !== 'completed'
        );

        return reset($incomplete) ?: null;
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentageAttribute(): float
    {
        if (!$this->stages || count($this->stages) === 0) {
            return 0.0;
        }

        $completed = array_filter($this->stages, fn($stage) => 
            isset($stage['status']) && $stage['status'] === 'completed'
        );

        return round((count($completed) / count($this->stages)) * 100, 2);
    }

    /**
     * Update stage status
     */
    public function updateStage(string $stageName, string $status, ?string $notes = null): void
    {
        $stages = $this->stages ?? [];

        foreach ($stages as &$stage) {
            if ($stage['stage'] === $stageName) {
                $stage['status'] = $status;
                $stage['updated_at'] = now()->toDateTimeString();
                if ($notes) {
                    $stage['notes'] = $notes;
                }
                break;
            }
        }

        $this->stages = $stages;
        $this->save();
    }

    /**
     * Scope pending jobs
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope cleared jobs
     */
    public function scopeCleared($query)
    {
        return $query->where('status', 'cleared');
    }

    /**
     * Scope overdue jobs
     */
    public function scopeOverdue($query)
    {
        return $query->where('expected_clearance_date', '<', now())
            ->whereNotIn('status', ['cleared', 'released', 'cancelled']);
    }

    /**
     * Scope by broker
     */
    public function scopeByBroker($query, int $brokerId)
    {
        return $query->where('broker_id', $brokerId);
    }
}
