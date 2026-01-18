<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MfgQuote extends Model
{
    protected $fillable = [
        'quote_number',
        'mfg_cost_run_id',
        'client_id',
        'client_name',
        'client_email',
        'client_phone',
        'unit_cost',
        'margin_pct',
        'unit_price',
        'qty',
        'total_amount',
        'currency',
        'valid_until',
        'notes',
        'status',
        'meta',
        'created_by',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'margin_pct' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'qty' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'valid_until' => 'date',
        'meta' => 'array',
    ];

    public function costRun(): BelongsTo
    {
        return $this->belongsTo(MfgCostRun::class, 'mfg_cost_run_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->quote_number)) {
                $model->quote_number = 'MFQ-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));
            }
        });
    }
}
