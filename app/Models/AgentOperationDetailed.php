<?php

namespace App\Models;

/**
 * Agent Operation Detailed Model
 * 
 * Purpose: إدارة عمليات الوكلاء التفصيلية
 * Relations: Agent, User
 * Policies: AgentOperationPolicy
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentOperationDetailed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'agent_id',
        'user_id',
        'operation_type',
        'status',
        'operation_code',
        'client_name',
        'service_description',
        'service_value',
        'commission_rate',
        'commission_amount',
        'net_income',
        'contract_date',
        'start_date',
        'completion_date',
        'client_rating',
        'client_feedback',
        'operation_details',
        'notes',
    ];

    protected $casts = [
        'service_value' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_income' => 'decimal:2',
        'contract_date' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'operation_details' => 'array',
    ];

    // Relations
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeShippingOps($query)
    {
        return $query->where('operation_type', 'shipping');
    }

    public function scopeBrandAgency($query)
    {
        return $query->where('operation_type', 'brand_agency');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function calculateCommission(): void
    {
        if ($this->service_value && $this->commission_rate) {
            $this->commission_amount = ($this->service_value * $this->commission_rate) / 100;
            $this->net_income = $this->service_value - $this->commission_amount;
            $this->save();
        }
    }

    public function rateOperation(int $rating, string $feedback = null): void
    {
        $this->update([
            'client_rating' => $rating,
            'client_feedback' => $feedback,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير معروف',
        };
    }
}
