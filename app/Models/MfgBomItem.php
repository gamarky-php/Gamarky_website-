<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomItem extends Model
{
    protected $fillable = [
        'mfg_cost_run_id',
        'material',
        'uom',
        'qty_per_batch',
        'unit_price',
        'scrap_pct',
    ];

    protected $casts = [
        'qty_per_batch' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'scrap_pct' => 'decimal:2',
    ];

    public function costRun(): BelongsTo
    {
        return $this->belongsTo(MfgCostRun::class, 'mfg_cost_run_id');
    }

    public function getTotalCostAttribute(): float
    {
        return $this->qty_per_batch * $this->unit_price * (1 + $this->scrap_pct / 100);
    }
}

// Backward compatibility alias
class MfgBomItem extends BomItem {}
