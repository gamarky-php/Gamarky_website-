<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'sectors',
        'countries_needed',
        'description',
    ];

    protected $casts = [
        'sectors' => 'array',
        'countries_needed' => 'array',
    ];

    /**
     * فحص إذا كانت العلامة تحتاج وكيل في بلد معين
     */
    public function needsAgentInCountry(string $country): bool
    {
        $countries = $this->countries_needed ?? [];
        return in_array($country, $countries);
    }

    /**
     * فحص إذا كانت العلامة في قطاع معين
     */
    public function isInSector(string $sector): bool
    {
        $sectors = $this->sectors ?? [];
        return in_array($sector, $sectors);
    }

    /**
     * Scopes
     */
    public function scopeBySector($query, ?string $sector)
    {
        if (empty($sector)) {
            return $query;
        }

        return $query->whereJsonContains('sectors', $sector);
    }

    public function scopeNeedsCountry($query, ?string $country)
    {
        if (empty($country)) {
            return $query;
        }

        return $query->whereJsonContains('countries_needed', $country);
    }
}
