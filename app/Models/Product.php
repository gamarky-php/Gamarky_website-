<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'uom',
        'default_batch',
        'notes',
    ];

    protected $casts = [
        'default_batch' => 'integer',
    ];

    public function costRuns(): HasMany
    {
        return $this->hasMany(MfgCostRun::class);
    }
}
