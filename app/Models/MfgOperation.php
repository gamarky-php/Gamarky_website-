<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoutingOp extends Model
{
    protected $fillable = [
        'mfg_cost_run_id',
        'op_seq',
        'operation',
        'setup_time_hr',
        'run_time_hr',
        'labor_rate',
        'machine_rate',
    ];

    protected $casts = [
        'op_seq' => 'integer',
        'setup_time_hr' => 'decimal:3',
        'run_time_hr' => 'decimal:3',
        'labor_rate' => 'decimal:2',
        'machine_rate' => 'decimal:2',
    ];

    public function costRun(): BelongsTo
    {
        return $this->belongsTo(MfgCostRun::class, 'mfg_cost_run_id');
    }

    public function getTotalCostAttribute(): float
    {
        $setupCost = $this->setup_time_hr * ($this->labor_rate + $this->machine_rate);
        $runCost = $this->run_time_hr * ($this->labor_rate + $this->machine_rate);

        return $setupCost + $runCost;
    }

    public function getMachineHoursAttribute(): float
    {
        return $this->setup_time_hr + $this->run_time_hr;
    }

    public function getLaborHoursAttribute(): float
    {
        return $this->setup_time_hr + $this->run_time_hr;
    }
}

// Backward compatibility alias
class MfgOperation extends RoutingOp {}
