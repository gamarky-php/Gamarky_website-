<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\AnalyticsService;
use App\Services\ChartConfigService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Dashboard Home - لوحة التحكم الرئيسية
 * 
 * عرض KPIs رئيسية + 3 رسوم بيانية أساسية
 * 
 * RELIABILITY PROTOCOL:
 * - NEVER crashes due to missing tables/columns
 * - All DB queries protected with schema checks
 * - Returns safe defaults (0/null) when data unavailable
 * - Logs warnings for missing schema elements
 * - Caches schema checks per request for performance
 */
class DashboardHome extends Component
{
    // KPI Cards Data
    public $totalBookings = 0;
    public $avgClearanceTime = 0;
    public $onTimeRate = 0;
    public $subscriptionRevenue = 0;
    public $adsCtr = 0;
    
    // Charts Data
    public $funnelChart = [];
    public $portsChart = [];
    public $containersChart = [];
    
    // Filters
    public $period = 'monthly';
    public $funnelPeriod = 'weekly';
    public $funnelUnits = 4;
    
    // Refresh state
    public $lastUpdate;
    public $autoRefresh = false;

    protected $analytics;
    protected $chartConfig;

    // Memoization cache for schema checks (per request)
    private static $schemaCache = [];

    /**
     * Boot services
     */
    public function boot(AnalyticsService $analytics, ChartConfigService $chartConfig)
    {
        $this->analytics = $analytics;
        $this->chartConfig = $chartConfig;
    }

    /**
     * Mount - تحميل البيانات الأولية
     */
    public function mount()
    {
        $this->loadAllData();
    }

    /**
     * تحميل جميع البيانات (KPIs + Charts)
     */
    public function loadAllData()
    {
        try {
            $this->loadKpiCards();
        } catch (\Throwable $e) {
            Log::error('Dashboard: Failed to load KPI cards', ['error' => $e->getMessage()]);
        }

        try {
            $this->loadCharts();
        } catch (\Throwable $e) {
            Log::error('Dashboard: Failed to load charts', ['error' => $e->getMessage()]);
        }

        $this->lastUpdate = now()->format('H:i:s');
    }

    /**
     * تحميل بيانات كروت KPIs
     */
    protected function loadKpiCards()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        
        // 1. إجمالي الحجوزات هذا الشهر
        try {
            $this->totalBookings = $this->getTotalBookingsThisMonth($startOfMonth, $now);
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load total bookings', ['error' => $e->getMessage()]);
            $this->totalBookings = ['value' => 0, 'trend' => $this->neutralTrend(), 'breakdown' => []];
        }
        
        // 2. متوسط زمن التخليص
        try {
            $this->avgClearanceTime = $this->getAvgClearanceTime();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load avg clearance time', ['error' => $e->getMessage()]);
            $this->avgClearanceTime = ['value' => 0, 'unit' => 'يوم', 'trend' => $this->neutralTrend(), 'color' => 'gray', 'target' => 5];
        }
        
        // 3. نسبة الالتزام بالمواعيد
        try {
            $this->onTimeRate = $this->getOnTimeRate($startOfMonth, $now);
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load on-time rate', ['error' => $e->getMessage()]);
            $this->onTimeRate = ['value' => 0, 'unit' => '%', 'trend' => $this->neutralTrend(), 'color' => 'gray', 'on_time' => 0, 'total' => 0, 'target' => 95];
        }
        
        // 4. إيراد الاشتراكات
        try {
            $this->subscriptionRevenue = $this->getSubscriptionRevenue($startOfMonth, $now);
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load subscription revenue', ['error' => $e->getMessage()]);
            $this->subscriptionRevenue = ['value' => 0, 'formatted' => '0 ريال', 'trend' => $this->neutralTrend(), 'color' => 'green', 'new_subscriptions' => 0];
        }
        
        // 5. CTR للإعلانات
        try {
            $this->adsCtr = $this->getAdsCtr();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load ads CTR', ['error' => $e->getMessage()]);
            $this->adsCtr = $this->getAdsCtrFallback();
        }
    }

    /**
     * تحميل الرسوم البيانية الثلاثة
     */
    protected function loadCharts()
    {
        try {
            $this->loadFunnelChart();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load funnel chart', ['error' => $e->getMessage()]);
            $this->funnelChart = [];
        }

        try {
            $this->loadPortsChart();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load ports chart', ['error' => $e->getMessage()]);
            $this->portsChart = [];
        }

        try {
            $this->loadContainersChart();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to load containers chart', ['error' => $e->getMessage()]);
            $this->containersChart = [];
        }
    }

    // ==================== KPI CARDS CALCULATIONS ====================

    /**
     * إجمالي الحجوزات هذا الشهر
     */
    protected function getTotalBookingsThisMonth(Carbon $start, Carbon $end): array
    {
        $containers = $this->safeCount('container_bookings', $start, $end);
        $trucks = $this->safeCount('truck_bookings', $start, $end);
        $imports = $this->safeCount('import_requests', $start, $end);
        $exports = $this->safeCount('export_shipments', $start, $end);
        
        $total = $containers + $trucks + $imports + $exports;
        
        // احسب الشهر السابق للمقارنة
        $prevStart = $start->copy()->subMonth();
        $prevEnd = $end->copy()->subMonth();
        
        $prevContainers = $this->safeCount('container_bookings', $prevStart, $prevEnd);
        $prevTrucks = $this->safeCount('truck_bookings', $prevStart, $prevEnd);
        $prevImports = $this->safeCount('import_requests', $prevStart, $prevEnd);
        $prevExports = $this->safeCount('export_shipments', $prevStart, $prevEnd);
        
        $prevTotal = $prevContainers + $prevTrucks + $prevImports + $prevExports;
        
        $trend = $this->calculateTrend($total, $prevTotal);
        
        return [
            'value' => $total,
            'trend' => $trend,
            'breakdown' => [
                'حاويات' => $containers,
                'شاحنات' => $trucks,
                'استيراد' => $imports,
                'تصدير' => $exports,
            ],
        ];
    }

    /**
     * متوسط زمن التخليص (بالأيام)
     */
    protected function getAvgClearanceTime(): array
    {
        $table = 'clearance_requests';
        $requiredCols = ['created_at', 'status', 'actual_clearance_date', 'started_at'];
        
        if (!$this->colsExist($table, $requiredCols)) {
            $this->logMissing($table, $requiredCols);
            return [
                'value' => 0,
                'unit' => 'يوم',
                'trend' => $this->neutralTrend(),
                'color' => 'gray',
                'target' => 5,
            ];
        }

        try {
            // آخر 30 يوم
            $startDate = Carbon::now()->subDays(30);
            
            $avgDays = DB::table($table)
                ->whereNotNull('actual_clearance_date')
                ->where('status', 'cleared')
                ->where('created_at', '>=', $startDate)
                ->selectRaw('AVG(DATEDIFF(actual_clearance_date, started_at)) as avg')
                ->value('avg');
            
            $avgDays = round($avgDays ?? 0, 1);
            
            // السابق للمقارنة (30-60 يوم مضت)
            $prevStart = Carbon::now()->subDays(60);
            $prevEnd = Carbon::now()->subDays(30);
            
            $prevAvgDays = DB::table($table)
                ->whereNotNull('actual_clearance_date')
                ->where('status', 'cleared')
                ->whereBetween('created_at', [$prevStart, $prevEnd])
                ->selectRaw('AVG(DATEDIFF(actual_clearance_date, started_at)) as avg')
                ->value('avg');
            
            $prevAvgDays = round($prevAvgDays ?? 0, 1);
            
            $trend = $this->calculateTrend($avgDays, $prevAvgDays, true);
            
            // تحديد اللون بناءً على الأداء
            $color = 'green';
            if ($avgDays > 10) {
                $color = 'red';
            } elseif ($avgDays > 7) {
                $color = 'amber';
            }
            
            return [
                'value' => $avgDays,
                'unit' => 'يوم',
                'trend' => $trend,
                'color' => $color,
                'target' => 5,
            ];
        } catch (\Throwable $e) {
            Log::warning("Dashboard: clearance time calculation failed", ['error' => $e->getMessage()]);
            return [
                'value' => 0,
                'unit' => 'يوم',
                'trend' => $this->neutralTrend(),
                'color' => 'gray',
                'target' => 5,
            ];
        }
    }

    /**
     * نسبة الالتزام بالمواعيد
     */
    protected function getOnTimeRate(Carbon $start, Carbon $end): array
    {
        $containerStats = $this->getOnTimeStatsForTable('container_bookings', $start, $end);
        $truckStats = $this->getOnTimeStatsForTable('truck_bookings', $start, $end);
        
        $totalShipments = ($containerStats['total'] ?? 0) + ($truckStats['total'] ?? 0);
        $onTimeShipments = ($containerStats['on_time'] ?? 0) + ($truckStats['on_time'] ?? 0);
        
        $rate = $totalShipments > 0 ? round(($onTimeShipments / $totalShipments) * 100, 1) : 0;
        
        // الشهر السابق للمقارنة
        $prevStart = $start->copy()->subMonth();
        $prevEnd = $end->copy()->subMonth();
        
        $prevContainerStats = $this->getOnTimeStatsForTable('container_bookings', $prevStart, $prevEnd);
        $prevTruckStats = $this->getOnTimeStatsForTable('truck_bookings', $prevStart, $prevEnd);
        
        $prevTotal = ($prevContainerStats['total'] ?? 0) + ($prevTruckStats['total'] ?? 0);
        $prevOnTime = ($prevContainerStats['on_time'] ?? 0) + ($prevTruckStats['on_time'] ?? 0);
        $prevRate = $prevTotal > 0 ? round(($prevOnTime / $prevTotal) * 100, 1) : 0;
        
        $trend = $this->calculateTrend($rate, $prevRate);
        
        // تحديد اللون
        $color = 'green';
        if ($rate < 75) {
            $color = 'red';
        } elseif ($rate < 90) {
            $color = 'amber';
        }
        
        return [
            'value' => $rate,
            'unit' => '%',
            'trend' => $trend,
            'color' => $color,
            'on_time' => $onTimeShipments,
            'total' => $totalShipments,
            'target' => 95,
        ];
    }

    /**
     * إيراد الاشتراكات (هذا الشهر)
     */
    protected function getSubscriptionRevenue(Carbon $start, Carbon $end): array
    {
        // Try multiple column names with fallback priority
        $sumCols = ['amount', 'price', 'total', 'value', 'paid_amount'];
        $revenue = $this->safeSumWithFallback('subscriptions', $start, $end, $sumCols, ['status' => 'active']);
        
        // الشهر السابق
        $prevStart = $start->copy()->subMonth();
        $prevEnd = $end->copy()->subMonth();
        
        $prevRevenue = $this->safeSumWithFallback('subscriptions', $prevStart, $prevEnd, $sumCols, ['status' => 'active']);
        
        $trend = $this->calculateTrend($revenue, $prevRevenue);
        
        // عدد الاشتراكات الجديدة
        $newSubscriptions = $this->safeCountMemoized('subscriptions', $start, $end);
        
        return [
            'value' => $revenue,
            'formatted' => number_format($revenue, 0) . ' ريال',
            'trend' => $trend,
            'color' => 'green',
            'new_subscriptions' => $newSubscriptions,
        ];
    }

    /**
     * CTR للإعلانات (آخر 30 يوم)
     */
    protected function getAdsCtr(): array
    {
        $table = 'ads_analytics';
        
        if (!$this->tableExists($table)) {
            return $this->getAdsCtrFallback();
        }

        $days = 30;
        $startDate = Carbon::now()->subDays($days);
        
        try {
            // Determine date column (prefer 'date', fallback to 'created_at', or no filter)
            $dateCol = $this->colExists($table, 'date') ? 'date' : 
                       ($this->colExists($table, 'created_at') ? 'created_at' : null);
            
            $hasCtr = $this->colExists($table, 'ctr');
            $hasImpressions = $this->colExists($table, 'impressions');
            $hasClicks = $this->colExists($table, 'clicks');
            
            // Must have at least impressions OR pre-calculated ctr
            if (!$hasImpressions && !$hasCtr) {
                $this->logMissing($table, ['impressions', 'ctr']);
                return $this->getAdsCtrFallback();
            }
            
            // Build query with date filter only if date column exists
            $query = DB::table($table);
            if ($dateCol) {
                $query->where($dateCol, '>=', $startDate);
            }
            
            // Build SELECT based on available columns
            $selectParts = [];
            if ($hasImpressions) $selectParts[] = 'SUM(impressions) as total_impressions';
            if ($hasClicks) $selectParts[] = 'SUM(clicks) as total_clicks';
            if ($hasCtr) $selectParts[] = 'AVG(ctr) as avg_ctr';
            
            if (empty($selectParts)) {
                return $this->getAdsCtrFallback();
            }
            
            $stats = $query->selectRaw(implode(', ', $selectParts))->first();
            
            $totalImpressions = $stats->total_impressions ?? 0;
            $totalClicks = $stats->total_clicks ?? 0;
            
            // Calculate CTR using best available method
            if ($totalImpressions > 0 && $hasClicks) {
                $ctr = round(($totalClicks / $totalImpressions) * 100, 2);
            } elseif ($hasCtr && isset($stats->avg_ctr)) {
                $ctr = round($stats->avg_ctr, 2);
            } else {
                $ctr = 0;
            }
            
            // الفترة السابقة للمقارنة
            $prevStart = Carbon::now()->subDays($days * 2);
            $prevEnd = Carbon::now()->subDays($days);
            
            $prevQuery = DB::table($table);
            if ($dateCol) {
                $prevQuery->whereBetween($dateCol, [$prevStart, $prevEnd]);
            }
            
            $prevStats = $prevQuery->selectRaw(implode(', ', $selectParts))->first();
            
            $prevImpressions = $prevStats->total_impressions ?? 0;
            $prevClicks = $prevStats->total_clicks ?? 0;
            
            if ($prevImpressions > 0 && $hasClicks) {
                $prevCtr = round(($prevClicks / $prevImpressions) * 100, 2);
            } elseif ($hasCtr && isset($prevStats->avg_ctr)) {
                $prevCtr = round($prevStats->avg_ctr, 2);
            } else {
                $prevCtr = 0;
            }
            
            $trend = $this->calculateTrend($ctr, $prevCtr);
            
            // تحديد اللون
            $color = 'green';
            if ($ctr < 2) {
                $color = 'red';
            } elseif ($ctr < 3) {
                $color = 'amber';
            }
            
            return [
                'value' => $ctr,
                'unit' => '%',
                'trend' => $trend,
                'color' => $color,
                'impressions' => $totalImpressions,
                'clicks' => $totalClicks,
                'target' => 3.5,
            ];
        } catch (\Throwable $e) {
            Log::warning("Dashboard: ads CTR calculation failed", ['error' => $e->getMessage()]);
            return $this->getAdsCtrFallback();
        }
    }

    // ==================== CHARTS LOADING ====================

    /**
     * تحميل Funnel Chart (أسبوعي)
     */
    protected function loadFunnelChart()
    {
        $data = $this->analytics->getFunnelData($this->funnelPeriod, $this->funnelUnits, 'container');
        
        $this->funnelChart = $this->chartConfig->lineChart(
            $data['chart']['labels'],
            [
                [
                    'label' => 'بحث',
                    'data' => $data['chart']['datasets'][0]['data'],
                ],
                [
                    'label' => 'عروض أسعار',
                    'data' => $data['chart']['datasets'][1]['data'],
                ],
                [
                    'label' => 'حجوزات',
                    'data' => $data['chart']['datasets'][2]['data'],
                ],
            ],
            [
                'title' => 'قمع التحويل - أسبوعي',
                'yAxisLabel' => 'عدد العمليات',
            ]
        );
        
        $this->funnelChart['summary'] = $data['summary'];
    }

    /**
     * تحميل أداء الموانئ (Bar Chart)
     */
    protected function loadPortsChart()
    {
        $clearanceData = $this->analytics->getClearanceTimeByPort(6);
        
        $labels = $clearanceData['chart']['labels'];
        $data = $clearanceData['chart']['datasets'][0]['data'];
        
        $colors = [];
        foreach ($data as $days) {
            if ($days <= 5) {
                $colors[] = $this->chartConfig->getColor('success');
            } elseif ($days <= 8) {
                $colors[] = $this->chartConfig->getColor('warning');
            } else {
                $colors[] = $this->chartConfig->getColor('danger');
            }
        }
        
        $this->portsChart = $this->chartConfig->barChart(
            $labels,
            [
                [
                    'label' => 'متوسط أيام التخليص',
                    'data' => $data,
                    'backgroundColor' => $colors,
                ],
            ],
            [
                'title' => 'أداء الموانئ - متوسط زمن التخليص',
                'yAxisLabel' => 'أيام',
                'indexAxis' => 'y',
            ]
        );
        
        $this->portsChart['details'] = $clearanceData['details'];
        $this->portsChart['average'] = $clearanceData['average'];
    }

    /**
     * تحميل توزيع أنواع الحاويات (Doughnut Chart)
     */
    protected function loadContainersChart()
    {
        if (!$this->tableExists('container_bookings') || 
            !$this->colExists('container_bookings', 'container_type')) {
            $this->containersChart = $this->chartConfig->doughnutChart(
                ['لا توجد بيانات'],
                [
                    [
                        'label' => 'أنواع الحاويات',
                        'data' => [0],
                        'backgroundColor' => [$this->chartConfig->getColor('gray')],
                    ],
                ],
                ['title' => 'توزيع أنواع الحاويات (آخر 30 يوم)']
            );
            return;
        }

        try {
            $containerTypes = DB::table('container_bookings')
                ->select('container_type', DB::raw('COUNT(*) as count'))
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('container_type')
                ->orderBy('count', 'desc')
                ->get();
            
            $labels = [];
            $data = [];
            $total = 0;
            
            $typeNames = [
                '20ft' => 'حاوية 20 قدم',
                '40ft' => 'حاوية 40 قدم',
                '40hc' => 'حاوية 40 HC',
                'reefer_20' => 'مبردة 20 قدم',
                'reefer_40' => 'مبردة 40 قدم',
                'open_top' => 'مفتوحة السقف',
                'flat_rack' => 'مسطحة',
            ];
            
            foreach ($containerTypes as $type) {
                $labels[] = $typeNames[$type->container_type] ?? $type->container_type;
                $data[] = $type->count;
                $total += $type->count;
            }
            
            if ($total === 0) {
                $this->containersChart = $this->chartConfig->doughnutChart(
                    ['لا توجد بيانات'],
                    [0],
                    ['title' => 'توزيع أنواع الحاويات (آخر 30 يوم)']
                );
                return;
            }
            
            $this->containersChart = $this->chartConfig->doughnutChart(
                $labels,
                $data,
                [
                    'title' => 'توزيع أنواع الحاويات',
                    'cutout' => '65%',
                ]
            );
            
            $this->containersChart['total'] = $total;
            $this->containersChart['percentages'] = array_map(
                fn($val) => round(($val / $total) * 100, 1),
                $data
            );
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Container types chart failed', ['error' => $e->getMessage()]);
            $this->containersChart = [];
        }
    }

    // ==================== SAFE DATABASE HELPERS ====================

    /**
     * Safe table existence check with memoization
     */
    private function tableExists(string $table): bool
    {
        $key = "table:{$table}";
        
        if (array_key_exists($key, self::$schemaCache)) {
            return self::$schemaCache[$key];
        }
        
        try {
            $exists = Schema::hasTable($table);
            self::$schemaCache[$key] = $exists;
            
            if (!$exists) {
                Log::warning("Dashboard: Table missing", ['table' => $table]);
            }
            
            return $exists;
        } catch (\Throwable $e) {
            Log::error("Dashboard: Failed to check table existence", ['table' => $table, 'error' => $e->getMessage()]);
            self::$schemaCache[$key] = false;
            return false;
        }
    }

    /**
     * Check if single column exists with memoization
     */
    private function colExists(string $table, string $col): bool
    {
        $key = "col:{$table}.{$col}";
        
        if (array_key_exists($key, self::$schemaCache)) {
            return self::$schemaCache[$key];
        }
        
        if (!$this->tableExists($table)) {
            self::$schemaCache[$key] = false;
            return false;
        }
        
        try {
            $exists = Schema::hasColumn($table, $col);
            self::$schemaCache[$key] = $exists;
            
            if (!$exists) {
                Log::warning("Dashboard: Column missing", ['table' => $table, 'column' => $col]);
            }
            
            return $exists;
        } catch (\Throwable $e) {
            Log::error("Dashboard: Failed to check column existence", [
                'table' => $table, 
                'column' => $col, 
                'error' => $e->getMessage()
            ]);
            self::$schemaCache[$key] = false;
            return false;
        }
    }
    /**
     * Check if all required columns exist
     */
    private function colsExist(string $table, array $cols): bool
    {
        if (!$this->tableExists($table)) {
            return false;
        }
        
        foreach ($cols as $col) {
            if (!$this->colExists($table, $col)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Safe count with date range filter
     */
    private function safeCount(string $table, Carbon $start, Carbon $end): int
    {
        if (!$this->tableExists($table)) {
            return 0;
        }
        
        if (!$this->colExists($table, 'created_at')) {
            Log::warning("Dashboard: Cannot count without created_at", ['table' => $table]);
            return 0;
        }
        
        try {
            return (int) DB::table($table)
                ->whereBetween('created_at', [$start, $end])
                ->count();
        } catch (\Throwable $e) {
            Log::warning("Dashboard: Count failed", ['table' => $table, 'error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Safe SUM with fallback to multiple column names
     * 
     * @param string $table Table name
     * @param Carbon $start Start date
     * @param Carbon $end End date
     * @param array $sumCols Array of column names to try in priority order
     * @param array $whereEquals Additional WHERE conditions (column => value)
     * @return float|int Sum value or 0 on failure
     */
    private function safeSumWithFallback(string $table, Carbon $start, Carbon $end, array $sumCols, array $whereEquals = []): float|int
    {
        if (!$this->tableExists($table)) {
            return 0;
        }
        
        if (!$this->colExists($table, 'created_at')) {
            Log::warning("Dashboard: Cannot sum without created_at", ['table' => $table]);
            return 0;
        }
        
        // Check status column exists if filtering by status
        $filteredWhereEquals = [];
        foreach ($whereEquals as $col => $val) {
            if ($this->colExists($table, $col)) {
                $filteredWhereEquals[$col] = $val;
            } else {
                Log::warning("Dashboard: WHERE column missing, skipping filter", ['table' => $table, 'column' => $col]);
            }
        }
        
        // Try each sum column in priority order
        foreach ($sumCols as $sumCol) {
            if ($this->colExists($table, $sumCol)) {
                try {
                    $q = DB::table($table)->whereBetween('created_at', [$start, $end]);
                    
                    foreach ($filteredWhereEquals as $col => $val) {
                        $q->where($col, $val);
                    }
                    
                    $result = $q->sum($sumCol);
                    return $result ?? 0;
                } catch (\Throwable $e) {
                    Log::warning("Dashboard: SUM failed, trying next column", [
                        'table' => $table, 
                        'column' => $sumCol, 
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }
        }
        
        $this->logMissing($table, $sumCols);
        return 0;
    }

    /**
     * Get on-time stats for a booking table with fallback logic
     */
    private function getOnTimeStatsForTable(string $table, Carbon $start, Carbon $end): array
    {
        if (!$this->tableExists($table)) {
            return ['total' => 0, 'on_time' => 0];
        }
        
        if (!$this->colExists($table, 'created_at')) {
            return ['total' => 0, 'on_time' => 0];
        }
        
        $hasDeliveryDate = $this->colExists($table, 'delivery_date');
        $hasExpectedDelivery = $this->colExists($table, 'expected_delivery');
        $hasStatus = $this->colExists($table, 'status');
        
        // If both date columns missing, can't calculate on-time rate
        if (!$hasDeliveryDate && !$hasExpectedDelivery) {
            $this->logMissing($table, ['delivery_date', 'expected_delivery']);
            
            // Return total count only, on_time = 0
            $total = $this->safeCount($table, $start, $end);
            return ['total' => $total, 'on_time' => 0];
        }
        
        // If only expected_delivery exists without delivery_date => can't calculate on-time
        if (!$hasDeliveryDate && $hasExpectedDelivery) {
            Log::warning("Dashboard: delivery_date missing, cannot calculate on-time rate", ['table' => $table]);
            $total = $this->safeCount($table, $start, $end);
            return ['total' => $total, 'on_time' => 0];
        }
        
        try {
            // Build safe query based on available columns
            $selectRaw = 'COUNT(*) as total';
            
            if ($hasDeliveryDate && $hasExpectedDelivery && $hasStatus) {
                // Best case: all columns available
                $selectRaw .= ', SUM(CASE WHEN status = "delivered" AND delivery_date <= expected_delivery THEN 1 ELSE 0 END) as on_time';
            } elseif ($hasDeliveryDate && $hasExpectedDelivery) {
                // No status filter, just compare dates
                $selectRaw .= ', SUM(CASE WHEN delivery_date <= expected_delivery THEN 1 ELSE 0 END) as on_time';
            } else {
                // Fallback: can't calculate on-time
                $selectRaw .= ', 0 as on_time';
            }
            
            $stats = DB::table($table)
                ->whereBetween('created_at', [$start, $end])
                ->selectRaw($selectRaw)
                ->first();
            
            return [
                'total' => $stats->total ?? 0,
                'on_time' => $stats->on_time ?? 0,
            ];
        } catch (\Throwable $e) {
            Log::warning("Dashboard: On-time rate calculation failed", [
                'table' => $table, 
                'error' => $e->getMessage()
            ]);
            return ['total' => 0, 'on_time' => 0];
        }
    }

    // ==================== UTILITY HELPERS ====================

    /**
     * حساب الاتجاه trend
     */
    protected function calculateTrend($current, $previous, $lowerIsBetter = false): array
    {
        if ($previous == 0) {
            return $this->neutralTrend();
        }
        
        $change = $current - $previous;
        $percentage = abs(round(($change / $previous) * 100, 1));
        
        if ($change > 0) {
            $direction = 'up';
            $color = $lowerIsBetter ? 'red' : 'green';
        } elseif ($change < 0) {
            $direction = 'down';
            $color = $lowerIsBetter ? 'green' : 'red';
        } else {
            return $this->neutralTrend();
        }
        
        return [
            'direction' => $direction,
            'percentage' => $percentage,
            'color' => $color,
        ];
    }

    /**
     * Neutral trend fallback
     */
    private function neutralTrend(): array
    {
        return [
            'direction' => 'neutral',
            'percentage' => 0,
            'color' => 'gray',
        ];
    }

    /**
     * Get ads CTR fallback response
     */
    private function getAdsCtrFallback(): array
    {
        return [
            'value' => 0,
            'unit' => '%',
            'trend' => $this->neutralTrend(),
            'color' => 'purple',
            'impressions' => 0,
            'clicks' => 0,
            'target' => 3.5,
        ];
    }

    /**
     * Log missing schema elements
     */
    private function logMissing(string $table, array $cols): void
    {
        Log::warning("Dashboard KPI skipped: missing columns", [
            'table' => $table,
            'columns' => implode(', ', $cols),
        ]);
    }

    // ==================== LIVEWIRE ACTIONS ====================

    /**
     * تحديث البيانات يدوياً
     */
    public function refresh()
    {
        // Clear schema cache on manual refresh
        self::$schemaCache = [];
        
        $this->loadAllData();
        $this->dispatch('dashboard-refreshed');
    }

    /**
     * تبديل التحديث التلقائي
     */
    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    /**
     * تغيير فترة Funnel
     */
    public function updateFunnelPeriod($period)
    {
        $this->funnelPeriod = $period;
        try {
            $this->loadFunnelChart();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to update funnel period', ['error' => $e->getMessage()]);
            $this->funnelChart = [];
        }
    }

    /**
     * تغيير عدد وحدات Funnel
     */
    public function updateFunnelUnits($units)
    {
        $this->funnelUnits = $units;
        try {
            $this->loadFunnelChart();
        } catch (\Throwable $e) {
            Log::warning('Dashboard: Failed to update funnel units', ['error' => $e->getMessage()]);
            $this->funnelChart = [];
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.dashboard.dashboard-home')
            ->layout('layouts.dashboard');
    }
}
