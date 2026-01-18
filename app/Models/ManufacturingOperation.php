<?php

namespace App\Models;

/**
 * Manufacturing Operation Model
 * 
 * Purpose: إدارة عمليات التصنيع (BOM، تكاليف الإنتاج، عروض الأسعار)
 * Relations: User
 * Policies: ManufacturingOperationPolicy
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManufacturingOperation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'operation_type',
        'status',
        'product_name',
        'product_code',
        'target_quantity',
        'bom_items',
        'operations',
        'overhead_costs',
        'material_cost',
        'labor_cost',
        'overhead_cost',
        'total_cost',
        'unit_cost',
        'start_date',
        'expected_completion_date',
        'actual_completion_date',
        'notes',
    ];

    protected $casts = [
        'target_quantity' => 'decimal:2',
        'bom_items' => 'array',
        'operations' => 'array',
        'overhead_costs' => 'array',
        'material_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'overhead_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public function calculateCosts(): void
    {
        $materialCost = 0;
        if ($this->bom_items) {
            foreach ($this->bom_items as $item) {
                $materialCost += ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        }

        $laborCost = 0;
        if ($this->operations) {
            foreach ($this->operations as $operation) {
                $laborCost += ($operation['hours'] ?? 0) * ($operation['rate'] ?? 0);
            }
        }

        $overheadCost = 0;
        if ($this->overhead_costs) {
            foreach ($this->overhead_costs as $overhead) {
                $overheadCost += $overhead['amount'] ?? 0;
            }
        }

        $this->material_cost = $materialCost;
        $this->labor_cost = $laborCost;
        $this->overhead_cost = $overheadCost;
        $this->total_cost = $materialCost + $laborCost + $overheadCost;
        
        if ($this->target_quantity > 0) {
            $this->unit_cost = $this->total_cost / $this->target_quantity;
        }

        $this->save();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'on_hold' => 'معلق',
            'cancelled' => 'ملغي',
            default => 'غير معروف',
        };
    }
}
