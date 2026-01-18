<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AdsWidgetService
{
    public function cards(?string $specialty = null): array
    {
        // 1) أسعار الحاويات - الحصول على مورد حاويات/شحن
        $containers = DB::table('suppliers')->where('status', 'approved')
            ->where(function($q) {
                $q->where('main_products', 'LIKE', '%حاوية%')
                  ->orWhere('main_products', 'LIKE', '%container%')
                  ->orWhere('main_products', 'LIKE', '%شحن%')
                  ->orWhere('introduction', 'LIKE', '%حاوية%');
            })
            ->orderByDesc('created_at')->orderBy('company_name')->limit(1)->first();

        $containersCard = [
            'title' => 'أسعار الحاويات',
            'icon'  => 'truck',
            'lines' => [
                'حاوية 20 قدم: 1,200$ – 1,800$',
                'حاوية 40 قدم: 2,000$ – 3,200$',
            ],
            'cta'   => ['text' => 'طلب عرض سعر', 'url' => url('/quotes/containers')],
            'color' => 'indigo',
            'supplier' => $containers ? $containers->company_name : null,
        ];

        // 2) مستخلص جمركي - البحث عن موردين في الخدمات الجمركية
        $broker = DB::table('suppliers')->where('status', 'approved')
            ->where(function($q) {
                $q->where('main_products', 'LIKE', '%جمرك%')
                  ->orWhere('main_products', 'LIKE', '%customs%')
                  ->orWhere('introduction', 'LIKE', '%جمرك%')
                  ->orWhere('introduction', 'LIKE', '%مستخلص%');
            })
            ->orderByDesc('created_at')->limit(1)->first();

        $brokerCard = [
            'title' => 'مستخلص جمركي',
            'icon'  => 'clipboard-check',
            'lines' => [
                'خبرة +15 سنة ✓',
                'جميع المنافذ المصرية ✓',
                'أسعار تنافسية ✓',
            ],
            'cta'   => ['text' => 'اتصل بنا', 'url' => url('/contact/broker')],
            'color' => 'emerald',
            'supplier' => $broker ? $broker->company_name : null,
        ];

        // 3) وكيل شحن دولي - البحث عن موردين في الشحن الدولي
        $ff = DB::table('suppliers')->where('status', 'approved')
            ->where(function($q) {
                $q->where('main_products', 'LIKE', '%freight%')
                  ->orWhere('main_products', 'LIKE', '%وكيل%')
                  ->orWhere('main_products', 'LIKE', '%شحن%')
                  ->orWhere('introduction', 'LIKE', '%شحن دولي%');
            })
            ->orderByDesc('created_at')->limit(1)->first();

        $ffCard = [
            'title' => 'وكيل شحن دولي',
            'icon'  => 'globe',
            'lines' => [
                'شحن لجميع أنحاء العالم 🌍',
                'تأمين شامل 🛡️',
                'تتبع مباشر ⚡',
            ],
            'cta'   => ['text' => 'احصل على عرض', 'url' => url('/quotes/freight')],
            'color' => 'cyan',
            'supplier' => $ff ? $ff->company_name : null,
        ];

        // 4) نقل البضائع - البحث عن موردين في النقل المحلي
        $truck = DB::table('suppliers')
            ->select('company_name', 'main_products', 'introduction')
            ->where('status', 'approved')
            ->where(function($q) {
                $q->where('main_products', 'LIKE', '%نقل%')
                  ->orWhere('main_products', 'LIKE', '%truck%')
                  ->orWhere('main_products', 'LIKE', '%transport%')
                  ->orWhere('introduction', 'LIKE', '%نقل محلي%');
            })
            ->orderByDesc('created_at')->limit(1)->first();

        $truckCard = [
            'title' => 'نقل البضائع',
            'icon'  => 'truck-delivery',
            'lines' => [
                isset($truck->main_products) ? "تخصص: " . substr($truck->main_products, 0, 30) . "..." : 'أسطول حديث 🚚',
                'تغطية شاملة 🧭',
                isset($truck->introduction) ? "خدمة: " . substr($truck->introduction, 0, 30) . "..." : 'أسعار مناسبة 💰',
            ],
            'cta'   => ['text' => 'طلب خدمة', 'url' => url('/orders/trucking')],
            'color' => 'amber',
            'supplier' => $truck ? $truck->company_name : null,
        ];

        return [$containersCard, $brokerCard, $ffCard, $truckCard];
    }
}