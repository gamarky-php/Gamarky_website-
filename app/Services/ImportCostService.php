<?php

namespace App\Services;

use App\Models\Country;
use App\Models\Port;
use App\Models\ShippingType;
use Illuminate\Support\Facades\Cache;

class ImportCostService
{
    /**
     * Exchange rates (should be from database/API in production)
     */
    private array $exchangeRates = [
        'USD' => 3.75,
        'EUR' => 4.10,
        'GBP' => 4.65,
        'CNY' => 0.52,
        'TRY' => 0.12,
        'JPY' => 0.025,
        'SAR' => 1.00
    ];

    /**
     * Shipping rates per kg by shipping type
     */
    private array $shippingRates = [
        1 => 12, // Sea shipping - SAR per kg
        2 => 25, // Air shipping - SAR per kg  
        3 => 8,  // Land shipping - SAR per kg
    ];

    /**
     * Customs duty rates by product category
     */
    private array $customsRates = [
        'electronics' => 0.15,
        'clothing' => 0.10,
        'auto_parts' => 0.20,
        'food' => 0.05,
        'general' => 0.12
    ];

    /**
     * Calculate import costs based on provided data
     *
     * @param array $data
     * @return array
     */
    public function calculate(array $data): array
    {
        // Convert product value to SAR
        $productValueSAR = $this->convertToSAR(
            $data['product_value'],
            $data['product_currency']
        );

        // Get related models
        $portTo = Port::find($data['port_to']);
        $shippingType = ShippingType::find($data['shipping_type']);

        // Calculate individual costs
        $shippingCost = $this->calculateShippingCost($data['weight'], $data['shipping_type']);
        $customsDuty = $this->calculateCustomsDuty($productValueSAR, $data['product_category'] ?? 'general');
        $insuranceCost = $this->calculateInsurance($productValueSAR, $data);
        $additionalFees = $this->calculateAdditionalFees($portTo);
        
        // Calculate VAT (15% on product value + customs duty + shipping + fees)
        $vatBase = $productValueSAR + $customsDuty + $shippingCost + $additionalFees['total'];
        $vat = $vatBase * 0.15;

        // Calculate totals
        $subtotal = $productValueSAR + $shippingCost + $customsDuty + $insuranceCost;
        $totalCost = $subtotal + $additionalFees['total'] + $vat;

        return [
            'input_data' => [
                'product_value' => $data['product_value'],
                'product_currency' => $data['product_currency'],
                'product_value_sar' => round($productValueSAR, 2),
                'weight' => $data['weight'],
                'country_from' => $data['country_from'],
                'country_to' => $data['country_to'] ?? 'SA',
                'shipping_type' => $shippingType->name_ar ?? 'غير محدد',
                'port_to' => $portTo->name_ar ?? 'غير محدد',
            ],
            'cost_breakdown' => [
                'product_value_sar' => round($productValueSAR, 2),
                'shipping_cost' => round($shippingCost, 2),
                'customs_duty' => round($customsDuty, 2),
                'customs_rate' => round($this->getCustomsRate($data['product_category'] ?? 'general') * 100, 1) . '%',
                'insurance_cost' => round($insuranceCost, 2),
                'handling_fee' => $additionalFees['handling'],
                'documentation_fee' => $additionalFees['documentation'],
                'port_fee' => $additionalFees['port'],
                'vat' => round($vat, 2),
                'vat_rate' => '15%'
            ],
            'totals' => [
                'subtotal' => round($subtotal, 2),
                'total_fees' => $additionalFees['total'],
                'vat_amount' => round($vat, 2),
                'total_cost' => round($totalCost, 2)
            ],
            'estimated_delivery' => [
                'min_days' => $shippingType->min_delivery_days ?? 7,
                'max_days' => $shippingType->max_delivery_days ?? 14,
                'description' => $shippingType->delivery_description ?? 'التسليم المتوقع'
            ],
            'currency' => 'SAR',
            'exchange_rate_used' => isset($data['product_currency']) ? ($this->exchangeRates[$data['product_currency']] ?? 1) : 1,
            'calculation_date' => now()->toDateString(),
            'disclaimer' => 'هذه تقديرات أولية وقد تختلف التكاليف الفعلية حسب طبيعة البضاعة والإجراءات الجمركية'
        ];
    }

    /**
     * Convert amount to SAR
     */
    private function convertToSAR(float $amount, string $currency): float
    {
        return $amount * ($this->exchangeRates[$currency] ?? 1);
    }

    /**
     * Calculate shipping cost based on weight and shipping type
     */
    private function calculateShippingCost(float $weight, int $shippingType): float
    {
        $ratePerKg = $this->shippingRates[$shippingType] ?? 15;
        return $weight * $ratePerKg;
    }

    /**
     * Calculate customs duty
     */
    private function calculateCustomsDuty(float $productValueSAR, string $category): float
    {
        $rate = $this->getCustomsRate($category);
        return $productValueSAR * $rate;
    }

    /**
     * Get customs rate for category
     */
    private function getCustomsRate(string $category): float
    {
        return $this->customsRates[$category] ?? $this->customsRates['general'];
    }

    /**
     * Calculate insurance cost
     */
    private function calculateInsurance(float $productValueSAR, array $data): float
    {
        if (!($data['add_insurance'] ?? false)) {
            return 0;
        }

        $insuranceRate = ($data['insurance_rate'] ?? 2.5) / 100;
        return $productValueSAR * $insuranceRate;
    }

    /**
     * Calculate additional fees
     */
    private function calculateAdditionalFees(?Port $port): array
    {
        $handlingFee = 50;      // Fixed handling fee
        $documentationFee = 25; // Documentation fee  
        $portFee = $port->handling_fee ?? 75; // Port specific fee

        return [
            'handling' => $handlingFee,
            'documentation' => $documentationFee,
            'port' => $portFee,
            'total' => $handlingFee + $documentationFee + $portFee
        ];
    }

    /**
     * Get exchange rates (for API response)
     */
    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }

    /**
     * Get shipping rates (for API response)
     */
    public function getShippingRates(): array
    {
        return $this->shippingRates;
    }

    /**
     * Get customs rates (for API response)
     */
    public function getCustomsRates(): array
    {
        return $this->customsRates;
    }
}