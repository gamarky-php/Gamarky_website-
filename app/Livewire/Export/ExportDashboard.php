<?php

namespace App\Livewire\Export;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ExportOperationDetailed;
use App\Models\ContainerQuote;
use App\Models\ContainerBooking;
use App\Models\CostCalculation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ExportDashboard extends Component
{
    use WithPagination;

    // Filters
    public $statusFilter = 'all';
    public $dateRange = 'this_month';
    public $searchTerm = '';
    public $destinationFilter = 'all';

    // Loading states
    public $loading = false;
    public $kpisLoading = false;

    // Listeners
    protected $listeners = ['refreshDashboard' => '$refresh'];

    /**
     * Mount component
     */
    public function mount()
    {
        // Check authorization
        if (!Gate::allows('dashboard.view')) {
            abort(403, 'ليس لديك صلاحية للوصول إلى لوحة التصدير');
        }
    }

    /**
     * Get KPIs data
     */
    public function getKpisProperty()
    {
        // ✅ Fallback آمن إذا الجدول غير موجود
        if (!\Illuminate\Support\Facades\Schema::hasTable('export_operation_details')) {
            return [
                'in_progress' => 0,
                'avg_shipment_value' => 0,
                'margin_ratio' => 0,
                'total_revenue' => 0,
            ];
        }

        $userId = auth()->id();
        $dateFilter = $this->getDateFilter();

        // طلبات تصدير جارية (In Progress)
        $inProgress = ExportOperationDetailed::where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing', 'in_transit'])
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->count();

        // متوسط قيمة الشحنة
        $avgShipmentValue = ExportOperationDetailed::where('user_id', $userId)
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->avg('total_value') ?? 0;

        // نسبة الهامش (من الحسابات المحفوظة)
        $calculations = CostCalculation::where('user_id', $userId)
            ->where('module', 'export')
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->get();

        $totalMargin = 0;
        $totalSales = 0;
        foreach ($calculations as $calc) {
            $totals = $calc->totals ?? [];
            if (isset($totals['margin_amount']) && isset($totals['selling_price'])) {
                $totalMargin += $totals['margin_amount'];
                $totalSales += $totals['selling_price'];
            }
        }

        $marginRatio = $totalSales > 0 ? round(($totalMargin / $totalSales) * 100, 1) : 0;

        // إجمالي الإيرادات (من عمليات التصدير المكتملة)
        $totalRevenue = ExportOperationDetailed::where('user_id', $userId)
            ->where('status', 'completed')
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->sum('total_value') ?? 0;

        return [
            'in_progress' => $inProgress,
            'avg_shipment_value' => round($avgShipmentValue, 2),
            'margin_ratio' => $marginRatio,
            'total_revenue' => $totalRevenue,
        ];
    }

    /**
     * Get recent export operations
     */
    public function getRecentOperationsProperty()
    {
        // ✅ Fallback آمن
        if (!\Illuminate\Support\Facades\Schema::hasTable('export_operation_details')) {
            return collect([]);
        }

        return ExportOperationDetailed::where('user_id', auth()->id())
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->destinationFilter !== 'all', fn($q) => $q->where('destination_country', $this->destinationFilter))
            ->when($this->searchTerm, fn($q) => $q->where(function($query) {
                $query->where('reference_number', 'like', '%' . $this->searchTerm . '%')
                      ->orWhere('product_description', 'like', '%' . $this->searchTerm . '%');
            }))
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get top destination markets
     */
    public function getTopMarketsProperty()
    {
        // ✅ Fallback آمن
        if (!\Illuminate\Support\Facades\Schema::hasTable('export_operation_details')) {
            return collect([]);
        }

        $dateFilter = $this->getDateFilter();

        return ExportOperationDetailed::where('user_id', auth()->id())
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->select('destination_country', DB::raw('COUNT(*) as shipments_count'), DB::raw('SUM(total_value) as total_value'))
            ->groupBy('destination_country')
            ->orderByDesc('total_value')
            ->take(5)
            ->get();
    }

    /**
     * Get recent container bookings for export
     */
    public function getRecentBookingsProperty()
    {
        // ✅ Fallback آمن للتحقق من الأعمدة
        if (!\Illuminate\Support\Facades\Schema::hasTable('container_bookings')) {
            return collect([]);
        }

        // تحديد عمود المستخدم بناءً على الموجود
        $userColumn = null;
        if (\Illuminate\Support\Facades\Schema::hasColumn('container_bookings', 'shipper_id')) {
            $userColumn = 'shipper_id';
        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_bookings', 'user_id')) {
            $userColumn = 'user_id';
        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_bookings', 'requester_id')) {
            $userColumn = 'requester_id';
        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_bookings', 'customer_id')) {
            $userColumn = 'customer_id';
        }

        // إذا لم يوجد أي عمود مناسب
        if (!$userColumn) {
            return collect([]);
        }

        // ✅ تحديد عمود incoterm بناءً على الموجود في container_quotes
        $incotermColumn = null;
        if (\Illuminate\Support\Facades\Schema::hasTable('container_quotes')) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('container_quotes', 'incoterm')) {
                $incotermColumn = 'incoterm';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_quotes', 'trade_term')) {
                $incotermColumn = 'trade_term';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_quotes', 'incoterms')) {
                $incotermColumn = 'incoterms';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_quotes', 'terms')) {
                $incotermColumn = 'terms';
            } elseif (\Illuminate\Support\Facades\Schema::hasColumn('container_quotes', 'incoterm_code')) {
                $incotermColumn = 'incoterm_code';
            }
        }

        // إذا وُجد عمود incoterm، طبّق الفلتر
        $query = ContainerBooking::where($userColumn, auth()->id());
        
        if ($incotermColumn) {
            $query->whereHas('quote', fn($q) => $q->where($incotermColumn, 'like', '%EX%')); // EXW, FCA, FAS للتصدير
        }

        return $query->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get recent cost calculations
     */
    public function getRecentCalculationsProperty()
    {
        return CostCalculation::where('user_id', auth()->id())
            ->where('module', 'export')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Get date filter range
     */
    protected function getDateFilter()
    {
        return match($this->dateRange) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            default => null,
        };
    }

    /**
     * Get unique destinations for filter
     */
    public function getDestinationsProperty()
    {
        // ✅ Fallback آمن
        if (!\Illuminate\Support\Facades\Schema::hasTable('export_operation_details')) {
            return collect([]);
        }

        return ExportOperationDetailed::where('user_id', auth()->id())
            ->select('destination_country')
            ->distinct()
            ->pluck('destination_country')
            ->filter()
            ->sort()
            ->values();
    }

    /**
     * Update filters
     */
    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedDestinationFilter()
    {
        $this->resetPage();
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function updatedDateRange()
    {
        $this->kpisLoading = true;
    }

    /**
     * Reset all filters
     */
    public function resetFilters()
    {
        $this->statusFilter = 'all';
        $this->destinationFilter = 'all';
        $this->dateRange = 'this_month';
        $this->searchTerm = '';
        $this->resetPage();
    }

    /**
     * Navigate to cost calculator
     */
    public function navigateToCostCalculator()
    {
        return redirect()->route('dashboard.export.costs');
    }

    /**
     * View operation details
     */
    public function viewOperation($operationId)
    {
        $this->dispatch('openOperationModal', operationId: $operationId);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.export.export-dashboard', [
            'kpis' => $this->kpis,
            'recentOperations' => $this->recentOperations,
            'topMarkets' => $this->topMarkets,
            'recentBookings' => $this->recentBookings,
            'recentCalculations' => $this->recentCalculations,
            'destinations' => $this->destinations,
        ])->layout('layouts.dashboard');
    }
}
