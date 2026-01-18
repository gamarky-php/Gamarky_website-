<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name', 'iso2'];

    /**
     * Get all ports for this country
     */
    public function ports(): HasMany
    {
        return $this->hasMany(Port::class);
    }
}
