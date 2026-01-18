<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'name' => 'TechVision',
                'logo_path' => null,
                'sectors' => ['إلكترونيات', 'تكنولوجيا'],
                'countries_needed' => ['السعودية', 'الإمارات', 'الكويت'],
                'description' => 'علامة تجارية رائدة في مجال الإلكترونيات والأجهزة الذكية'
            ],
            [
                'name' => 'FreshFood Co.',
                'logo_path' => null,
                'sectors' => ['أغذية ومشروبات'],
                'countries_needed' => ['مصر', 'الأردن', 'لبنان'],
                'description' => 'منتجات غذائية طبيعية وصحية من مصادر موثوقة'
            ],
            [
                'name' => 'FashionStyle',
                'logo_path' => null,
                'sectors' => ['أزياء وملابس', 'إكسسوارات'],
                'countries_needed' => ['الكويت', 'قطر', 'البحرين'],
                'description' => 'أزياء عصرية وأنيقة للرجال والنساء'
            ],
            [
                'name' => 'HomeComfort',
                'logo_path' => null,
                'sectors' => ['أثاث ومفروشات'],
                'countries_needed' => ['السعودية', 'الإمارات', 'البحرين'],
                'description' => 'أثاث منزلي فاخر وعصري'
            ],
            [
                'name' => 'BeautyGlow',
                'logo_path' => null,
                'sectors' => ['مستحضرات تجميل', 'عناية شخصية'],
                'countries_needed' => ['الإمارات', 'لبنان', 'الأردن'],
                'description' => 'مستحضرات تجميل طبيعية خالية من المواد الضارة'
            ],
            [
                'name' => 'SportsPro',
                'logo_path' => null,
                'sectors' => ['رياضة ولياقة'],
                'countries_needed' => ['عمان', 'السعودية', 'الكويت'],
                'description' => 'معدات رياضية احترافية للمحترفين والهواة'
            ],
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }
    }
}

