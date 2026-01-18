<?php

namespace Database\Seeders;

use App\Models\Agent;
use Illuminate\Database\Seeder;

class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            [
                'company_name' => 'الشحن الدولي المتقدم',
                'contact_name' => 'أحمد محمد',
                'country' => 'السعودية',
                'city' => 'جدة',
                'phone' => '+966501234567',
                'whatsapp' => '+966501234567',
                'email' => 'info@advancedshipping.sa',
                'has_cargox' => true,
                'has_einvoice' => true,
                'warehouses' => ['خاصة', 'عامة', 'مبردة'],
                'avg_response_hours' => 12,
                'on_time_ratio' => 95,
                'doc_accuracy_ratio' => 98,
                'rating_auto' => 90,
                'rating_client' => 4.8,
                'badges' => ['موثوق', 'ذهبي', 'سريع'],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات', 'CargoX', 'فاتورة'],
                'notes' => 'وكيل متميز مع خبرة 15 عام في مجال الشحن الدولي'
            ],
            [
                'company_name' => 'لوجستيات الخليج',
                'contact_name' => 'فاطمة أحمد',
                'country' => 'الإمارات',
                'city' => 'دبي',
                'phone' => '+971501234567',
                'whatsapp' => '+971501234567',
                'email' => 'contact@gulflogistics.ae',
                'has_cargox' => true,
                'has_einvoice' => false,
                'warehouses' => ['عامة', 'جافة'],
                'avg_response_hours' => 24,
                'on_time_ratio' => 88,
                'doc_accuracy_ratio' => 92,
                'rating_auto' => 85,
                'rating_client' => 4.3,
                'badges' => ['موثوق'],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات', 'CargoX'],
                'notes' => 'متخصصون في الشحن إلى دول الخليج'
            ],
            [
                'company_name' => 'فريت ماستر',
                'contact_name' => 'محمد علي',
                'country' => 'مصر',
                'city' => 'الإسكندرية',
                'phone' => '+201012345678',
                'whatsapp' => '+201012345678',
                'email' => 'info@freightmaster.eg',
                'has_cargox' => false,
                'has_einvoice' => true,
                'warehouses' => ['خاصة', 'عامة'],
                'avg_response_hours' => 18,
                'on_time_ratio' => 90,
                'doc_accuracy_ratio' => 94,
                'rating_auto' => 88,
                'rating_client' => 4.5,
                'badges' => ['موثوق', 'اقتصادي'],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات', 'فاتورة'],
                'notes' => 'أسعار تنافسية مع خدمة ممتازة'
            ],
            [
                'company_name' => 'كارجو برو',
                'contact_name' => 'سارة خالد',
                'country' => 'الأردن',
                'city' => 'عمان',
                'phone' => '+962791234567',
                'whatsapp' => '+962791234567',
                'email' => 'info@cargopro.jo',
                'has_cargox' => true,
                'has_einvoice' => true,
                'warehouses' => ['خاصة', 'مبردة'],
                'avg_response_hours' => 15,
                'on_time_ratio' => 93,
                'doc_accuracy_ratio' => 96,
                'rating_auto' => 92,
                'rating_client' => 4.7,
                'badges' => ['موثوق', 'ذهبي'],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات', 'CargoX', 'فاتورة'],
                'notes' => 'متخصصون في الشحن البري والبحري'
            ],
            [
                'company_name' => 'الشحن السريع العالمي',
                'contact_name' => 'عبدالله حسن',
                'country' => 'الكويت',
                'city' => 'الكويت',
                'phone' => '+96550123456',
                'whatsapp' => '+96550123456',
                'email' => 'info@fastglobalshipping.kw',
                'has_cargox' => false,
                'has_einvoice' => false,
                'warehouses' => ['عامة', 'جافة'],
                'avg_response_hours' => 30,
                'on_time_ratio' => 85,
                'doc_accuracy_ratio' => 90,
                'rating_auto' => 82,
                'rating_client' => 4.0,
                'badges' => [],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات'],
                'notes' => 'خدمات شحن سريعة'
            ],
            [
                'company_name' => 'شركة النقل المتكامل',
                'contact_name' => 'ليلى محمود',
                'country' => 'قطر',
                'city' => 'الدوحة',
                'phone' => '+97430123456',
                'whatsapp' => '+97430123456',
                'email' => 'contact@completetransport.qa',
                'has_cargox' => true,
                'has_einvoice' => true,
                'warehouses' => ['خاصة', 'عامة', 'مبردة', 'جافة'],
                'avg_response_hours' => 10,
                'on_time_ratio' => 97,
                'doc_accuracy_ratio' => 99,
                'rating_auto' => 95,
                'rating_client' => 4.9,
                'badges' => ['موثوق', 'ذهبي', 'سريع', 'متميز'],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات', 'CargoX', 'فاتورة'],
                'notes' => 'الخيار الأمثل لخدمات الشحن المتكاملة'
            ],
            [
                'company_name' => 'الشحن الاقتصادي',
                'contact_name' => 'يوسف إبراهيم',
                'country' => 'البحرين',
                'city' => 'المنامة',
                'phone' => '+97333123456',
                'whatsapp' => '+97333123456',
                'email' => 'info@economicshipping.bh',
                'has_cargox' => false,
                'has_einvoice' => true,
                'warehouses' => ['عامة'],
                'avg_response_hours' => 36,
                'on_time_ratio' => 80,
                'doc_accuracy_ratio' => 88,
                'rating_auto' => 78,
                'rating_client' => 3.8,
                'badges' => ['اقتصادي'],
                'services' => ['تجميع', 'تحميل', 'مستندات'],
                'notes' => 'أسعار منخفضة للشحنات الصغيرة'
            ],
            [
                'company_name' => 'لوجستيات الشرق',
                'contact_name' => 'منى عبدالله',
                'country' => 'عُمان',
                'city' => 'مسقط',
                'phone' => '+96891234567',
                'whatsapp' => '+96891234567',
                'email' => 'info@eastlogistics.om',
                'has_cargox' => true,
                'has_einvoice' => false,
                'warehouses' => ['خاصة', 'عامة'],
                'avg_response_hours' => 20,
                'on_time_ratio' => 91,
                'doc_accuracy_ratio' => 93,
                'rating_auto' => 87,
                'rating_client' => 4.4,
                'badges' => ['موثوق'],
                'services' => ['تجميع', 'تخزين', 'تحميل', 'مستندات', 'CargoX'],
                'notes' => 'خبرة واسعة في الشحن إلى آسيا'
            ],
        ];

        foreach ($agents as $agentData) {
            Agent::create($agentData);
        }
    }
}

