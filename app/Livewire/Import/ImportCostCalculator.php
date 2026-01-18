<?php

namespace App\Livewire\Import;

use Livewire\Component;
use App\Models\CostCalculation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ImportCostCalculator extends Component
{
    // Input Fields
    public $purchase_price = 0;
    public $incoterm = 'FOB';
    public $origin_country = '';
    public $origin_port = '';
    public $destination_port = '';
    public $container_type = '20ft';
    public $gross_weight_kg = 0;
    public $cbm = 0;
    public $hs_code = '';
    public $product_category = '';

    // Cost Items (البنود)
    public $items = [];

    // Totals
    public $subtotal = 0;
    public $grand_total = 0;

    // KPIs
    public $duty_ratio = 0;
    public $logistics_share = 0;
    public $lead_time_days = 0;

    // UI State
    public $calculationName = '';
    public $calculationType = 'scenario'; // scenario, quote, invoice
    public $savedCalculationId = null;
    public $showSaveModal = false;
    public $showExportModal = false;

    // Validation rules
    protected $rules = [
        'purchase_price' => 'required|numeric|min:0',
        'incoterm' => 'required|in:EXW,FOB,CFR,CIF,DAP,DDP',
        'origin_country' => 'required|string|max:100',
        'destination_port' => 'required|string|max:100',
        'container_type' => 'required|in:20ft,40ft,40ft_hc',
        'gross_weight_kg' => 'required|numeric|min:0',
        'cbm' => 'required|numeric|min:0',
        'calculationName' => 'required_if:showSaveModal,true|string|max:255',
    ];

    /**
     * Mount component
     */
    public function mount($calculationId = null)
    {
        // Check authorization
        if (!Gate::allows('costs.view')) {
            abort(403, 'ليس لديك صلاحية لاستخدام حاسبة التكاليف');
        }

        // Load existing calculation if provided
        if ($calculationId) {
            $this->loadCalculation($calculationId);
        } else {
            // Initialize default items
            $this->initializeDefaultItems();
        }
    }

    /**
     * Initialize default cost items
     */
    protected function initializeDefaultItems()
    {
        $this->items = [
            ['name' => 'نقل دولي (International Freight)', 'amount' => 0, 'editable' => true],
            ['name' => 'تأمين (Insurance)', 'amount' => 0, 'editable' => true],
            ['name' => 'رسوم ميناء محلي (Local Port Fees)', 'amount' => 0, 'editable' => true],
            ['name' => 'رسوم جمركية (Customs Duties)', 'amount' => 0, 'editable' => true],
            ['name' => 'رسوم تخليص (Clearance Fees)', 'amount' => 0, 'editable' => true],
            ['name' => 'نقل داخلي (Inland Transport)', 'amount' => 0, 'editable' => true],
            ['name' => 'مصاريف أخرى (Other Expenses)', 'amount' => 0, 'editable' => true],
        ];
    }

    /**
     * Load existing calculation
     */
    protected function loadCalculation($calculationId)
    {
        $calculation = CostCalculation::where('id', $calculationId)
            ->where('user_id', auth()->id())
            ->where('module', 'import')
            ->firstOrFail();

        $this->savedCalculationId = $calculation->id;
        $this->calculationName = $calculation->title;
        $this->calculationType = $calculation->type;

        // Load inputs
        $inputs = $calculation->inputs ?? [];
        $this->purchase_price = $inputs['purchase_price'] ?? 0;
        $this->incoterm = $inputs['incoterm'] ?? 'FOB';
        $this->origin_country = $inputs['origin_country'] ?? '';
        $this->origin_port = $inputs['origin_port'] ?? '';
        $this->destination_port = $inputs['destination_port'] ?? '';
        $this->container_type = $inputs['container_type'] ?? '20ft';
        $this->gross_weight_kg = $inputs['gross_weight_kg'] ?? 0;
        $this->cbm = $inputs['cbm'] ?? 0;
        $this->hs_code = $inputs['hs_code'] ?? '';
        $this->product_category = $inputs['product_category'] ?? '';
        $this->lead_time_days = $inputs['lead_time_days'] ?? 0;

        // Load items
        $this->items = $calculation->items ?? $this->initializeDefaultItems();

        // Recalculate
        $this->calculate();
    }

    /**
     * Auto-calculate on field update
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['purchase_price', 'incoterm', 'container_type', 'gross_weight_kg', 'cbm'])) {
            $this->autoCalculateItems();
        }

        if (str_starts_with($propertyName, 'items.')) {
            $this->calculate();
        }
    }

    /**
     * Auto-calculate items based on formulas
     */
    protected function autoCalculateItems()
    {
        // International Freight (حسب نوع الحاوية والمسافة - تقديري)
        $freightRates = [
            '20ft' => 1500,
            '40ft' => 2500,
            '40ft_hc' => 2800,
        ];
        $this->items[0]['amount'] = $freightRates[$this->container_type] ?? 0;

        // Insurance (0.5% من قيمة البضاعة)
        $this->items[1]['amount'] = round($this->purchase_price * 0.005, 2);

        // Local Port Fees (تقديري حسب الوزن)
        $this->items[2]['amount'] = round($this->gross_weight_kg * 0.05, 2);

        // Customs Duties (10% من FOB - تقديري)
        if ($this->incoterm === 'FOB' || $this->incoterm === 'EXW') {
            $this->items[3]['amount'] = round($this->purchase_price * 0.10, 2);
        }

        // Clearance Fees (ثابتة تقريباً)
        $this->items[4]['amount'] = 300;

        // Inland Transport (حسب CBM)
        $this->items[5]['amount'] = round($this->cbm * 50, 2);

        // Recalculate totals
        $this->calculate();
    }

    /**
     * Calculate totals and KPIs
     */
    public function calculate()
    {
        // Calculate subtotal from items
        $this->subtotal = collect($this->items)->sum('amount');

        // Grand total = purchase price + logistics costs
        $this->grand_total = $this->purchase_price + $this->subtotal;

        // KPIs
        if ($this->grand_total > 0) {
            // Duty Ratio (نسبة الرسوم الجمركية)
            $customsDuty = $this->items[3]['amount'] ?? 0;
            $this->duty_ratio = round(($customsDuty / $this->grand_total) * 100, 2);

            // Logistics Share (نسبة تكاليف اللوجستيات)
            $this->logistics_share = round(($this->subtotal / $this->grand_total) * 100, 2);
        } else {
            $this->duty_ratio = 0;
            $this->logistics_share = 0;
        }

        // Lead Time (تقديري حسب الدولة والإنكوترم)
        $this->lead_time_days = $this->estimateLeadTime();
    }

    /**
     * Estimate lead time
     */
    protected function estimateLeadTime()
    {
        $baseDays = 30; // Default

        // Adjust based on origin
        if (str_contains(strtolower($this->origin_country), 'china')) {
            $baseDays = 35;
        } elseif (str_contains(strtolower($this->origin_country), 'europe')) {
            $baseDays = 20;
        } elseif (str_contains(strtolower($this->origin_country), 'usa')) {
            $baseDays = 25;
        }

        // Adjust based on incoterm
        if ($this->incoterm === 'DDP') {
            $baseDays += 5; // Extra clearance time
        }

        return $baseDays;
    }

    /**
     * Add custom item
     */
    public function addItem()
    {
        $this->items[] = [
            'name' => 'بند جديد',
            'amount' => 0,
            'editable' => true,
        ];
    }

    /**
     * Remove item
     */
    public function removeItem($index)
    {
        if (isset($this->items[$index]) && $this->items[$index]['editable']) {
            unset($this->items[$index]);
            $this->items = array_values($this->items); // Re-index
            $this->calculate();
        }
    }

    /**
     * Reset calculator
     */
    public function resetCalculator()
    {
        $this->reset([
            'purchase_price', 'origin_country', 'origin_port', 'destination_port',
            'gross_weight_kg', 'cbm', 'hs_code', 'product_category',
            'calculationName', 'savedCalculationId'
        ]);
        $this->incoterm = 'FOB';
        $this->container_type = '20ft';
        $this->calculationType = 'scenario';
        $this->initializeDefaultItems();
        $this->calculate();
    }

    /**
     * Save calculation
     */
    public function saveCalculation()
    {
        // Authorization check
        if (!Gate::allows('costs.create')) {
            $this->dispatch('alert', type: 'error', message: 'ليس لديك صلاحية لحفظ الحسابات');
            return;
        }

        $this->validate([
            'calculationName' => 'required|string|max:255',
        ]);

        $inputs = [
            'purchase_price' => $this->purchase_price,
            'incoterm' => $this->incoterm,
            'origin_country' => $this->origin_country,
            'origin_port' => $this->origin_port,
            'destination_port' => $this->destination_port,
            'container_type' => $this->container_type,
            'gross_weight_kg' => $this->gross_weight_kg,
            'cbm' => $this->cbm,
            'hs_code' => $this->hs_code,
            'product_category' => $this->product_category,
            'lead_time_days' => $this->lead_time_days,
        ];

        $totals = [
            'subtotal' => $this->subtotal,
            'grand_total' => $this->grand_total,
            'duty_ratio' => $this->duty_ratio,
            'logistics_share' => $this->logistics_share,
        ];

        if ($this->savedCalculationId) {
            // Update existing
            $calculation = CostCalculation::findOrFail($this->savedCalculationId);
            $this->authorize('update', $calculation);

            $calculation->update([
                'title' => $this->calculationName,
                'type' => $this->calculationType,
                'inputs' => $inputs,
                'items' => $this->items,
                'totals' => $totals,
                'grand_total' => $this->grand_total,
            ]);

            $message = 'تم تحديث الحساب بنجاح';
        } else {
            // Create new
            $calculation = CostCalculation::create([
                'user_id' => auth()->id(),
                'module' => 'import',
                'title' => $this->calculationName,
                'type' => $this->calculationType,
                'status' => 'draft',
                'inputs' => $inputs,
                'items' => $this->items,
                'totals' => $totals,
                'grand_total' => $this->grand_total,
            ]);

            $this->savedCalculationId = $calculation->id;
            $message = 'تم حفظ الحساب بنجاح';
        }

        $this->showSaveModal = false;
        $this->dispatch('alert', type: 'success', message: $message);
    }

    /**
     * Export to PDF
     */
    public function exportPdf()
    {
        if (!$this->savedCalculationId) {
            $this->dispatch('alert', type: 'error', message: 'يجب حفظ الحساب أولاً');
            return;
        }

        // In production, implement PDF generation logic
        $this->dispatch('alert', type: 'info', message: 'سيتم تصدير PDF قريباً');
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        if (!$this->savedCalculationId) {
            $this->dispatch('alert', type: 'error', message: 'يجب حفظ الحساب أولاً');
            return;
        }

        // In production, implement Excel generation logic
        $this->dispatch('alert', type: 'info', message: 'سيتم تصدير Excel قريباً');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.import.import-cost-calculator')->layout('layouts.dashboard');
    }
}
