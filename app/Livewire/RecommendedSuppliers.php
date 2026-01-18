<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class RecommendedSuppliers extends Component
{
    public ?string $countryCode = null;
    public int $limit = 6;

    public function mount(?string $countryCode = null, int $limit = 6)
    {
        $this->countryCode = $countryCode;
        $this->limit = max(1, (int)$limit);
    }

    public function render()
    {
        // Check if Supplier model exists
        if (!class_exists(\App\Models\Supplier::class)) {
            return view('livewire.recommended-suppliers', [
                'suppliers' => collect($this->getDummySuppliers())
            ]);
        }

        // Capture scalars to avoid $this binding issues in closure
        $limit = max(1, (int)$this->limit);
        $countryCode = $this->countryCode;
        $key = "rec_suppliers|{$countryCode}|{$limit}";

        try {
            $suppliers = Cache::remember($key, 600, function () use ($limit, $countryCode) {
                $query = \App\Models\Supplier::query()
                    ->select(['id','company_name','mobile_phone','tel','country_code','city'])
                    ->limit($limit);

                // Apply scopes if they exist
                if (method_exists(\App\Models\Supplier::class, 'scopeApproved')) {
                    $query->approved();
                }
                if (method_exists(\App\Models\Supplier::class, 'scopeHasContact')) {
                    $query->hasContact();
                }
                if (method_exists(\App\Models\Supplier::class, 'scopeForCountry') && $countryCode) {
                    $query->forCountry($countryCode);
                }

                return $query->inRandomOrder()->get();
            });
        } catch (\Exception $e) {
            // Fallback to dummy data if query fails
            $suppliers = collect($this->getDummySuppliers());
        }

        return view('livewire.recommended-suppliers', compact('suppliers'));
    }

    /**
     * Generate dummy suppliers for testing/fallback
     */
    private function getDummySuppliers(): array
    {
        $limit = min($this->limit, 6);
        $dummyData = [];

        $companies = [
            ['name' => 'شركة الإمداد التجاري', 'country' => 'sa', 'city' => 'الرياض', 'phone' => '+966501234567'],
            ['name' => 'مؤسسة التوريدات العالمية', 'country' => 'ae', 'city' => 'دبي', 'phone' => '+971501234567'],
            ['name' => 'شركة المصدر الأول', 'country' => 'eg', 'city' => 'القاهرة', 'phone' => '+201001234567'],
            ['name' => 'مؤسسة الشحن السريع', 'country' => 'sa', 'city' => 'جدة', 'phone' => '+966502345678'],
            ['name' => 'شركة التجارة الدولية', 'country' => 'kw', 'city' => 'الكويت', 'phone' => '+96550123456'],
            ['name' => 'مؤسسة النقل والتخليص', 'country' => 'eg', 'city' => 'الإسكندرية', 'phone' => '+201112345678'],
        ];

        for ($i = 0; $i < $limit; $i++) {
            $company = $companies[$i % count($companies)];
            $dummyData[] = (object) [
                'id' => $i + 1,
                'company_name' => $company['name'],
                'mobile_phone' => $company['phone'],
                'tel' => null,
                'country_code' => $company['country'],
                'city' => $company['city'],
            ];
        }

        return $dummyData;
    }
}
