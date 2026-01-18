<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortResource extends JsonResource
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
            'code' => $this->code,
            'type' => $this->type, // sea, air, land
            'city' => $this->city,
            'address' => $this->address,
            'coordinates' => $this->coordinates ? json_decode($this->coordinates, true) : null,
            'is_active' => $this->is_active,
            
            // معلومات البلد
            'country' => $this->whenLoaded('country', function () {
                return [
                    'id' => $this->country->id,
                    'name' => $this->country->name,
                    'code' => $this->country->code,
                    'flag' => $this->country->flag_url,
                ];
            }),
            
            // التسهيلات المتاحة
            'facilities' => $this->facilities ? json_decode($this->facilities, true) : [],
            
            // معلومات الاتصال
            'contact_info' => $this->when(!is_null($this->contact_info), function () {
                return json_decode($this->contact_info, true);
            }),
            
            // الخدمات المتاحة
            'services' => $this->when(!is_null($this->services), function () {
                return json_decode($this->services, true);
            }),
            
            // معلومات إضافية للتطبيق المحمول
            'mobile_info' => [
                'formatted_address' => $this->getFormattedAddress(),
                'display_name' => $this->getDisplayName(),
                'type_icon' => $this->getTypeIcon(),
                'distance_unit' => $this->type === 'sea' ? 'nautical_miles' : 'km',
            ],
            
            // إحصائيات الاستخدام
            'usage_stats' => $this->when(isset($this->usage_count), function () {
                return [
                    'usage_count' => $this->usage_count,
                    'popularity_rank' => $this->popularity_rank ?? null,
                ];
            }),
            
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * الحصول على العنوان المنسق
     */
    private function getFormattedAddress()
    {
        $parts = array_filter([$this->name, $this->city, $this->country->name ?? null]);
        return implode(', ', $parts);
    }

    /**
     * الحصول على الاسم للعرض
     */
    private function getDisplayName()
    {
        return $this->city ? "{$this->name} ({$this->city})" : $this->name;
    }

    /**
     * الحصول على أيقونة نوع الميناء
     */
    private function getTypeIcon()
    {
        $icons = [
            'sea' => '🚢',
            'air' => '✈️',
            'land' => '🚛'
        ];

        return $icons[$this->type] ?? '📍';
    }
}