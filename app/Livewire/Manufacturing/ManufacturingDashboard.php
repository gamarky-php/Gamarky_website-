<?php

namespace App\Livewire\Manufacturing;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\ManufacturingOperation;

class ManufacturingDashboard extends Component
{
    use WithPagination;

    // Filters
    public string $dateRange = 'month';
    public $dateFrom;
    public $dateTo;
    public string $statusFilter = 'all';
    public string $productTypeFilter = 'all';
    public $searchTerm = '';

    // Loading States
    public bool $kpisLoading = false;
    
    // Data Properties with Safe Defaults
    public array $kpis = [
        'in_progress' => 0,
        'avg_production_cost' => 0,
        'production_efficiency' => 0,
        'total_production' => 0,
    ];
    
    public array $productTypes = [];
    
    // Safe Defaults للمتغيرات المطلوبة في الـ View
    public $recentOperations;
    public $recentCalculations;
    public $productionByType;

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo = now()->endOfMonth()->toDateString();
        
        // تهيئة المتغيرات بقيم آمنة
        $this->recentOperations   = collect();
        $this->recentCalculations = collect();
        $this->productionByType   = collect();
        
        $this->loadKpis();
        $this->loadProductTypes();
    }

    /**
     * Load KPIs with safe fallbacks
     */
    public function loadKpis(): void
    {
        $this->kpisLoading = true;
        
        try {
            $data = $this->calculateKpis();
            $this->kpis = [
                'in_progress' => $data['inProgress'] ?? 0,
                'avg_production_cost' => $data['avgCost'] ?? 0,
                'production_efficiency' => $data['productionEfficiency'] ?? 0,
                'total_production' => $data['totalProduction'] ?? 0,
            ];
        } catch (\Exception $e) {
            logger()->error('Error loading manufacturing KPIs: ' . $e->getMessage());
        } finally {
            $this->kpisLoading = false;
        }
    }

    /**
     * Calculate KPIs
     */
    protected function calculateKpis(): array
    {
        if (!Schema::hasTable('manufacturing_operations')) {
            return [
                'inProgress' => 0,
                'avgCost' => 0,
                'productionEfficiency' => 0,
                'totalProduction' => 0,
            ];
        }

        $userId = Auth::id();
        $dateFilter = $this->getDateFilter();

        // In Progress Operations
        $inProgress = ManufacturingOperation::where('user_id', $userId)
            ->whereIn('status', ['pending', 'in_production', 'quality_check'])
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->count();

        // Average Cost
        $avgCost = (float) ManufacturingOperation::where('user_id', $userId)
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->avg('total_cost');

        // Production Efficiency
        $totalCompletedQuery = ManufacturingOperation::where('user_id', $userId)
            ->where('status', 'completed')
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter));

        $totalCompleted = (clone $totalCompletedQuery)->count();
        $completedOnTime = 0;

        if ($totalCompleted > 0 && Schema::hasColumn('manufacturing_operations', 'expected_completion_date')) {
            $finishCol = 'updated_at';
            if (Schema::hasColumn('manufacturing_operations', 'actual_completion_date')) {
                $finishCol = 'actual_completion_date';
            } elseif (Schema::hasColumn('manufacturing_operations', 'completed_at')) {
                $finishCol = 'completed_at';
            }

            $completedOnTime = (clone $totalCompletedQuery)
                ->whereNotNull('expected_completion_date')
                ->whereNotNull($finishCol)
                ->whereColumn($finishCol, '<=', 'expected_completion_date')
                ->count();
        }

        $productionEfficiency = $totalCompleted > 0
            ? round(($completedOnTime / $totalCompleted) * 100, 1)
            : 0;

        // Total Production
        $totalProduction = 0;
        if (Schema::hasColumn('manufacturing_operations', 'quantity')) {
            $totalProduction = (int) ManufacturingOperation::where('user_id', $userId)
                ->where('status', 'completed')
                ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
                ->sum('quantity');
        } elseif (Schema::hasColumn('manufacturing_operations', 'units_produced')) {
            $totalProduction = (int) ManufacturingOperation::where('user_id', $userId)
                ->where('status', 'completed')
                ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
                ->sum('units_produced');
        } else {
            $totalProduction = $totalCompleted;
        }

        return [
            'inProgress' => $inProgress,
            'avgCost' => round($avgCost, 2),
            'productionEfficiency' => $productionEfficiency,
            'totalProduction' => $totalProduction,
        ];
    }

    /**
     * Load product types
     */
    public function loadProductTypes(): void
    {
        try {
            if (!Schema::hasTable('manufacturing_operations')) {
                $this->productTypes = [];
                return;
            }

            $possibleColumns = ['product_type', 'product_type_name', 'type', 'category', 'product_category'];
            $columnToUse = null;

            foreach ($possibleColumns as $col) {
                if (Schema::hasColumn('manufacturing_operations', $col)) {
                    $columnToUse = $col;
                    break;
                }
            }

            if ($columnToUse) {
                $this->productTypes = ManufacturingOperation::query()
                    ->where('user_id', Auth::id())
                    ->whereNotNull($columnToUse)
                    ->distinct()
                    ->orderBy($columnToUse)
                    ->pluck($columnToUse)
                    ->filter()
                    ->values()
                    ->toArray();
            } else {
                $this->productTypes = [];
            }
        } catch (\Exception $e) {
            logger()->error('Error loading product types: ' . $e->getMessage());
            $this->productTypes = [];
        }
    }

    /**
     * Get recent operations
     */
    public function getRecentOperationsProperty()
    {
        try {
            if (!Schema::hasTable('manufacturing_operations')) {
                return collect();
            }

            $q = ManufacturingOperation::query()->where('user_id', Auth::id());

            // Apply status filter
            if ($this->statusFilter !== 'all') {
                $q->where('status', $this->statusFilter);
            }

            // Apply product type filter
            if ($this->productTypeFilter !== 'all') {
                $possibleColumns = ['product_type', 'product_type_name', 'type', 'category', 'product_category'];
                foreach ($possibleColumns as $col) {
                    if (Schema::hasColumn('manufacturing_operations', $col)) {
                        $q->where($col, $this->productTypeFilter);
                        break;
                    }
                }
            }

            // Apply date filter
            $dateFilter = $this->getDateFilter();
            if ($dateFilter) {
                $q->whereBetween('created_at', $dateFilter);
            }

            return $q->orderBy('created_at', 'desc')->take(10)->get();
        } catch (\Exception $e) {
            logger()->error('Error loading recent operations: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get production by type
     */
    public function getProductionByTypeProperty()
    {
        try {
            if (!Schema::hasTable('manufacturing_operations')) {
                return collect();
            }

            $table = (new ManufacturingOperation)->getTable();

            // Find product type column
            $productTypeCol = null;
            $possibleCols = ['product_type', 'product_type_name', 'type', 'category', 'product_category'];
            foreach ($possibleCols as $col) {
                if (Schema::hasColumn($table, $col)) {
                    $productTypeCol = $col;
                    break;
                }
            }

            if (!$productTypeCol) {
                return collect();
            }

            // Find quantity column
            $qtyCol = Schema::hasColumn($table, 'quantity') ? 'quantity'
                : (Schema::hasColumn($table, 'total_quantity') ? 'total_quantity' : null);

            $q = ManufacturingOperation::query()->where('user_id', Auth::id());

            // Apply date filter
            $dateFilter = $this->getDateFilter();
            if ($dateFilter && is_array($dateFilter)) {
                $q->whereBetween('created_at', $dateFilter);
            }

            // Build query
            $q->selectRaw($productTypeCol.' as product_type')
              ->selectRaw('COUNT(*) as batches_count');

            if ($qtyCol) {
                $q->selectRaw('COALESCE(SUM('.$qtyCol.'),0) as total_quantity');
            } else {
                $q->selectRaw('0 as total_quantity');
            }

            return $q->groupBy($productTypeCol)
                     ->orderByDesc('batches_count')
                     ->take(10)
                     ->get();
        } catch (\Exception $e) {
            logger()->error('Error loading production by type: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get recent calculations
     */
    public function getRecentCalculationsProperty()
    {
        try {
            if (!class_exists(\App\Models\CostCalculation::class)) {
                return collect();
            }

            $table = (new \App\Models\CostCalculation)->getTable();

            $q = \App\Models\CostCalculation::query()->where('user_id', Auth::id());

            if (Schema::hasColumn($table, 'module')) {
                $q->where('module', 'manufacturing');
            }

            return $q->orderByDesc('created_at')->take(5)->get();
        } catch (\Exception $e) {
            logger()->error('Error loading recent calculations: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get date filter
     */
    protected function getDateFilter(): ?array
    {
        return match ($this->dateRange) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => ($this->dateFrom && $this->dateTo)
                ? [Carbon::parse($this->dateFrom)->startOfDay(), Carbon::parse($this->dateTo)->endOfDay()]
                : null,
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    /**
     * Navigate to cost calculator
     */
    public function navigateToCostCalculator()
    {
        return redirect()->route('dashboard.manufacturing.costs');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.manufacturing.manufacturing-dashboard', [
            'summaryStats' => $this->kpis ?? [],
            'kpis' => $this->kpis ?? [],
            'recentOperations' => $this->recentOperations ?? collect(),
            'productionByType' => $this->productionByType ?? collect(),
            'recentCalculations' => $this->recentCalculations ?? collect(),
            'charts' => [],
            'alerts' => [],
            'quickActions' => [],
        ]);
    }
}
