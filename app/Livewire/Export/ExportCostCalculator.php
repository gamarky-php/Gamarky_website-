<?php

namespace App\Livewire\Export;

use Livewire\Component;
use App\Models\CostCalculation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ExportCostCalculator extends Component
{
    // Input Fields
    public $production_cost = 0;
    public $quantity = 1;
    public $incoterm = 'EXW';
    public $destination_country = '';
    public $destination_port = '';
    public $origin_port = '';
    public $container_type = '20ft';
    public $gross_weight_kg = 0;
    public $cbm = 0;
    public $hs_code = '';
    public $product_description = '';
    public $target_margin_percent = 20;

    // Cost Items (البنود)
    public $items = [];

    // Pricing
    public $total_cost = 0;
    public $margin_amount = 0;
    public $selling_price = 0;
    public $unit_selling_price = 0;

    // KPIs
    public $margin_ratio = 0;
    public $export_readiness_score = 0;
    public $profit_per_unit = 0;

    // UI State
    public $calculationName = '';
    public $calculationType = 'scenario'; // scenario, quote, invoice
    public $savedCalculationId = null;
    public $showSaveModal = false;
    public $showExportModal = false;

    // Validation rules
    protected $rules = [
        'production_cost' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:1',
        'incoterm' => 'required|in:EXW,FCA,FAS,FOB,CFR,CIF,CPT,CIP,DAP,DPU,DDP',
        'destination_country' => 'required|string|max:100',
        'destination_port' => 'required|string|max:100',
        'container_type' => 'required|in:20ft,40ft,40ft_hc',
        'gross_weight_kg' => 'required|numeric|min:0',
        'cbm' => 'required|numeric|min:0',
        'target_margin_percent' => 'required|numeric|min:0|max:100',
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
            ['name' => 'تكلفة الإنتاج (Production Cost)', 'amount' => 0, 'editable' => false, 'auto' => true],
            ['name' => 'التعبئة والتغليف (Packaging)', 'amount' => 0, 'editable' => true, 'auto' => false],
            ['name' => 'التغليف للتصدير (Export Packing)', 'amount' => 0, 'editable' => true, 'auto' => false],
            ['name' => 'النقل للميناء (Inland Transport)', 'amount' => 0, 'editable' => true, 'auto' => true],
            ['name' => 'رسوم ميناء (Port Fees)', 'amount' => 0, 'editable' => true, 'auto' => false],
            ['name' => 'الشحن الدولي (International Freight)', 'amount' => 0, 'editable' => true, 'auto' => true],
            ['name' => 'التأمين (Insurance)', 'amount' => 0, 'editable' => true, 'auto' => true],
            ['name' => 'العمولات (Commissions)', 'amount' => 0, 'editable' => true, 'auto' => false],
            ['name' => 'مصاريف أخرى (Other Expenses)', 'amount' => 0, 'editable' => true, 'auto' => false],
        ];
    }

    /**
     * Load existing calculation
     */
    protected function loadCalculation($calculationId)
    {
        $calculation = CostCalculation::where('id', $calculationId)
            ->where('user_id', auth()->id())
            ->where('module', 'export')
            ->firstOrFail();

        $this->savedCalculationId = $calculation->id;
        $this->calculationName = $calculation->title;
        $this->calculationType = $calculation->type;

        // Load inputs
        $inputs = $calculation->inputs ?? [];
        $this->production_cost = $inputs['production_cost'] ?? 0;
        $this->quantity = $inputs['quantity'] ?? 1;
        $this->incoterm = $inputs['incoterm'] ?? 'EXW';
        $this->destination_country = $inputs['destination_country'] ?? '';
        $this->destination_port = $inputs['destination_port'] ?? '';
        $this->origin_port = $inputs['origin_port'] ?? '';
        $this->container_type = $inputs['container_type'] ?? '20ft';
        $this->gross_weight_kg = $inputs['gross_weight_kg'] ?? 0;
        $this->cbm = $inputs['cbm'] ?? 0;
        $this->hs_code = $inputs['hs_code'] ?? '';
        $this->product_description = $inputs['product_description'] ?? '';
        $this->target_margin_percent = $inputs['target_margin_percent'] ?? 20;

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
        if (in_array($propertyName, ['production_cost', 'quantity', 'incoterm', 'container_type', 'gross_weight_kg', 'cbm', 'target_margin_percent'])) {
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
        // Production Cost (البند الأول - تلقائي)
        $this->items[0]['amount'] = $this->production_cost * $this->quantity;

        // Packaging (2% من تكلفة الإنتاج - تقديري)
        $this->items[1]['amount'] = round($this->items[0]['amount'] * 0.02, 2);

        // Export Packing (3% من تكلفة الإنتاج - تقديري)
        $this->items[2]['amount'] = round($this->items[0]['amount'] * 0.03, 2);

        // Inland Transport (حسب CBM - $30 لكل CBM)
        $this->items[3]['amount'] = round($this->cbm * 30, 2);

        // International Freight (حسب نوع الحاوية)
        $freightRates = [
            '20ft' => 2000,
            '40ft' => 3500,
            '40ft_hc' => 3800,
        ];
        $this->items[5]['amount'] = $freightRates[$this->container_type] ?? 0;

        // Insurance (0.3% من قيمة البضاعة)
        $goodsValue = $this->items[0]['amount'];
        $this->items[6]['amount'] = round($goodsValue * 0.003, 2);

        // Commissions (5% من تكلفة الإنتاج - تقديري)
        $this->items[7]['amount'] = round($this->items[0]['amount'] * 0.05, 2);

        // Recalculate totals
        $this->calculate();
    }

    /**
     * Calculate totals, margin, and KPIs
     */
    public function calculate()
    {
        // Total cost from all items
        $this->total_cost = collect($this->items)->sum('amount');

        // Calculate margin based on target percentage
        $this->margin_amount = round($this->total_cost * ($this->target_margin_percent / 100), 2);

        // Selling price = Total cost + Margin
        $this->selling_price = $this->total_cost + $this->margin_amount;

        // Unit selling price
        $this->unit_selling_price = $this->quantity > 0 ? round($this->selling_price / $this->quantity, 2) : 0;

        // Profit per unit
        $unitCost = $this->quantity > 0 ? round($this->total_cost / $this->quantity, 2) : 0;
        $this->profit_per_unit = $this->unit_selling_price - $unitCost;

        // KPIs
        if ($this->selling_price > 0) {
            // Margin Ratio (نسبة الهامش)
            $this->margin_ratio = round(($this->margin_amount / $this->selling_price) * 100, 2);
        } else {
            $this->margin_ratio = 0;
        }

        // Export Readiness Score (درجة الجاهزية للتصدير - تقديرية)
        $this->export_readiness_score = $this->calculateReadinessScore();
    }

    /**
     * Calculate export readiness score (0-100)
     */
    protected function calculateReadinessScore()
    {
        $score = 0;

        // Has production cost (20 points)
        if ($this->production_cost > 0) $score += 20;

        // Has destination (20 points)
        if (!empty($this->destination_country)) $score += 20;

        // Has packaging cost (15 points)
        if ($this->items[1]['amount'] > 0 || $this->items[2]['amount'] > 0) $score += 15;

        // Has freight cost (20 points)
        if ($this->items[5]['amount'] > 0) $score += 20;

        // Has reasonable margin (15 points)
        if ($this->target_margin_percent >= 10 && $this->target_margin_percent <= 50) $score += 15;

        // Has HS code (10 points)
        if (!empty($this->hs_code)) $score += 10;

        return min($score, 100);
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
            'auto' => false,
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
            'production_cost', 'quantity', 'destination_country', 'destination_port', 'origin_port',
            'gross_weight_kg', 'cbm', 'hs_code', 'product_description',
            'calculationName', 'savedCalculationId'
        ]);
        $this->incoterm = 'EXW';
        $this->container_type = '20ft';
        $this->calculationType = 'scenario';
        $this->target_margin_percent = 20;
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
            'production_cost' => $this->production_cost,
            'quantity' => $this->quantity,
            'incoterm' => $this->incoterm,
            'destination_country' => $this->destination_country,
            'destination_port' => $this->destination_port,
            'origin_port' => $this->origin_port,
            'container_type' => $this->container_type,
            'gross_weight_kg' => $this->gross_weight_kg,
            'cbm' => $this->cbm,
            'hs_code' => $this->hs_code,
            'product_description' => $this->product_description,
            'target_margin_percent' => $this->target_margin_percent,
        ];

        $totals = [
            'total_cost' => $this->total_cost,
            'margin_amount' => $this->margin_amount,
            'margin_ratio' => $this->margin_ratio,
            'selling_price' => $this->selling_price,
            'unit_selling_price' => $this->unit_selling_price,
            'profit_per_unit' => $this->profit_per_unit,
            'export_readiness_score' => $this->export_readiness_score,
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
                'grand_total' => $this->selling_price,
            ]);

            $message = 'تم تحديث الحساب بنجاح';
        } else {
            // Create new
            $calculation = CostCalculation::create([
                'user_id' => auth()->id(),
                'module' => 'export',
                'title' => $this->calculationName,
                'type' => $this->calculationType,
                'status' => 'draft',
                'inputs' => $inputs,
                'items' => $this->items,
                'totals' => $totals,
                'grand_total' => $this->selling_price,
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
        return view('livewire.export.export-cost-calculator')->layout('layouts.dashboard');
    }
}
