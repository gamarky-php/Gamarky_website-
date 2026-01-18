<?php

namespace Database\Seeders;

use App\Models\ShippingType;
use Illuminate\Database\Seeder;

class ShippingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'بحري', 'code' => 'sea'],
            ['name' => 'جوي', 'code' => 'air'],
            ['name' => 'بري', 'code' => 'land'],
        ];

        foreach ($types as $type) {
            ShippingType::firstOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
