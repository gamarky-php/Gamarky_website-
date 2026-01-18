<?php

namespace App\Models;

/**
 * Export Operation Model
 * 
 * Purpose: إدارة عمليات التصدير (عروض، شحنات، أبحاث السوق)
 * Relations: User
 * Policies: ExportOperationPolicy
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExportOperationDetailed extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ تحديد اسم الجدول الصحيح
    protected $table = 'export_operation_details';

    protected $fillable = [
        'user_id',
        'operation_type',
        'status',
        'product_name',
        'product_description',
        'reference_number',
        'destination_country',
        'hs_code',
        'quantity',
        'unit',
        'target_country',
        'target_market',
        'market_requirements',
        'fob_price',
        'shipping_cost',
        'insurance_cost',
        'total_cost',
        'total_value',
        'shipment_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'export_documents',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'fob_price' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'insurance_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_value' => 'decimal:2',
        'shipment_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'market_requirements' => 'array',
        'export_documents' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByCountry($query, string $country)
    {
        return $query->where('target_country', $country);
    }

    public function scopeThisQuarter($query)
    {
        $quarter = ceil(now()->month / 3);
        $startMonth = ($quarter - 1) * 3 + 1;
        
        return $query->whereMonth('created_at', '>=', $startMonth)
                     ->whereMonth('created_at', '<=', $startMonth + 2)
                     ->whereYear('created_at', now()->year);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التسليم',
            'cancelled' => 'ملغي',
            default => 'غير معروف',
        };
    }

    public function calculateTotalCost(): void
    {
        $this->total_cost = ($this->fob_price ?? 0)
                          + ($this->shipping_cost ?? 0)
                          + ($this->insurance_cost ?? 0);
        $this->save();
    }
}
