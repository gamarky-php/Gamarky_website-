<?php

namespace App\Livewire\Agency;

use Livewire\Component;

class AgencyCommissionCalculator extends Component
{
    // Agency Type
    public $agencyType = 'shipping'; // shipping | brand

    // Shipping Agent Commission Inputs
    public $shipment_value = 10000;
    public $commission_percentage = 3;
    public $setup_fee = 500;
    public $minimum_commission = 200;
    public $number_of_shipments = 1;
    public $marketing_materials = false;
    public $marketing_cost = 300;

    // Brand Agent Commission Inputs
    public $brand_annual_sales = 100000;
    public $brand_commission_tier = 5;
    public $brand_setup_fee = 1000;
    public $brand_minimum_annual = 5000;
    public $brand_marketing_package = 'basic'; // basic | standard | premium
    public $territory_exclusivity = false;
    public $exclusivity_fee = 2000;

    // Marketing Package Costs
    public $marketingPackages = [
        'basic' => ['name' => 'أساسي', 'cost' => 500],
        'standard' => ['name' => 'قياسي', 'cost' => 1200],
        'premium' => ['name' => 'متقدم', 'cost' => 2500],
    ];

    // Results
    public $results = [];

    public function mount()
    {
        $this->calculate();
    }

    public function updatedAgencyType()
    {
        $this->calculate();
    }

    public function updated($propertyName)
    {
        $this->calculate();
    }

    public function calculate()
    {
        if ($this->agencyType === 'shipping') {
            $this->calculateShippingCommission();
        } else {
            $this->calculateBrandCommission();
        }
    }

    private function calculateShippingCommission()
    {
        // Base commission calculation
        $base_commission = ($this->shipment_value * $this->commission_percentage) / 100;
        
        // Apply minimum commission rule
        $commission_per_shipment = max($base_commission, $this->minimum_commission);
        
        // Total commission for all shipments
        $total_commission = $commission_per_shipment * $this->number_of_shipments;
        
        // Marketing materials cost
        $marketing_total = $this->marketing_materials ? $this->marketing_cost : 0;
        
        // Total fees
        $total_cost = $this->setup_fee + $total_commission + $marketing_total;

        $this->results = [
            'setup_fee' => $this->setup_fee,
            'base_commission' => $base_commission,
            'commission_per_shipment' => $commission_per_shipment,
            'total_commission' => $total_commission,
            'marketing_cost' => $marketing_total,
            'grand_total' => $total_cost,
            'breakdown' => [
                ['item' => 'رسوم التأسيس', 'amount' => $this->setup_fee],
                ['item' => 'عمولة الشحنات', 'amount' => $total_commission],
                ['item' => 'مواد تسويقية', 'amount' => $marketing_total],
            ],
        ];
    }

    private function calculateBrandCommission()
    {
        // Annual commission calculation
        $annual_commission = ($this->brand_annual_sales * $this->brand_commission_tier) / 100;
        
        // Apply minimum annual commission
        $final_commission = max($annual_commission, $this->brand_minimum_annual);
        
        // Marketing package cost
        $marketing_package_cost = $this->marketingPackages[$this->brand_marketing_package]['cost'];
        
        // Exclusivity fee
        $exclusivity_total = $this->territory_exclusivity ? $this->exclusivity_fee : 0;
        
        // Total fees
        $total_cost = $this->brand_setup_fee + $final_commission + $marketing_package_cost + $exclusivity_total;

        $this->results = [
            'setup_fee' => $this->brand_setup_fee,
            'base_commission' => $annual_commission,
            'final_commission' => $final_commission,
            'marketing_package' => $this->marketingPackages[$this->brand_marketing_package]['name'],
            'marketing_cost' => $marketing_package_cost,
            'exclusivity_fee' => $exclusivity_total,
            'grand_total' => $total_cost,
            'breakdown' => [
                ['item' => 'رسوم التأسيس', 'amount' => $this->brand_setup_fee],
                ['item' => 'العمولة السنوية', 'amount' => $final_commission],
                ['item' => 'باقة التسويق', 'amount' => $marketing_package_cost],
                ['item' => 'رسوم الحصرية الإقليمية', 'amount' => $exclusivity_total],
            ],
        ];
    }

    public function resetCalculator()
    {
        if ($this->agencyType === 'shipping') {
            $this->shipment_value = 10000;
            $this->commission_percentage = 3;
            $this->setup_fee = 500;
            $this->minimum_commission = 200;
            $this->number_of_shipments = 1;
            $this->marketing_materials = false;
            $this->marketing_cost = 300;
        } else {
            $this->brand_annual_sales = 100000;
            $this->brand_commission_tier = 5;
            $this->brand_setup_fee = 1000;
            $this->brand_minimum_annual = 5000;
            $this->brand_marketing_package = 'basic';
            $this->territory_exclusivity = false;
            $this->exclusivity_fee = 2000;
        }
        
        $this->calculate();
    }

    public function render()
    {
        return view('livewire.agency.agency-commission-calculator');
    }
}
