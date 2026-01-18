<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'مصر', 'iso2' => 'EG'],
            ['name' => 'السعودية', 'iso2' => 'SA'],
            ['name' => 'الإمارات', 'iso2' => 'AE'],
            ['name' => 'الولايات المتحدة', 'iso2' => 'US'],
            ['name' => 'الصين', 'iso2' => 'CN'],
            ['name' => 'تركيا', 'iso2' => 'TR'],
            ['name' => 'الهند', 'iso2' => 'IN'],
            ['name' => 'ألمانيا', 'iso2' => 'DE'],
            ['name' => 'المملكة المتحدة', 'iso2' => 'GB'],
            ['name' => 'فرنسا', 'iso2' => 'FR'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['iso2' => $country['iso2']],
                $country
            );
        }
    }
}
