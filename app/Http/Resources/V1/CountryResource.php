<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'iso_code' => $this->iso_code,
            'currency_code' => $this->currency_code,
            'currency_symbol' => $this->currency_symbol,
            'calling_code' => $this->calling_code,
            'timezone' => $this->timezone,
            'flag' => $this->flag_url,
            'is_active' => $this->is_active,
            
            // عدد الموانئ
            'ports_count' => $this->when($this->relationLoaded('ports'), function () {
                return $this->ports->count();
            }),
            
            // الموانئ إذا كانت محملة
            'ports' => PortResource::collection($this->whenLoaded('ports')),
            
            // عدد المستخدمين
            'users_count' => $this->when(isset($this->users_count), $this->users_count),
            
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}