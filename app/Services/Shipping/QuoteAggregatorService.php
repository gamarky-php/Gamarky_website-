<?php

namespace App\Services\Shipping;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * خدمة تجميع عروض الأسعار من شركات الشحن المختلفة
 * Quote Aggregator Service
 */
class QuoteAggregatorService
{
    /**
     * مزودو خدمات الشحن
     */
    private const PROVIDERS = [
        'maersk' => [
            'name' => 'Maersk Line',
            'api_url' => 'https://api.maersk.com/quotes',
            'enabled' => true,
        ],
        'msc' => [
            'name' => 'MSC Mediterranean',
            'api_url' => 'https://api.msc.com/quotes',
            'enabled' => true,
        ],
        'cma_cgm' => [
            'name' => 'CMA CGM',
            'api_url' => 'https://api.cmacgm.com/quotes',
            'enabled' => true,
        ],
        'cosco' => [
            'name' => 'COSCO Shipping',
            'api_url' => 'https://api.cosco.com/quotes',
            'enabled' => true,
        ],
        'hapag_lloyd' => [
            'name' => 'Hapag-Lloyd',
            'api_url' => 'https://api.hapag-lloyd.com/quotes',
            'enabled' => true,
        ],
    ];

    /**
     * الحصول على عروض الأسعار من جميع المزودين
     *
     * @param array $searchParams
     * @return Collection
     */
    public function aggregateQuotes(array $searchParams): Collection
    {
        $cacheKey = $this->generateCacheKey($searchParams);

        // محاولة جلب النتائج من الكاش (صالحة لمدة 30 دقيقة)
        return Cache::remember($cacheKey, 1800, function () use ($searchParams) {
            $quotes = collect();

            foreach (self::PROVIDERS as $providerKey => $providerConfig) {
                if (!$providerConfig['enabled']) {
                    continue;
                }

                try {
                    $providerQuotes = $this->fetchFromProvider($providerKey, $providerConfig, $searchParams);
                    $quotes = $quotes->merge($providerQuotes);
                } catch (\Exception $e) {
                    Log::error("Failed to fetch quotes from {$providerKey}: " . $e->getMessage());
                    // الاستمرار في جلب من مزودين آخرين
                }
            }

            return $quotes;
        });
    }

    /**
     * جلب العروض من مزود واحد
     *
     * @param string $providerKey
     * @param array $providerConfig
     * @param array $searchParams
     * @return Collection
     */
    private function fetchFromProvider(string $providerKey, array $providerConfig, array $searchParams): Collection
    {
        // في الإنتاج، هذه ستكون استدعاءات حقيقية للـ API
        // الآن نستخدم بيانات وهمية للعرض التوضيحي
        
        if (config('app.env') === 'production') {
            return $this->fetchFromProviderAPI($providerConfig['api_url'], $searchParams);
        }

        // محاكاة البيانات للتطوير
        return $this->generateMockQuotes($providerKey, $providerConfig, $searchParams);
    }

    /**
     * جلب البيانات من API حقيقي (للإنتاج)
     *
     * @param string $apiUrl
     * @param array $searchParams
     * @return Collection
     */
    private function fetchFromProviderAPI(string $apiUrl, array $searchParams): Collection
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . config('services.shipping.api_key'),
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, [
                    'origin' => $searchParams['origin_port'],
                    'destination' => $searchParams['destination_port'],
                    'loading_date' => $searchParams['loading_date'],
                    'weight_kg' => $searchParams['weight_kg'],
                    'cbm' => $searchParams['cbm'] ?? null,
                    'cargo_type' => $searchParams['cargo_type'],
                    'service_type' => $searchParams['service_type'],
                    'container_type' => $searchParams['container_type'],
                ]);

            if ($response->successful()) {
                return collect($response->json('quotes', []));
            }

            Log::warning("API request to {$apiUrl} returned status {$response->status()}");
            return collect();

        } catch (\Exception $e) {
            Log::error("API request to {$apiUrl} failed: " . $e->getMessage());
            return collect();
        }
    }

    /**
     * توليد عروض وهمية للتطوير والاختبار
     *
     * @param string $providerKey
     * @param array $providerConfig
     * @param array $searchParams
     * @return Collection
     */
    private function generateMockQuotes(string $providerKey, array $providerConfig, array $searchParams): Collection
    {
        $basePrice = $this->calculateBasePrice($searchParams);
        $transitDays = $this->calculateTransitDays($searchParams);

        // تنويع الأسعار والأوقات بناءً على المزود
        $priceVariation = match($providerKey) {
            'maersk' => 1.15,
            'msc' => 0.95,
            'cma_cgm' => 1.22,
            'cosco' => 0.85,
            'hapag_lloyd' => 1.18,
            default => 1.0,
        };

        $timeVariation = match($providerKey) {
            'maersk' => 0,
            'msc' => 3,
            'cma_cgm' => -2,
            'cosco' => 6,
            'hapag_lloyd' => 1,
            default => 0,
        };

        $totalPrice = round($basePrice * $priceVariation, 2);
        $shipping = round($totalPrice * 0.68, 2);
        $portFees = round($totalPrice * 0.16, 2);
        $documentation = round($totalPrice * 0.08, 2);
        $insurance = round($totalPrice * 0.08, 2);

        return collect([[
            'id' => strtoupper($providerKey) . '_' . uniqid(),
            'company' => $providerConfig['name'],
            'logo' => $this->getProviderLogo($providerKey),
            'total_price' => $totalPrice,
            'currency' => 'USD',
            'transit_days' => max(10, $transitDays + $timeVariation),
            'validity_hours' => rand(24, 96),
            'rating' => round(4.3 + (rand(0, 7) / 10), 1),
            'is_door_to_door' => in_array($providerKey, ['maersk', 'cma_cgm', 'hapag_lloyd']),
            'has_cargox' => in_array($providerKey, ['maersk', 'msc', 'hapag_lloyd']),
            'breakdown' => [
                'shipping' => $shipping,
                'port_fees' => $portFees,
                'documentation' => $documentation,
                'insurance' => $insurance,
            ],
            'cutoff_date' => now()->addDays(rand(10, 15))->format('Y-m-d'),
            'provider' => $providerKey,
        ]]);
    }

    /**
     * حساب السعر الأساسي بناءً على معايير البحث
     *
     * @param array $searchParams
     * @return float
     */
    private function calculateBasePrice(array $searchParams): float
    {
        $basePrice = 800;

        // إضافة تكلفة بناءً على نوع الحاوية
        $containerPricing = [
            '20GP' => 1.0,
            '40GP' => 1.6,
            '40HQ' => 1.7,
            'Reefer' => 2.2,
        ];
        $basePrice *= $containerPricing[$searchParams['container_type']] ?? 1.0;

        // إضافة تكلفة للبضائع الخطرة
        if ($searchParams['cargo_type'] === 'dangerous') {
            $basePrice *= 1.35;
        }

        // إضافة تكلفة بناءً على الوزن
        if ($searchParams['weight_kg'] > 20000) {
            $basePrice *= 1.2;
        }

        // نوع الخدمة
        if ($searchParams['service_type'] === 'LCL') {
            $basePrice *= 0.7;
        }

        return $basePrice;
    }

    /**
     * حساب أيام الشحن التقريبية
     *
     * @param array $searchParams
     * @return int
     */
    private function calculateTransitDays(array $searchParams): int
    {
        // في الإنتاج، هذا سيعتمد على قاعدة بيانات المسافات بين الموانئ
        // الآن نستخدم قيمة افتراضية مع بعض العشوائية
        return rand(15, 25);
    }

    /**
     * الحصول على شعار المزود
     *
     * @param string $providerKey
     * @return string
     */
    private function getProviderLogo(string $providerKey): string
    {
        $logos = [
            'maersk' => 'https://via.placeholder.com/120x60/0F2E5D/FFFFFF?text=Maersk',
            'msc' => 'https://via.placeholder.com/120x60/0F2E5D/FFFFFF?text=MSC',
            'cma_cgm' => 'https://via.placeholder.com/120x60/0F2E5D/FFFFFF?text=CMA+CGM',
            'cosco' => 'https://via.placeholder.com/120x60/0F2E5D/FFFFFF?text=COSCO',
            'hapag_lloyd' => 'https://via.placeholder.com/120x60/0F2E5D/FFFFFF?text=Hapag-Lloyd',
        ];

        return $logos[$providerKey] ?? 'https://via.placeholder.com/120x60/0F2E5D/FFFFFF?text=Carrier';
    }

    /**
     * توليد مفتاح الكاش من معايير البحث
     *
     * @param array $searchParams
     * @return string
     */
    private function generateCacheKey(array $searchParams): string
    {
        return 'shipping_quotes_' . md5(json_encode($searchParams));
    }

    /**
     * مسح الكاش لعروض معينة
     *
     * @param array $searchParams
     * @return void
     */
    public function clearCache(array $searchParams): void
    {
        $cacheKey = $this->generateCacheKey($searchParams);
        Cache::forget($cacheKey);
    }

    /**
     * الحصول على قائمة الموانئ المتاحة
     *
     * @return Collection
     */
    public function getAvailablePorts(): Collection
    {
        return collect([
            ['code' => 'AEJEA', 'name' => 'جبل علي', 'country' => 'الإمارات', 'region' => 'الخليج'],
            ['code' => 'SAJED', 'name' => 'جدة', 'country' => 'السعودية', 'region' => 'الخليج'],
            ['code' => 'EGPSD', 'name' => 'بورسعيد', 'country' => 'مصر', 'region' => 'البحر المتوسط'],
            ['code' => 'EGSUZ', 'name' => 'السويس', 'country' => 'مصر', 'region' => 'البحر الأحمر'],
            ['code' => 'EGALY', 'name' => 'الإسكندرية', 'country' => 'مصر', 'region' => 'البحر المتوسط'],
            ['code' => 'KWKWI', 'name' => 'الكويت', 'country' => 'الكويت', 'region' => 'الخليج'],
            ['code' => 'QADOH', 'name' => 'الدوحة', 'country' => 'قطر', 'region' => 'الخليج'],
            ['code' => 'OMSLM', 'name' => 'صلالة', 'country' => 'عمان', 'region' => 'الخليج'],
            ['code' => 'JOAQJ', 'name' => 'العقبة', 'country' => 'الأردن', 'region' => 'البحر الأحمر'],
            ['code' => 'CNSHA', 'name' => 'شنغهاي', 'country' => 'الصين', 'region' => 'شرق آسيا'],
            ['code' => 'SGSIN', 'name' => 'سنغافورة', 'country' => 'سنغافورة', 'region' => 'جنوب شرق آسيا'],
            ['code' => 'NLRTM', 'name' => 'روتردام', 'country' => 'هولندا', 'region' => 'أوروبا'],
            ['code' => 'USNYC', 'name' => 'نيويورك', 'country' => 'أمريكا', 'region' => 'أمريكا الشمالية'],
            ['code' => 'DEHAM', 'name' => 'هامبورغ', 'country' => 'ألمانيا', 'region' => 'أوروبا'],
            ['code' => 'GBLON', 'name' => 'لندن', 'country' => 'بريطانيا', 'region' => 'أوروبا'],
            ['code' => 'INMUN', 'name' => 'مومباي', 'country' => 'الهند', 'region' => 'جنوب آسيا'],
            ['code' => 'BRRIO', 'name' => 'ريو دي جانيرو', 'country' => 'البرازيل', 'region' => 'أمريكا الجنوبية'],
            ['code' => 'ZADUR', 'name' => 'ديربان', 'country' => 'جنوب أفريقيا', 'region' => 'أفريقيا'],
        ]);
    }

    /**
     * البحث عن ميناء بالاسم أو الرمز
     *
     * @param string $query
     * @return Collection
     */
    public function searchPorts(string $query): Collection
    {
        $query = strtolower($query);
        
        return $this->getAvailablePorts()->filter(function ($port) use ($query) {
            return str_contains(strtolower($port['name']), $query) ||
                   str_contains(strtolower($port['code']), $query) ||
                   str_contains(strtolower($port['country']), $query);
        });
    }
}
