<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('suppliers')->count() === 0) {
            DB::table('suppliers')->insert([
                [
                    'company_name' => 'Shanghai Parts Co',
                    'country_code' => 'CN',
                    'province' => 'Shanghai',
                    'city' => 'Shanghai',
                    'mobile_phone' => '+86-21-0000',
                    'tel' => '+86-21-0000-0000',
                    'website' => 'http://shparts.com',
                    'main_products' => 'Auto parts, Electronics',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'company_name' => 'Alex Import',
                    'country_code' => 'EG',
                    'province' => 'Alexandria',
                    'city' => 'Alexandria',
                    'mobile_phone' => '+20-3-0000',
                    'tel' => '+20-3-0000-0000',
                    'website' => 'http://aleximport.eg',
                    'main_products' => 'General import/export',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
