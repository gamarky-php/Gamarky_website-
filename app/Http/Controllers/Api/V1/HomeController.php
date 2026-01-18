<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'title' => 'Gamarky',
            'sections' => ['import','export','manufacturing','clearance','containers','agents'],
        ]);
    }

    public function menus()
    {
        return response()->json([
            'import' => [
                ['label' => 'حاسبة الاستيراد', 'route' => 'front.import.calculator', 'api' => '/api/v1/import/calculator/prefill'],
                ['label' => 'الإجراءات', 'route' => 'front.import.procedures'],
                ['label' => 'التعريفة', 'route' => 'front.import.tariffs'],
                ['label' => 'تتبع الشحنة', 'route' => 'front.import.tracking'],
            ],
            'export' => [
                ['label' => 'حاسبة التصدير', 'route' => 'front.export.calculator'],
                ['label' => 'الإجراءات', 'route' => 'front.export.procedures'],
                ['label' => 'المتطلبات', 'route' => 'front.export.requirements'],
                ['label' => 'تتبع الشحنة', 'route' => 'front.export.tracking'],
            ],
            'manufacturing' => [
                ['label' => 'خطط التصنيع', 'route' => 'front.manufacturing.plans'],
                ['label' => 'الموردين', 'route' => 'front.manufacturing.suppliers'],
                ['label' => 'جودة المنتج', 'route' => 'front.manufacturing.quality'],
                ['label' => 'التكاليف', 'route' => 'front.manufacturing.costs'],
            ],
            'clearance' => [
                ['label' => 'التخليص الجمركي', 'route' => 'front.clearance.customs'],
                ['label' => 'الوثائق المطلوبة', 'route' => 'front.clearance.documents'],
                ['label' => 'الرسوم والضرائب', 'route' => 'front.clearance.fees'],
                ['label' => 'متابعة الطلبات', 'route' => 'front.clearance.tracking'],
            ],
            'containers' => [
                ['label' => 'أنواع الحاويات', 'route' => 'front.containers.types'],
                ['label' => 'حجز الحاويات', 'route' => 'front.containers.booking'],
                ['label' => 'تتبع الحاويات', 'route' => 'front.containers.tracking'],
                ['label' => 'الأسعار', 'route' => 'front.containers.pricing'],
            ],
            'agents' => [
                ['label' => 'شبكة الوكلاء', 'route' => 'front.agents.network'],
                ['label' => 'طلب وكيل', 'route' => 'front.agents.request'],
                ['label' => 'تقييم الوكلاء', 'route' => 'front.agents.reviews'],
                ['label' => 'الأسعار', 'route' => 'front.agents.pricing'],
            ],
        ]);
    }
}