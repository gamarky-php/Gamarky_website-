<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ShippingType;
use Illuminate\Http\Request;

class ShippingTypeController extends Controller
{
    /**
     * الحصول على أنواع الشحن للاختيار (Select2 format)
     */
    public function select2(Request $request)
    {
        $search = $request->get('q', '');
        $mode = $request->get('mode'); // sea, air, land
        $limit = $request->get('limit', 50);

        $query = ShippingType::select('id', 'name', 'name_en', 'name_ar', 'mode', 'icon', 'description')
            ->where('is_active', true);

        // فلترة حسب نوع النقل
        if (!empty($mode)) {
            $query->where('mode', $mode);
        }

        // البحث في الاسم
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('name_en', 'LIKE', "%{$search}%")
                  ->orWhere('name_ar', 'LIKE', "%{$search}%");
            });
        }

        $shippingTypes = $query->orderBy('sort_order')
            ->orderBy('name')
            ->limit($limit)
            ->get();

        $results = $shippingTypes->map(function ($type) {
            return [
                'id' => $type->id,
                'text' => $type->name,
                'name' => $type->name,
                'name_en' => $type->name_en,
                'name_ar' => $type->name_ar,
                'mode' => $type->mode,
                'icon' => $type->icon,
                'description' => $type->description,
                'estimated_days' => $type->estimated_delivery_days,
                'price_multiplier' => $type->price_multiplier ?? 1.0,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'results' => $results,
                'pagination' => [
                    'more' => $shippingTypes->count() >= $limit
                ]
            ]
        ]);
    }

    /**
     * الحصول على جميع أنواع الشحن مع التصنيف
     */
    public function index(Request $request)
    {
        $shippingTypes = ShippingType::where('is_active', true)
            ->orderBy('mode')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $groupedTypes = $shippingTypes->groupBy('mode')->map(function ($types, $mode) {
            return [
                'mode' => $mode,
                'mode_name' => $this->getModeName($mode),
                'types' => $types->map(function ($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'name_en' => $type->name_en,
                        'name_ar' => $type->name_ar,
                        'description' => $type->description,
                        'icon' => $type->icon,
                        'estimated_days' => $type->estimated_delivery_days,
                        'price_multiplier' => $type->price_multiplier ?? 1.0,
                        'features' => $type->features ? json_decode($type->features, true) : [],
                    ];
                })->values()
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => [
                'shipping_types' => $groupedTypes
            ]
        ]);
    }

    /**
     * الحصول على تفاصيل نوع شحن واحد
     */
    public function show(Request $request, $id)
    {
        $shippingType = ShippingType::where('is_active', true)->find($id);

        if (!$shippingType) {
            return response()->json([
                'success' => false,
                'message' => 'نوع الشحن غير موجود'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'shipping_type' => [
                    'id' => $shippingType->id,
                    'name' => $shippingType->name,
                    'name_en' => $shippingType->name_en,
                    'name_ar' => $shippingType->name_ar,
                    'description' => $shippingType->description,
                    'mode' => $shippingType->mode,
                    'mode_name' => $this->getModeName($shippingType->mode),
                    'icon' => $shippingType->icon,
                    'estimated_days' => $shippingType->estimated_delivery_days,
                    'price_multiplier' => $shippingType->price_multiplier ?? 1.0,
                    'features' => $shippingType->features ? json_decode($shippingType->features, true) : [],
                    'restrictions' => $shippingType->restrictions ? json_decode($shippingType->restrictions, true) : [],
                    'requirements' => $shippingType->requirements ? json_decode($shippingType->requirements, true) : [],
                ]
            ]
        ]);
    }

    /**
     * مقارنة أنواع الشحن
     */
    public function compare(Request $request)
    {
        $ids = $request->get('ids', []);
        
        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى اختيار أنواع الشحن للمقارنة'
            ], 400);
        }

        $shippingTypes = ShippingType::whereIn('id', $ids)
            ->where('is_active', true)
            ->get();

        $comparison = $shippingTypes->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'mode' => $type->mode,
                'mode_name' => $this->getModeName($type->mode),
                'estimated_days' => $type->estimated_delivery_days,
                'price_multiplier' => $type->price_multiplier ?? 1.0,
                'features' => $type->features ? json_decode($type->features, true) : [],
                'pros' => $this->getTypePros($type),
                'cons' => $this->getTypeCons($type),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'comparison' => $comparison,
                'comparison_points' => [
                    'speed' => 'السرعة',
                    'cost' => 'التكلفة',
                    'security' => 'الأمان',
                    'tracking' => 'التتبع',
                    'insurance' => 'التأمين',
                ]
            ]
        ]);
    }

    /**
     * الحصول على أنواع الشحن حسب الميزانية
     */
    public function byBudget(Request $request)
    {
        $budget = $request->get('budget', 'medium'); // low, medium, high
        $mode = $request->get('mode');

        $query = ShippingType::where('is_active', true);

        if (!empty($mode)) {
            $query->where('mode', $mode);
        }

        // فلترة حسب الميزانية
        switch ($budget) {
            case 'low':
                $query->where('price_multiplier', '<=', 1.2);
                break;
            case 'high':
                $query->where('price_multiplier', '>=', 1.5);
                break;
            default: // medium
                $query->whereBetween('price_multiplier', [1.0, 1.5]);
                break;
        }

        $shippingTypes = $query->orderBy('price_multiplier')
            ->orderBy('estimated_delivery_days')
            ->get();

        $results = $shippingTypes->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'mode' => $type->mode,
                'estimated_days' => $type->estimated_delivery_days,
                'price_multiplier' => $type->price_multiplier ?? 1.0,
                'budget_category' => $this->getBudgetCategory($type->price_multiplier ?? 1.0),
                'recommended' => $this->isRecommended($type),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'shipping_types' => $results,
                'budget' => $budget,
                'budget_ranges' => [
                    'low' => 'ميزانية محدودة (حتى 20% إضافية)',
                    'medium' => 'ميزانية متوسطة (20-50% إضافية)',
                    'high' => 'ميزانية مرتفعة (أكثر من 50% إضافية)'
                ]
            ]
        ]);
    }

    /**
     * الحصول على اسم نوع النقل
     */
    private function getModeName($mode)
    {
        $names = [
            'sea' => 'بحري',
            'air' => 'جوي',
            'land' => 'بري'
        ];

        return $names[$mode] ?? $mode;
    }

    /**
     * الحصول على مميزات نوع الشحن
     */
    private function getTypePros($type)
    {
        $commonPros = [
            'sea' => ['تكلفة أقل', 'مناسب للشحنات الكبيرة', 'صديق للبيئة'],
            'air' => ['سرعة عالية', 'أمان أكبر', 'تتبع دقيق'],
            'land' => ['مرونة في التوقيت', 'تكلفة معقولة', 'سهولة الوصول']
        ];

        return $commonPros[$type->mode] ?? [];
    }

    /**
     * الحصول على عيوب نوع الشحن
     */
    private function getTypeCons($type)
    {
        $commonCons = [
            'sea' => ['وقت أطول', 'تأثر بالطقس', 'قيود الموانئ'],
            'air' => ['تكلفة أعلى', 'قيود الحجم والوزن', 'تأثر بأحوال الطيران'],
            'land' => ['محدود جغرافياً', 'تأثر بالطرق', 'أمان أقل من الجوي']
        ];

        return $commonCons[$type->mode] ?? [];
    }

    /**
     * تحديد فئة الميزانية
     */
    private function getBudgetCategory($multiplier)
    {
        if ($multiplier <= 1.2) return 'low';
        if ($multiplier >= 1.5) return 'high';
        return 'medium';
    }

    /**
     * تحديد ما إذا كان النوع موصى به
     */
    private function isRecommended($type)
    {
        // يمكن إضافة منطق أكثر تعقيداً هنا
        return $type->price_multiplier >= 1.0 && $type->price_multiplier <= 1.3 && 
               $type->estimated_delivery_days <= 14;
    }
}