<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Port extends Model
{
    protected $fillable = ['country_id', 'name', 'code', 'mode'];

    /**
     * Get the country that owns this port
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
