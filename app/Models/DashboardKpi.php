<?php

namespace App\Models;

/**
 * Dashboard KPI Model
 * 
 * Purpose: مؤشرات الأداء الرئيسية للوحة التحكم
 * Relations: None (Aggregated Data)
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardKpi extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'period',
        'period_date',
        'total_operations',
        'completed_operations',
        'pending_operations',
        'cancelled_operations',
        'total_revenue',
        'total_cost',
        'net_profit',
        'section_specific_data',
        'custom_metrics',
    ];

    protected $casts = [
        'period_date' => 'date',
        'total_revenue' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'section_specific_data' => 'array',
        'custom_metrics' => 'array',
    ];

    // Scopes
    public function scopeForSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    public function scopeForPeriod($query, string $period)
    {
        return $query->where('period', $period);
    }

    public function scopeDaily($query)
    {
        return $query->where('period', 'daily');
    }

    public function scopeWeekly($query)
    {
        return $query->where('period', 'weekly');
    }

    public function scopeMonthly($query)
    {
        return $query->where('period', 'monthly');
    }

    public function scopeYearly($query)
    {
        return $query->where('period', 'yearly');
    }

    public function scopeLatest($query, string $section, string $period = 'daily')
    {
        return $query->forSection($section)
                     ->forPeriod($period)
                     ->orderBy('period_date', 'desc')
                     ->first();
    }

    // Methods
    public function getCompletionRateAttribute(): float
    {
        if ($this->total_operations == 0) {
            return 0;
        }
        
        return round(($this->completed_operations / $this->total_operations) * 100, 2);
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->total_revenue == 0) {
            return 0;
        }
        
        return round(($this->net_profit / $this->total_revenue) * 100, 2);
    }
}
