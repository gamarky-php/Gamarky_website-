<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OverheadPool extends Model
{
    protected $fillable = [
        'mfg_cost_run_id',
        'name',
        'basis',
        'rate',
    ];

    protected $casts = [
        'rate' => 'decimal:4',
    ];

    public function costRun(): BelongsTo
    {
        return $this->belongsTo(MfgCostRun::class, 'mfg_cost_run_id');
    }

    public function calculateCost(MfgCostRun $run): float
    {
        switch ($this->basis) {
            case 'machine_hour':
                $hours = $run->ops->sum('machine_hours');

                return $hours * $this->rate;

            case 'labor_hour':
                $hours = $run->ops->sum('labor_hours');

                return $hours * $this->rate;

            case 'material_pct':
                $materialCost = $run->bomItems->sum('total_cost');

                return $materialCost * ($this->rate / 100);

            default:
                return 0;
        }
    }
}

// Backward compatibility alias
class MfgOverhead extends OverheadPool {}
