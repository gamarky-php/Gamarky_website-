<?php

namespace App\Models;

/**
 * Import Operation Model
 * 
 * Purpose: إدارة عمليات الاستيراد (عروض أسعار، شحنات، تخليص جمركي)
 * Relations: User, CustomsOperation
 * Policies: ImportOperationPolicy
 * 
 * @property int $id
 * @property int $user_id
 * @property string $operation_type
 * @property string $status
 * @property string $product_name
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportOperation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'operation_type',
        'status',
        'hs_code',
        'product_name',
        'quantity',
        'unit',
        'origin_country',
        'origin_port',
        'destination_port',
        'customs_duty',
        'vat_amount',
        'shipping_cost',
        'total_cost',
        'expected_arrival_date',
        'actual_arrival_date',
        'clearance_date',
        'documents',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'customs_duty' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'expected_arrival_date' => 'date',
        'actual_arrival_date' => 'date',
        'clearance_date' => 'date',
        'documents' => 'array',
    ];

    // العلاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customsOperation(): HasOne
    {
        return $this->hasOne(CustomsOperation::class, 'import_operation_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('operation_type', $type);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    // Accessors & Mutators
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

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->expected_arrival_date) {
            return false;
        }
        
        return $this->expected_arrival_date->isPast() && $this->status !== 'completed';
    }

    // Methods
    public function calculateTotalCost(): void
    {
        $this->total_cost = ($this->customs_duty ?? 0) 
                          + ($this->vat_amount ?? 0) 
                          + ($this->shipping_cost ?? 0);
        $this->save();
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'actual_arrival_date' => now(),
        ]);
    }
}
