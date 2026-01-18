<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShippingTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'description' => $this->description,
            'mode' => $this->mode, // sea, air, land
            'mode_name' => $this->getModeName(),
            'icon' => $this->icon,
            'estimated_delivery_days' => $this->estimated_delivery_days,
            'price_multiplier' => $this->price_multiplier ?? 1.0,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            
            // المزايا والقيود
            'features' => $this->features ? json_decode($this->features, true) : [],
            'restrictions' => $this->restrictions ? json_decode($this->restrictions, true) : [],
            'requirements' => $this->requirements ? json_decode($this->requirements, true) : [],
            
            // معلومات إضافية للتطبيق المحمول
            'mobile_info' => [
                'budget_category' => $this->getBudgetCategory(),
                'speed_category' => $this->getSpeedCategory(),
                'recommended' => $this->isRecommended(),
                'pros' => $this->getPros(),
                'cons' => $this->getCons(),
                'best_for' => $this->getBestFor(),
            ],
            
            // معلومات التسعير
            'pricing_info' => [
                'base_cost_impact' => $this->getBaseCostImpact(),
                'additional_fees' => $this->getAdditionalFees(),
                'insurance_required' => $this->insurance_required ?? false,
            ],
            
            // معلومات التتبع
            'tracking_info' => [
                'tracking_available' => $this->tracking_available ?? true,
                'real_time_updates' => $this->real_time_updates ?? false,
                'milestone_notifications' => $this->milestone_notifications ?? true,
            ],
            
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * الحصول على اسم نوع النقل
     */
    private function getModeName()
    {
        $names = [
            'sea' => 'بحري',
            'air' => 'جوي', 
            'land' => 'بري'
        ];

        return $names[$this->mode] ?? $this->mode;
    }

    /**
     * تصنيف الميزانية
     */
    private function getBudgetCategory()
    {
        $multiplier = $this->price_multiplier ?? 1.0;
        
        if ($multiplier <= 1.2) return 'اقتصادي';
        if ($multiplier >= 1.5) return 'مرتفع';
        return 'متوسط';
    }

    /**
     * تصنيف السرعة
     */
    private function getSpeedCategory()
    {
        $days = $this->estimated_delivery_days ?? 14;
        
        if ($days <= 3) return 'عاجل';
        if ($days <= 7) return 'سريع';
        if ($days <= 14) return 'عادي';
        return 'بطيء';
    }

    /**
     * هل هذا النوع موصى به؟
     */
    private function isRecommended()
    {
        $multiplier = $this->price_multiplier ?? 1.0;
        $days = $this->estimated_delivery_days ?? 14;
        
        return $multiplier <= 1.3 && $days <= 14;
    }

    /**
     * المزايا
     */
    private function getPros()
    {
        $commonPros = [
            'sea' => ['تكلفة أقل', 'مناسب للشحنات الكبيرة', 'صديق للبيئة'],
            'air' => ['سرعة عالية', 'أمان أكبر', 'تتبع دقيق'],
            'land' => ['مرونة في التوقيت', 'تكلفة معقولة', 'سهولة الوصول']
        ];

        return $commonPros[$this->mode] ?? [];
    }

    /**
     * العيوب
     */
    private function getCons()
    {
        $commonCons = [
            'sea' => ['وقت أطول', 'تأثر بالطقس', 'قيود الموانئ'],
            'air' => ['تكلفة أعلى', 'قيود الحجم والوزن', 'تأثر بأحوال الطيران'],
            'land' => ['محدود جغرافياً', 'تأثر بالطرق', 'أمان أقل من الجوي']
        ];

        return $commonCons[$this->mode] ?? [];
    }

    /**
     * الأنسب لـ
     */
    private function getBestFor()
    {
        $bestFor = [
            'sea' => ['البضائع الكبيرة', 'الشحنات غير العاجلة', 'المواد الخام'],
            'air' => ['البضائع القيمة', 'الشحنات العاجلة', 'المنتجات الحساسة'],
            'land' => ['المسافات القصيرة', 'التوصيل المحلي', 'البضائع المتوسطة']
        ];

        return $bestFor[$this->mode] ?? [];
    }

    /**
     * تأثير التكلفة الأساسية
     */
    private function getBaseCostImpact()
    {
        $multiplier = $this->price_multiplier ?? 1.0;
        
        if ($multiplier <= 1.1) return 'لا يوجد تأثير';
        if ($multiplier <= 1.3) return 'زيادة طفيفة';
        if ($multiplier <= 1.5) return 'زيادة متوسطة';
        return 'زيادة كبيرة';
    }

    /**
     * الرسوم الإضافية
     */
    private function getAdditionalFees()
    {
        // يمكن تخصيص هذا حسب نوع الشحن
        $fees = [];
        
        if ($this->mode === 'air') {
            $fees[] = 'رسوم الوقود';
            $fees[] = 'رسوم الأمان';
        }
        
        if ($this->mode === 'sea') {
            $fees[] = 'رسوم الميناء';
            $fees[] = 'رسوم التخليص';
        }
        
        if ($this->mode === 'land') {
            $fees[] = 'رسوم الطرق';
            $fees[] = 'رسوم التخليص الحدودي';
        }
        
        return $fees;
    }
}