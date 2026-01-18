<?php

namespace App\Services\Pricing;

use Carbon\Carbon;

class ShippingAggregator
{
    /**
     * Get container shipping quotes from multiple providers
     * 
     * @param array $payload
     * @return array
     */
    public function getContainerQuotes(array $payload): array
    {
        // TODO: Replace with real API integrations (Maersk, MSC, CMA CGM, etc.)
        $mockQuotes = [
            [
                'provider_name' => 'Maersk Line',
                'service_level' => 'Express Service',
                'price_usd' => 3200,
                'transit_days' => 18,
                'reputation_score' => 9.5,
                'valid_until' => now()->addDays(7)->toDateTimeString(),
            ],
            [
                'provider_name' => 'MSC Mediterranean',
                'service_level' => 'Standard Service',
                'price_usd' => 2850,
                'transit_days' => 22,
                'reputation_score' => 9.2,
                'valid_until' => now()->addDays(5)->toDateTimeString(),
            ],
            [
                'provider_name' => 'CMA CGM',
                'service_level' => 'Economy Service',
                'price_usd' => 2500,
                'transit_days' => 28,
                'reputation_score' => 8.8,
                'valid_until' => now()->addDays(10)->toDateTimeString(),
            ],
            [
                'provider_name' => 'COSCO Shipping',
                'service_level' => 'Fast Track',
                'price_usd' => 3400,
                'transit_days' => 16,
                'reputation_score' => 9.0,
                'valid_until' => now()->addDays(3)->toDateTimeString(),
            ],
        ];

        // Calculate score for each quote
        foreach ($mockQuotes as &$quote) {
            $quote['score'] = $this->calculateScore($quote);
        }

        // Sort by score (highest first)
        usort($mockQuotes, fn($a, $b) => $b['score'] <=> $a['score']);

        return $mockQuotes;
    }

    /**
     * Get truck shipping quotes from multiple providers
     * 
     * @param array $payload
     * @return array
     */
    public function getTruckQuotes(array $payload): array
    {
        // TODO: Replace with real logistics providers
        $mockQuotes = [
            [
                'provider_name' => 'الشحن السريع',
                'service_level' => 'خدمة سريعة',
                'price_usd' => 850,
                'transit_days' => 3,
                'reputation_score' => 9.3,
                'valid_until' => now()->addDays(7)->toDateTimeString(),
            ],
            [
                'provider_name' => 'نقليات الخليج',
                'service_level' => 'خدمة عادية',
                'price_usd' => 680,
                'transit_days' => 5,
                'reputation_score' => 8.9,
                'valid_until' => now()->addDays(5)->toDateTimeString(),
            ],
            [
                'provider_name' => 'شركة الطرق البرية',
                'service_level' => 'خدمة اقتصادية',
                'price_usd' => 550,
                'transit_days' => 7,
                'reputation_score' => 8.5,
                'valid_until' => now()->addDays(10)->toDateTimeString(),
            ],
        ];

        foreach ($mockQuotes as &$quote) {
            $quote['score'] = $this->calculateScore($quote);
        }

        usort($mockQuotes, fn($a, $b) => $b['score'] <=> $a['score']);

        return $mockQuotes;
    }

    /**
     * Calculate aggregated score based on price, time, and reputation
     * 
     * Formula: Score = (0.4 * normalized_reputation) + (0.3 * price_factor) + (0.3 * time_factor)
     * 
     * @param array $quote
     * @return float
     */
    protected function calculateScore(array $quote): float
    {
        // Normalize reputation (0-10 scale)
        $reputationFactor = ($quote['reputation_score'] / 10) * 100;

        // Price factor (lower is better)
        // Assuming max reasonable price is $5000 for containers, $2000 for trucks
        $maxPrice = $quote['price_usd'] > 1500 ? 5000 : 2000;
        $priceFactor = (1 - ($quote['price_usd'] / $maxPrice)) * 100;

        // Time factor (faster is better)
        // Assuming max reasonable days is 40 for containers, 15 for trucks
        $maxDays = $quote['transit_days'] > 15 ? 40 : 15;
        $timeFactor = (1 - ($quote['transit_days'] / $maxDays)) * 100;

        // Weighted average
        $score = (0.4 * $reputationFactor) + (0.3 * $priceFactor) + (0.3 * $timeFactor);

        return round($score, 1);
    }
}
