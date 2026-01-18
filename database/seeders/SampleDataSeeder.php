<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $adminId = DB::table('users')->where('email', 'admin@gamarky.com')->value('id') ?? 1;
        
        // Sample Cost Calculations
        for ($i = 1; $i <= 3; $i++) {
            DB::table('cost_calculations')->insert([
                'ref_code' => "IMP-CC-2025-0000{$i}",
                'module' => 'import',
                'user_id' => $adminId,
                'title' => "حساب استيراد تجريبي {$i}",
                'currency' => 'USD',
                'inputs' => json_encode([
                    'purchase_price' => 10000 + ($i * 2000),
                    'incoterm' => 'FOB',
                    'origin_country' => 'CN',
                    'destination_port' => 'EG-ALY',
                    'container_type' => '40HC',
                ]),
                'items' => json_encode([
                    ['key' => 'international_freight', 'value' => 2000],
                    ['key' => 'insurance', 'value' => 150],
                    ['key' => 'customs_duties', 'value' => 1500],
                ]),
                'margin_percent' => 15,
                'totals' => json_encode([
                    'cogs' => 13650 + ($i * 2000),
                    'margin_value' => 2047.5 + ($i * 300),
                    'sell_price' => 15697.5 + ($i * 2300),
                ]),
                'metadata' => json_encode([
                    'kpis' => [
                        'duty_ratio' => 15.0,
                        'logistics_share' => 15.8,
                        'lead_time_days' => 26,
                    ],
                ]),
                'final_total' => 15697.5 + ($i * 2300),
                'saved_as' => 'scenario',
                'status' => 'draft',
                'created_at' => $now->copy()->subDays($i),
                'updated_at' => $now->copy()->subDays($i),
            ]);
        }
        
        $this->command->info('✓ Created 3 cost calculations');
    }
}
