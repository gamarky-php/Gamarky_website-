<?php

namespace App\Livewire\Import;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

use App\Models\ImportOperation;
use App\Models\ContainerQuote;

class ImportDashboard extends Component
{
    use WithPagination;

    public $dateRange = 'month'; // today|week|month|year|custom
    public $dateFrom;
    public $dateTo;
    
    // ✅ إضافة المتغيرات المطلوبة للـ Blade
    public bool $kpisLoading = true;
    public array $kpis = [
        'in_progress' => 0,
        'avg_lead_time' => 0,
        'total_quotes' => 0,
        'accepted_quotes' => 0,
        'acceptance_rate' => 0,
        'total_costs' => 0,
    ];
    
    // متغيرات إضافية للصفحة
    public $searchTerm = '';
    public $statusFilter = 'all';
    public $dateRangeFilter = 'this_month';

    public function mount()
    {
        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo   = now()->endOfMonth()->toDateString();
        
        // ✅ تحميل الـ KPIs عند التهيئة
        $this->loadKpis();
    }
    
    /**
     * تحميل الـ KPIs من قاعدة البيانات
     */
    public function loadKpis(): void
    {
        $this->kpisLoading = true;
        
        try {
            $this->kpis = $this->getKpisProperty();
        } catch (\Exception $e) {
            // في حالة وجود خطأ، نبقي القيم الافتراضية
            logger()->error('Error loading KPIs: ' . $e->getMessage());
        } finally {
            $this->kpisLoading = false;
        }
    }
    
    /**
     * تحديد البيانات الواردة في render
     */
    public function getRecentOperationsProperty()
    {
        return ImportOperation::where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();
    }
    
    public function getRecentCalculationsProperty()
    {
        // لو في جدول للحسابات، اجلبه. وإلا أرجع مصفوفة فارغة
        if (!Schema::hasTable('cost_calculations')) {
            return collect([]);
        }
        
        return \App\Models\CostCalculation::where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();
    }

    protected function getDateFilter(): ?array
    {
        return match ($this->dateRange) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week'  => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year'  => [now()->startOfYear(), now()->endOfYear()],
            'custom' => ($this->dateFrom && $this->dateTo)
                ? [Carbon::parse($this->dateFrom)->startOfDay(), Carbon::parse($this->dateTo)->endOfDay()]
                : null,
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    protected function getKpisProperty(): array
    {
        $userId = Auth::id();
        $dateFilter = $this->getDateFilter();

        // ✅ عمليات جارية
        $inProgress = ImportOperation::where('user_id', $userId)
            ->whereIn('status', ['pending', 'in_transit', 'at_port'])
            ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
            ->count();

        // ✅ متوسط زمن الوصول (بالأيام)
        $finishCol = null;

        if (Schema::hasColumn('import_operations', 'actual_arrival_date')) {
            $finishCol = 'actual_arrival_date';
        } elseif (Schema::hasColumn('import_operations', 'arrival_date')) {
            $finishCol = 'arrival_date';
        } elseif (Schema::hasColumn('import_operations', 'completed_at')) {
            $finishCol = 'completed_at';
        } else {
            $finishCol = 'updated_at';
        }

        $avgLeadTime = 0;
        if (Schema::hasColumn('import_operations', $finishCol) && Schema::hasColumn('import_operations', 'created_at')) {
            $avgLeadTime = (float) ImportOperation::where('user_id', $userId)
                ->where('status', 'completed')
                ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
                ->whereNotNull($finishCol)
                ->avg(DB::raw("DATEDIFF($finishCol, created_at)")) ?? 0;
        }

        // ✅ نسبة قبول العروض
        $totalQuotes = 0;
        $acceptedQuotes = 0;

        if (Schema::hasTable('container_quotes')) {
            $totalQuotes = ContainerQuote::where('requester_id', $userId)
                ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
                ->count();

            $acceptedQuotes = ContainerQuote::where('requester_id', $userId)
                ->where('status', 'accepted')
                ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
                ->count();
        }

        $quoteAcceptanceRate = $totalQuotes > 0 ? round(($acceptedQuotes / $totalQuotes) * 100, 1) : 0;
        
        // ✅ إجمالي التكاليف (يمكن حسابها من عمود total_cost إذا موجود)
        $totalCosts = 0;
        if (Schema::hasColumn('import_operations', 'total_cost')) {
            $totalCosts = (float) ImportOperation::where('user_id', $userId)
                ->when($dateFilter, fn($q) => $q->whereBetween('created_at', $dateFilter))
                ->sum('total_cost') ?? 0;
        }

        return [
            'in_progress' => $inProgress,
            'avg_lead_time' => round($avgLeadTime, 2),
            'total_quotes' => $totalQuotes,
            'accepted_quotes' => $acceptedQuotes,
            'acceptance_rate' => $quoteAcceptanceRate,
            'total_costs' => $totalCosts,
        ];
    }

    public function render()
    {
        return view('livewire.import.import-dashboard', [
            'recentOperations' => $this->recentOperations,
            'recentCalculations' => $this->recentCalculations,
        ]);
    }
}
