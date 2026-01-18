<?php

namespace App\Livewire\Manufacturing;

use Livewire\Component;
use App\Models\CostCalculation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ManufacturingCostCalculator extends Component
{
    // Input Fields
    public $product_name = '';
    public $batch_size = 1;
    public $unit_price = 0; // سعر البيع المطلوب للوحدة
    
    // Cost Items (البنود)
    public $items = [];

    // Pricing & Totals
    public $total_direct_cost = 0;
    public $total_indirect_cost = 0;
    public $total_production_cost = 0;
    public $margin_amount = 0;
    public $selling_price = 0;
    public $cost_per_unit = 0;
    public $target_margin_percent = 15;

    // KPIs
    public $direct_cost_ratio = 0;
    public $indirect_cost_ratio = 0;
    public $margin_ratio = 0;
    public $breakeven_units = 0;

    // UI State
    public $calculationName = '';
    public $calculationType = 'scenario'; // scenario, quote, invoice
    public $savedCalculationId = null;
    public $showSaveModal = false;

    // Validation rules
    protected $rules = [
        'product_name' => 'required|string|max:255',
        'batch_size' => 'required|integer|min:1',
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
            // Direct Costs
            ['category' => 'direct', 'name' => 'مواد خام (Raw Materials)', 'amount' => 0, 'editable' => true],
            ['category' => 'direct', 'name' => 'عمالة مباشرة (Direct Labor)', 'amount' => 0, 'editable' => true],
            ['category' => 'direct', 'name' => 'مواد مساعدة (Auxiliary Materials)', 'amount' => 0, 'editable' => true],
            
            // Indirect Costs
            ['category' => 'indirect', 'name' => 'طاقة (Energy/Utilities)', 'amount' => 0, 'editable' => true],
            ['category' => 'indirect', 'name' => 'صيانة (Maintenance)', 'amount' => 0, 'editable' => true],
            ['category' => 'indirect', 'name' => 'استهلاك معدات (Equipment Depreciation)', 'amount' => 0, 'editable' => true],
            ['category' => 'indirect', 'name' => 'إيجار المصنع (Factory Rent)', 'amount' => 0, 'editable' => true],
            ['category' => 'indirect', 'name' => 'رواتب إدارية (Administrative Salaries)', 'amount' => 0, 'editable' => true],
            ['category' => 'indirect', 'name' => 'تأمينات (Insurance)', 'amount' => 0, 'editable' => true],
            ['category' => 'indirect', 'name' => 'تكاليف غير مباشرة أخرى (Other Overhead)', 'amount' => 0, 'editable' => true],
        ];
    }

    /**
     * Load existing calculation
     */
    protected function loadCalculation($calculationId)
    {
        $calculation = CostCalculation::where('id', $calculationId)
            ->where('user_id', auth()->id())
            ->where('module', 'manufacturing')
            ->firstOrFail();

        $this->savedCalculationId = $calculation->id;
        $this->calculationName = $calculation->title;
        $this->calculationType = $calculation->type;

        // Load inputs
        $inputs = $calculation->inputs ?? [];
        $this->product_name = $inputs['product_name'] ?? '';
        $this->batch_size = $inputs['batch_size'] ?? 1;
        $this->unit_price = $inputs['unit_price'] ?? 0;
        $this->target_margin_percent = $inputs['target_margin_percent'] ?? 15;

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
        if (str_starts_with($propertyName, 'items.') || in_array($propertyName, ['batch_size', 'target_margin_percent', 'unit_price'])) {
            $this->calculate();
        }
    }

    /**
     * Calculate totals, costs, and KPIs
     */
    public function calculate()
    {
        // Separate direct and indirect costs
        $directItems = collect($this->items)->where('category', 'direct');
        $indirectItems = collect($this->items)->where('category', 'indirect');

        $this->total_direct_cost = $directItems->sum('amount');
        $this->total_indirect_cost = $indirectItems->sum('amount');
        
        // Total production cost
        $this->total_production_cost = $this->total_direct_cost + $this->total_indirect_cost;

        // Cost per unit
        $this->cost_per_unit = $this->batch_size > 0 ? round($this->total_production_cost / $this->batch_size, 2) : 0;

        // Calculate margin and selling price
        $this->margin_amount = round($this->total_production_cost * ($this->target_margin_percent / 100), 2);
        $this->selling_price = $this->total_production_cost + $this->margin_amount;

        // Unit price
        $this->unit_price = $this->batch_size > 0 ? round($this->selling_price / $this->batch_size, 2) : 0;

        // KPIs
        if ($this->total_production_cost > 0) {
            // Direct Cost Ratio
            $this->direct_cost_ratio = round(($this->total_direct_cost / $this->total_production_cost) * 100, 1);
            
            // Indirect Cost Ratio
            $this->indirect_cost_ratio = round(($this->total_indirect_cost / $this->total_production_cost) * 100, 1);
        } else {
            $this->direct_cost_ratio = 0;
            $this->indirect_cost_ratio = 0;
        }

        if ($this->selling_price > 0) {
            // Margin Ratio
            $this->margin_ratio = round(($this->margin_amount / $this->selling_price) * 100, 2);
        } else {
            $this->margin_ratio = 0;
        }

        // Breakeven Units (عدد الوحدات لتحقيق التعادل - assuming fixed costs are indirect)
        $variableCostPerUnit = $this->batch_size > 0 ? ($this->total_direct_cost / $this->batch_size) : 0;
        $contributionMargin = $this->unit_price - $variableCostPerUnit;
        
        if ($contributionMargin > 0) {
            $this->breakeven_units = ceil($this->total_indirect_cost / $contributionMargin);
        } else {
            $this->breakeven_units = 0;
        }
    }

    /**
     * Add custom item
     */
    public function addItem($category = 'direct')
    {
        $this->items[] = [
            'category' => $category,
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
            'product_name', 'batch_size', 'unit_price',
            'calculationName', 'savedCalculationId'
        ]);
        $this->calculationType = 'scenario';
        $this->target_margin_percent = 15;
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
            'product_name' => $this->product_name,
            'batch_size' => $this->batch_size,
            'unit_price' => $this->unit_price,
            'target_margin_percent' => $this->target_margin_percent,
        ];

        $totals = [
            'total_direct_cost' => $this->total_direct_cost,
            'total_indirect_cost' => $this->total_indirect_cost,
            'total_production_cost' => $this->total_production_cost,
            'margin_amount' => $this->margin_amount,
            'selling_price' => $this->selling_price,
            'cost_per_unit' => $this->cost_per_unit,
            'direct_cost_ratio' => $this->direct_cost_ratio,
            'indirect_cost_ratio' => $this->indirect_cost_ratio,
            'margin_ratio' => $this->margin_ratio,
            'breakeven_units' => $this->breakeven_units,
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
                'module' => 'manufacturing',
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

        $this->dispatch('alert', type: 'info', message: 'سيتم تصدير Excel قريباً');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.manufacturing.manufacturing-cost-calculator')->layout('layouts.dashboard');
    }
}
