<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CostCalculationResource extends JsonResource
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
            'reference_number' => $this->reference_number,
            'title' => $this->title ?? $this->generateTitle(),
            'status' => $this->status ?? 'draft',
            
            // معلومات الشحنة الأساسية
            'shipment_info' => [
                'weight' => $this->weight,
                'volume' => $this->volume,
                'dimensions' => $this->dimensions ? json_decode($this->dimensions, true) : null,
                'cargo_type' => $this->cargo_type,
                'cargo_description' => $this->cargo_description,
                'declared_value' => $this->declared_value,
                'currency' => $this->currency ?? 'USD',
            ],
            
            // معلومات المسار
            'route_info' => [
                'origin_port' => $this->whenLoaded('originPort', function () {
                    return new PortResource($this->originPort);
                }),
                'destination_port' => $this->whenLoaded('destinationPort', function () {
                    return new PortResource($this->destinationPort);
                }),
                'shipping_type' => $this->whenLoaded('shippingType', function () {
                    return new ShippingTypeResource($this->shippingType);
                }),
                'estimated_distance' => $this->estimated_distance,
                'estimated_transit_time' => $this->estimated_transit_time,
            ],
            
            // تفاصيل التكلفة
            'cost_breakdown' => [
                'base_freight' => $this->base_freight,
                'fuel_surcharge' => $this->fuel_surcharge,
                'security_fee' => $this->security_fee,
                'documentation_fee' => $this->documentation_fee,
                'handling_fee' => $this->handling_fee,
                'insurance_fee' => $this->insurance_fee,
                'customs_fee' => $this->customs_fee,
                'port_charges' => $this->port_charges,
                'other_charges' => $this->other_charges ? json_decode($this->other_charges, true) : [],
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax_amount,
                'tax_rate' => $this->tax_rate,
                'total_cost' => $this->total_cost,
                'currency' => $this->currency ?? 'USD',
            ],
            
            // معلومات إضافية للتطبيق المحمول
            'mobile_info' => [
                'formatted_total' => $this->getFormattedTotal(),
                'cost_per_kg' => $this->getCostPerKg(),
                'cost_per_cbm' => $this->getCostPerCbm(),
                'savings_compared_to_air' => $this->getSavingsComparedToAir(),
                'is_economical' => $this->isEconomical(),
                'quick_summary' => $this->getQuickSummary(),
            ],
            
            // خيارات الدفع والتأمين
            'payment_options' => [
                'payment_terms' => $this->payment_terms,
                'insurance_required' => $this->insurance_required ?? false,
                'insurance_percentage' => $this->insurance_percentage,
                'advance_payment_required' => $this->advance_payment_required ?? false,
                'advance_payment_percentage' => $this->advance_payment_percentage,
            ],
            
            // معلومات المستخدم والحفظ
            'user_info' => [
                'created_by' => $this->user->name ?? null,
                'is_saved' => !is_null($this->saved_at),
                'saved_at' => $this->saved_at?->toISOString(),
                'is_shared' => $this->is_shared ?? false,
                'share_token' => $this->when($this->is_shared, $this->share_token),
            ],
            
            // إجراءات سريعة
            'quick_actions' => [
                'can_save' => is_null($this->saved_at),
                'can_share' => true,
                'can_export_pdf' => true,
                'can_export_excel' => true,
                'can_book' => $this->status === 'calculated',
                'can_modify' => true,
            ],
            
            // التواريخ المهمة
            'timestamps' => [
                'calculated_at' => $this->created_at?->toISOString(),
                'last_modified' => $this->updated_at?->toISOString(),
                'expires_at' => $this->expires_at?->toISOString(),
                'valid_until' => $this->getValidUntil(),
            ],
        ];
    }

    /**
     * إنشاء عنوان للحساب
     */
    private function generateTitle()
    {
        $origin = $this->originPort->city ?? $this->originPort->name ?? 'غير محدد';
        $destination = $this->destinationPort->city ?? $this->destinationPort->name ?? 'غير محدد';
        
        return "شحنة من {$origin} إلى {$destination}";
    }

    /**
     * الحصول على التكلفة الإجمالية منسقة
     */
    private function getFormattedTotal()
    {
        $currency = $this->currency ?? 'USD';
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'SAR' => 'ر.س',
            'AED' => 'د.إ',
        ];
        
        $symbol = $currencySymbols[$currency] ?? $currency;
        
        return $symbol . ' ' . number_format($this->total_cost, 2);
    }

    /**
     * حساب التكلفة لكل كيلوغرام
     */
    private function getCostPerKg()
    {
        if (!$this->weight || $this->weight <= 0) {
            return null;
        }
        
        return round($this->total_cost / $this->weight, 2);
    }

    /**
     * حساب التكلفة لكل متر مكعب
     */
    private function getCostPerCbm()
    {
        if (!$this->volume || $this->volume <= 0) {
            return null;
        }
        
        return round($this->total_cost / $this->volume, 2);
    }

    /**
     * حساب الوفر مقارنة بالشحن الجوي
     */
    private function getSavingsComparedToAir()
    {
        // هذا مثال - يمكن تحسينه بحساب فعلي
        if ($this->shippingType && $this->shippingType->mode === 'air') {
            return null;
        }
        
        $estimatedAirCost = $this->total_cost * 2.5; // تقدير
        $savings = $estimatedAirCost - $this->total_cost;
        
        return [
            'amount' => round($savings, 2),
            'percentage' => round(($savings / $estimatedAirCost) * 100, 1),
            'formatted' => $this->currency . ' ' . number_format($savings, 2),
        ];
    }

    /**
     * هل الحساب اقتصادي؟
     */
    private function isEconomical()
    {
        $costPerKg = $this->getCostPerKg();
        
        if (!$costPerKg) {
            return null;
        }
        
        // معايير اقتصادية (يمكن تخصيصها)
        return $costPerKg <= 5; // أقل من 5 دولار للكيلو
    }

    /**
     * ملخص سريع للحساب
     */
    private function getQuickSummary()
    {
        $summary = [
            'route' => $this->generateTitle(),
            'total_cost' => $this->getFormattedTotal(),
            'transit_time' => $this->estimated_transit_time . ' أيام',
            'shipping_mode' => $this->shippingType->getModeName() ?? 'غير محدد',
        ];
        
        if ($this->getCostPerKg()) {
            $summary['cost_per_kg'] = $this->getCostPerKg() . ' ' . ($this->currency ?? 'USD') . '/كغ';
        }
        
        return $summary;
    }

    /**
     * تاريخ انتهاء صلاحية العرض
     */
    private function getValidUntil()
    {
        // العروض صالحة لمدة 30 يوم افتراضياً
        return $this->created_at?->addDays(30)->toISOString();
    }

    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => 'v1',
                'calculation_id' => $this->id,
                'timestamp' => now()->toISOString(),
            ],
            'actions' => [
                'save_url' => route('api.v1.costs.save', $this->id),
                'share_url' => route('api.v1.costs.share', $this->id),
                'pdf_url' => route('api.v1.costs.export.pdf', $this->id),
                'excel_url' => route('api.v1.costs.export.excel', $this->id),
            ]
        ];
    }
}