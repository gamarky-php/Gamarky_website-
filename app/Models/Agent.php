<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_name',
        'country',
        'city',
        'phone',
        'whatsapp',
        'email',
        'has_cargox',
        'has_einvoice',
        'warehouses',
        'avg_response_hours',
        'on_time_ratio',
        'doc_accuracy_ratio',
        'rating_auto',
        'rating_client',
        'badges',
        'services',
        'notes',
    ];

    protected $casts = [
        'has_cargox' => 'boolean',
        'has_einvoice' => 'boolean',
        'warehouses' => 'array',
        'badges' => 'array',
        'services' => 'array',
        'avg_response_hours' => 'integer',
        'on_time_ratio' => 'integer',
        'doc_accuracy_ratio' => 'integer',
        'rating_auto' => 'integer',
        'rating_client' => 'decimal:2',
    ];

    /**
     * العلاقات
     */
    public function operations(): HasMany
    {
        return $this->hasMany(AgentOperation::class);
    }

    /**
     * حساب السكور الإجمالي (متوسط مرجّح)
     * Formula: (on_time_ratio * 0.4) + (doc_accuracy_ratio * 0.4) + (rating_auto * 0.2)
     */
    public function getOverallScoreAttribute(): float
    {
        $onTimeWeight = 0.4;
        $docAccuracyWeight = 0.4;
        $ratingAutoWeight = 0.2;

        $score = ($this->on_time_ratio * $onTimeWeight) +
                 ($this->doc_accuracy_ratio * $docAccuracyWeight) +
                 ($this->rating_auto * $ratingAutoWeight);

        return round($score, 1);
    }

    /**
     * الحصول على عدد العمليات المكتملة
     */
    public function getCompletedOperationsCountAttribute(): int
    {
        return $this->operations()->whereNotNull('completed_at')->count();
    }

    /**
     * إجمالي النقاط المكتسبة
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->operations()->whereNotNull('completed_at')->sum('points');
    }

    /**
     * فحص إذا كان الوكيل لديه خدمة معينة
     */
    public function hasService(string $service): bool
    {
        return in_array($service, $this->services ?? []);
    }

    /**
     * فحص إذا كان لديه نوع مخزن معين
     */
    public function hasWarehouse(string $warehouse): bool
    {
        return in_array($warehouse, $this->warehouses ?? []);
    }

    /**
     * Scopes للبحث والفلترة
     */
    public function scopeSearch($query, ?string $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('country', 'like', "%{$search}%");
        });
    }

    public function scopeByCountry($query, ?string $country)
    {
        if (empty($country)) {
            return $query;
        }

        return $query->where('country', $country);
    }

    public function scopeHasCargox($query, ?bool $has)
    {
        if (is_null($has)) {
            return $query;
        }

        return $query->where('has_cargox', $has);
    }

    public function scopeHasEinvoice($query, ?bool $has)
    {
        if (is_null($has)) {
            return $query;
        }

        return $query->where('has_einvoice', $has);
    }

    public function scopeMinScore($query, ?int $score)
    {
        if (is_null($score)) {
            return $query;
        }

        // نحتاج لحساب السكور في الـ query
        // لكن بما أنه accessor، سنستخدم raw calculation
        return $query->whereRaw(
            '((on_time_ratio * 0.4) + (doc_accuracy_ratio * 0.4) + (rating_auto * 0.2)) >= ?',
            [$score]
        );
    }
}
