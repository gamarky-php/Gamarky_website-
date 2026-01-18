<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Ad;

class AdsSeeder extends Seeder
{
    public function run(): void
    {
        $specialty = 'freight';

        // إنشاء 3 موردين معتمدين إن لم يكونوا موجودين
        $suppliers = [];
        for ($i = 1; $i <= 3; $i++) {
            $supplier = Supplier::firstOrCreate(
                ['company_name' => "Freight Company {$i}"],
                [
                    'company_name' => "Freight Company {$i}",
                    'province' => 'Baghdad',
                    'city' => 'Baghdad',
                    'contact_person' => "Contact Person {$i}",
                    'status' => 'approved',
                    'country_code' => 'IQ',
                    'source' => 'seeder',
                ]
            );
            $suppliers[] = $supplier;
        }

        // إنشاء 3 إعلانات
        foreach ($suppliers as $index => $supplier) {
            Ad::firstOrCreate(
                ['supplier_id' => $supplier->id],
                [
                    'supplier_id' => $supplier->id,
                    'title' => "إعلان شركة {$supplier->company_name}",
                    'image_path' => "https://picsum.photos/600/300?random=" . ($index + 100),
                    'link_url' => "https://example.com/supplier/{$supplier->id}",
                    'is_active' => true,
                    'starts_at' => now()->subDays(1),
                    'ends_at' => now()->addDays(30),
                    'impressions' => rand(100, 1000),
                    'clicks' => rand(10, 100),
                    'priority' => rand(0, 5),
                ]
            );
        }
    }
}

