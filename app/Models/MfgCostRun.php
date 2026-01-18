<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MfgCostRun extends Model
{
    protected $fillable = [
        'product_id',
        'batch_size',
        'scrap_pct',
        'currency',
        'fx_rate',
        'total_cost',
        'unit_cost',
        'margin_pct',
        'target_price',
        'snapshot_json',
        'status',
        'created_by',
    ];

    protected $casts = [
        'batch_size' => 'integer',
        'scrap_pct' => 'decimal:2',
        'fx_rate' => 'decimal:6',
        'total_cost' => 'decimal:2',
        'unit_cost' => 'decimal:4',
        'margin_pct' => 'decimal:2',
        'target_price' => 'decimal:2',
        'snapshot_json' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bomItems(): HasMany
    {
        return $this->hasMany(BomItem::class);
    }

    public function ops(): HasMany
    {
        return $this->hasMany(RoutingOp::class);
    }

    public function overheads(): HasMany
    {
        return $this->hasMany(OverheadPool::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(MfgQuote::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
