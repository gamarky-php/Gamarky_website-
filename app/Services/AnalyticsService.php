<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Analytics Service - خدمة التحليلات والإحصائيات
 * 
 * توفر بيانات جاهزة للرسم البياني وتحليل الأداء عبر جميع أقسام النظام
 */
class AnalyticsService
{
    protected $chartConfig;
    protected $cacheService;

    public function __construct(ChartConfigService $chartConfig, CacheService $cacheService)
    {
        $this->chartConfig = $chartConfig;
        $this->cacheService = $cacheService;
    }
    /**
     * Get date range based on period
     * 
     * @param string $period daily|weekly|monthly|yearly
     * @param int $units Number of periods to go back
     * @return array [start_date, end_date, group_format]
     */
    protected function getDateRange(string $period = 'daily', int $units = 30): array
    {
        $now = Carbon::now();
        
        switch ($period) {
            case 'daily':
                $start = $now->copy()->subDays($units);
                $groupFormat = '%Y-%m-%d';
                $dateFormat = 'Y-m-d';
                break;
            
            case 'weekly':
                $start = $now->copy()->subWeeks($units);
                $groupFormat = '%Y-%u'; // Year-Week
                $dateFormat = 'Y-W';
                break;
            
            case 'monthly':
                $start = $now->copy()->subMonths($units);
                $groupFormat = '%Y-%m';
                $dateFormat = 'Y-m';
                break;
            
            case 'yearly':
                $start = $now->copy()->subYears($units);
                $groupFormat = '%Y';
                $dateFormat = 'Y';
                break;
            
            default:
                $start = $now->copy()->subDays(30);
                $groupFormat = '%Y-%m-%d';
                $dateFormat = 'Y-m-d';
        }
        
        return [
            'start' => $start,
            'end' => $now,
            'group_format' => $groupFormat,
            'date_format' => $dateFormat,
        ];
    }

    /**
     * Fill missing dates in timeline data
     * 
     * @param array $data Data with date keys
     * @param string $start Start date
     * @param string $end End date
     * @param string $format Date format
     * @param mixed $default Default value for missing dates
     * @return array
     */
    protected function fillMissingDates(array $data, string $start, string $end, string $format = 'Y-m-d', $default = 0): array
    {
        $result = [];
        $current = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        
        while ($current <= $endDate) {
            $key = $current->format($format);
            $result[$key] = $data[$key] ?? $default;
            $current->addDay();
        }
        
        return $result;
    }

    /**
     * Calculate percentage change
     * 
     * @param float $current Current value
     * @param float $previous Previous value
     * @return array [percentage, trend]
     */
    protected function calculateChange(float $current, float $previous): array
    {
        if ($previous == 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'trend' => $current > 0 ? 'up' : 'neutral',
            ];
        }
        
        $change = (($current - $previous) / $previous) * 100;
        
        return [
            'percentage' => round($change, 2),
            'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral'),
        ];
    }

    /**
     * Format chart data for Chart.js
     * 
     * @param array $labels X-axis labels
     * @param array $datasets Array of datasets [label, data, color]
     * @param string $type Chart type (line, bar, pie, doughnut)
     * @return array
     */
    protected function formatChartData(array $labels, array $datasets, string $type = 'line'): array
    {
        $colors = [
            'indigo' => ['bg' => 'rgba(99, 102, 241, 0.2)', 'border' => 'rgb(99, 102, 241)'],
            'green' => ['bg' => 'rgba(34, 197, 94, 0.2)', 'border' => 'rgb(34, 197, 94)'],
            'blue' => ['bg' => 'rgba(59, 130, 246, 0.2)', 'border' => 'rgb(59, 130, 246)'],
            'red' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'border' => 'rgb(239, 68, 68)'],
            'amber' => ['bg' => 'rgba(245, 158, 11, 0.2)', 'border' => 'rgb(245, 158, 11)'],
            'purple' => ['bg' => 'rgba(168, 85, 247, 0.2)', 'border' => 'rgb(168, 85, 247)'],
            'emerald' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'border' => 'rgb(16, 185, 129)'],
        ];
        
        $formattedDatasets = [];
        
        foreach ($datasets as $index => $dataset) {
            $color = $colors[$dataset['color'] ?? 'indigo'];
            
            $formattedDatasets[] = [
                'label' => $dataset['label'],
                'data' => $dataset['data'],
                'backgroundColor' => $color['bg'],
                'borderColor' => $color['border'],
                'borderWidth' => 2,
                'tension' => 0.4, // Smooth curves for line charts
                'fill' => true,
            ];
        }
        
        return [
            'type' => $type,
            'labels' => $labels,
            'datasets' => $formattedDatasets,
        ];
    }

    /**
     * Calculate average from array
     * 
     * @param array $values
     * @return float
     */
    protected function average(array $values): float
    {
        if (empty($values)) {
            return 0;
        }
        
        return round(array_sum($values) / count($values), 2);
    }

    /**
     * Get top N items by value
     * 
     * @param array $data Associative array
     * @param int $limit
     * @param bool $descending
     * @return array
     */
    protected function getTopItems(array $data, int $limit = 10, bool $descending = true): array
    {
        if ($descending) {
            arsort($data);
        } else {
            asort($data);
        }
        
        return array_slice($data, 0, $limit, true);
    }

    /**
     * Calculate conversion rate
     * 
     * @param int $conversions
     * @param int $total
     * @return float Percentage
     */
    protected function conversionRate(int $conversions, int $total): float
    {
        if ($total == 0) {
            return 0;
        }
        
        return round(($conversions / $total) * 100, 2);
    }

    /**
     * Get color based on performance threshold
     * 
     * @param float $value Current value
     * @param float $good Good threshold
     * @param float $average Average threshold
     * @return string
     */
    protected function getPerformanceColor(float $value, float $good, float $average): string
    {
        if ($value >= $good) {
            return 'green';
        } elseif ($value >= $average) {
            return 'amber';
        } else {
            return 'red';
        }
    }

    /**
     * Format number with K, M, B suffixes
     * 
     * @param int|float $number
     * @param int $decimals
     * @return string
     */
    protected function formatNumber($number, int $decimals = 1): string
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, $decimals) . 'B';
        } elseif ($number >= 1000000) {
            return round($number / 1000000, $decimals) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, $decimals) . 'K';
        }
        
        return (string) $number;
    }

    // ==================== FUNNEL ANALYTICS ====================

    /**
     * Get conversion funnel data: Search → Quote Selection → Booking
     * 
     * @param string $period daily|weekly|monthly
     * @param int $units Number of periods
     * @param string $section container|truck|import|export
     * @return array
     */
    public function getFunnelData(string $period = 'daily', int $units = 30, string $section = 'container'): array
    {
        $dateRange = $this->getDateRange($period, $units);
        
        // Get searches (analytics_events table)
        $searches = DB::table('analytics_events')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateRange['group_format']}') as period"),
                DB::raw('COUNT(*) as count')
            )
            ->where('event_type', 'search')
            ->where('section', $section)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

        // Get quote views
        $quoteViews = DB::table('analytics_events')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateRange['group_format']}') as period"),
                DB::raw('COUNT(DISTINCT session_id) as count')
            )
            ->where('event_type', 'quote_view')
            ->where('section', $section)
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

        // Get bookings based on section
        $bookingTable = $section === 'container' ? 'container_bookings' : 
                       ($section === 'truck' ? 'truck_bookings' : 
                       ($section === 'import' ? 'import_requests' : 'export_shipments'));
        
        $bookings = DB::table($bookingTable)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '{$dateRange['group_format']}') as period"),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupBy('period')
            ->pluck('count', 'period')
            ->toArray();

        // Prepare chart data
        $labels = [];
        $searchData = [];
        $quoteData = [];
        $bookingData = [];
        
        $current = $dateRange['start']->copy();
        while ($current <= $dateRange['end']) {
            $key = $current->format($dateRange['date_format']);
            $labels[] = $key;
            $searchData[] = $searches[$key] ?? 0;
            $quoteData[] = $quoteViews[$key] ?? 0;
            $bookingData[] = $bookings[$key] ?? 0;
            
            if ($period === 'daily') {
                $current->addDay();
            } elseif ($period === 'weekly') {
                $current->addWeek();
            } else {
                $current->addMonth();
            }
        }

        // Calculate overall conversion rates
        $totalSearches = array_sum($searchData);
        $totalQuotes = array_sum($quoteData);
        $totalBookings = array_sum($bookingData);
        
        $searchToQuote = $this->conversionRate($totalQuotes, $totalSearches);
        $quoteToBooking = $this->conversionRate($totalBookings, $totalQuotes);
        $overallConversion = $this->conversionRate($totalBookings, $totalSearches);

        return [
            'chart' => $this->formatChartData($labels, [
                ['label' => 'بحث', 'data' => $searchData, 'color' => 'indigo'],
                ['label' => 'عروض أسعار', 'data' => $quoteData, 'color' => 'blue'],
                ['label' => 'حجوزات', 'data' => $bookingData, 'color' => 'green'],
            ], 'line'),
            'summary' => [
                'total_searches' => $totalSearches,
                'total_quotes' => $totalQuotes,
                'total_bookings' => $totalBookings,
                'search_to_quote' => $searchToQuote,
                'quote_to_booking' => $quoteToBooking,
                'overall_conversion' => $overallConversion,
            ],
            'period' => $period,
            'section' => $section,
        ];
    }

    // ==================== CLEARANCE TIME ANALYTICS ====================

    /**
     * Get average clearance time by port
     * 
     * @param int $limit Top N ports
     * @return array
     */
    public function getClearanceTimeByPort(int $limit = 10): array
    {
        // Get clearance time data from clearance_requests
        $portData = DB::table('clearance_requests')
            ->select(
                'port',
                DB::raw('AVG(DATEDIFF(actual_clearance_date, started_at)) as avg_days'),
                DB::raw('COUNT(*) as total_clearances'),
                DB::raw('MIN(DATEDIFF(actual_clearance_date, started_at)) as min_days'),
                DB::raw('MAX(DATEDIFF(actual_clearance_date, started_at)) as max_days')
            )
            ->whereNotNull('actual_clearance_date')
            ->where('status', 'cleared')
            ->groupBy('port')
            ->orderBy('avg_days', 'desc')
            ->limit($limit)
            ->get();

        $labels = [];
        $avgDays = [];
        $counts = [];
        $colors = [];
        
        foreach ($portData as $port) {
            $labels[] = $port->port;
            $avgDays[] = round($port->avg_days, 1);
            $counts[] = $port->total_clearances;
            
            // Color based on performance (green < 5 days, amber 5-10, red > 10)
            $colors[] = $this->getPerformanceColor($port->avg_days, 5, 10);
        }

        // Get trend data (last 6 months)
        $trendData = DB::table('clearance_requests')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('AVG(DATEDIFF(actual_clearance_date, started_at)) as avg_days')
            )
            ->whereNotNull('actual_clearance_date')
            ->where('status', 'cleared')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('avg_days', 'month')
            ->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'متوسط أيام التخليص',
                    'data' => $avgDays,
                    'backgroundColor' => array_map(function($color) {
                        $colorMap = [
                            'green' => 'rgba(34, 197, 94, 0.7)',
                            'amber' => 'rgba(245, 158, 11, 0.7)',
                            'red' => 'rgba(239, 68, 68, 0.7)',
                        ];
                        return $colorMap[$color];
                    }, $colors),
                ]],
            ],
            'ports' => $portData->map(function($port) {
                return [
                    'port' => $port->port,
                    'avg_days' => round($port->avg_days, 1),
                    'total_clearances' => $port->total_clearances,
                    'min_days' => $port->min_days,
                    'max_days' => $port->max_days,
                    'performance' => $this->getPerformanceColor($port->avg_days, 5, 10),
                ];
            })->toArray(),
            'trend' => $trendData,
            'overall_avg' => round(DB::table('clearance_requests')
                ->whereNotNull('actual_clearance_date')
                ->where('status', 'cleared')
                ->avg(DB::raw('DATEDIFF(actual_clearance_date, started_at)')), 1),
        ];
    }

    // ==================== DOCUMENT ERROR ANALYTICS ====================

    /**
     * Get document error rate by port and broker
     * 
     * @param string $groupBy port|broker
     * @return array
     */
    public function getDocumentErrorRate(string $groupBy = 'port'): array
    {
        $groupColumn = $groupBy === 'broker' ? 'broker_id' : 'port';
        
        // Get error data from clearance_timeline
        $errorData = DB::table('clearance_timeline as ct')
            ->join('clearance_requests as cr', 'ct.clearance_request_id', '=', 'cr.id')
            ->select(
                "cr.{$groupColumn} as group_key",
                DB::raw('COUNT(*) as total_events'),
                DB::raw("SUM(CASE WHEN ct.status = 'documents_rejected' THEN 1 ELSE 0 END) as errors"),
                DB::raw("SUM(CASE WHEN ct.status = 'documents_rejected' THEN 1 ELSE 0 END) / COUNT(*) * 100 as error_rate")
            )
            ->groupBy('group_key')
            ->orderBy('error_rate', 'desc')
            ->limit(15)
            ->get();

        // If grouping by broker, get broker names
        if ($groupBy === 'broker') {
            $brokerIds = $errorData->pluck('group_key')->unique();
            $brokers = DB::table('customs_brokers')
                ->whereIn('id', $brokerIds)
                ->pluck('company_name', 'id');
            
            foreach ($errorData as $item) {
                $item->group_key = $brokers[$item->group_key] ?? 'Unknown';
            }
        }

        $labels = $errorData->pluck('group_key')->toArray();
        $errorRates = $errorData->pluck('error_rate')->map(function($rate) {
            return round($rate, 2);
        })->toArray();

        // Get error categories breakdown
        $errorCategories = DB::table('clearance_timeline')
            ->select(
                'notes',
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'documents_rejected')
            ->whereNotNull('notes')
            ->groupBy('notes')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'نسبة الأخطاء (%)',
                    'data' => $errorRates,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                ]],
            ],
            'data' => $errorData->map(function($item) {
                return [
                    'name' => $item->group_key,
                    'total_events' => $item->total_events,
                    'errors' => $item->errors,
                    'error_rate' => round($item->error_rate, 2),
                ];
            })->toArray(),
            'error_categories' => $errorCategories->pluck('count', 'notes')->toArray(),
            'overall_error_rate' => round(
                DB::table('clearance_timeline')
                    ->where('status', 'documents_rejected')
                    ->count() / 
                DB::table('clearance_timeline')->count() * 100,
                2
            ),
        ];
    }

    // ==================== SLA COMPLIANCE ANALYTICS ====================

    /**
     * Get customs broker SLA compliance tracking
     * 
     * @param int $limit Top N brokers
     * @return array
     */
    public function getBrokerSLACompliance(int $limit = 10): array
    {
        // Get SLA data from sla_tracking table
        $brokerData = DB::table('sla_tracking as st')
            ->join('customs_brokers as cb', 'st.broker_id', '=', 'cb.id')
            ->join('clearance_requests as cr', 'st.clearance_request_id', '=', 'cr.id')
            ->select(
                'cb.id as broker_id',
                'cb.company_name',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN st.met_sla = 1 THEN 1 ELSE 0 END) as met_sla_count'),
                DB::raw('SUM(CASE WHEN st.met_sla = 1 THEN 1 ELSE 0 END) / COUNT(*) * 100 as sla_compliance'),
                DB::raw('AVG(st.actual_hours) as avg_actual_hours'),
                DB::raw('AVG(st.sla_hours) as avg_sla_hours'),
                DB::raw('AVG(st.actual_hours - st.sla_hours) as avg_variance')
            )
            ->groupBy('cb.id', 'cb.company_name')
            ->orderBy('sla_compliance', 'desc')
            ->limit($limit)
            ->get();

        $labels = $brokerData->pluck('company_name')->toArray();
        $compliance = $brokerData->pluck('sla_compliance')->map(fn($v) => round($v, 1))->toArray();
        
        // Monthly SLA trend (last 6 months)
        $monthlyTrend = DB::table('sla_tracking')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(CASE WHEN met_sla = 1 THEN 1 ELSE 0 END) / COUNT(*) * 100 as compliance')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'نسبة الالتزام بـ SLA (%)',
                    'data' => $compliance,
                    'backgroundColor' => array_map(function($val) {
                        return $val >= 90 ? 'rgba(34, 197, 94, 0.7)' : 
                               ($val >= 75 ? 'rgba(245, 158, 11, 0.7)' : 'rgba(239, 68, 68, 0.7)');
                    }, $compliance),
                ]],
            ],
            'brokers' => $brokerData->map(function($broker) {
                return [
                    'broker_id' => $broker->broker_id,
                    'company_name' => $broker->company_name,
                    'total_requests' => $broker->total_requests,
                    'met_sla_count' => $broker->met_sla_count,
                    'sla_compliance' => round($broker->sla_compliance, 1),
                    'avg_actual_hours' => round($broker->avg_actual_hours, 1),
                    'avg_sla_hours' => round($broker->avg_sla_hours, 1),
                    'avg_variance' => round($broker->avg_variance, 1),
                    'performance' => $broker->sla_compliance >= 90 ? 'excellent' : 
                                   ($broker->sla_compliance >= 75 ? 'good' : 'poor'),
                ];
            })->toArray(),
            'monthly_trend' => $monthlyTrend->pluck('compliance', 'month')->map(fn($v) => round($v, 1))->toArray(),
            'overall_compliance' => round(
                DB::table('sla_tracking')->where('met_sla', 1)->count() / 
                DB::table('sla_tracking')->count() * 100,
                1
            ),
        ];
    }

    // ==================== SHIPPING PERFORMANCE ANALYTICS ====================

    /**
     * Get shipping performance metrics
     * 
     * @param string $type container|truck
     * @return array
     */
    public function getShippingPerformance(string $type = 'container'): array
    {
        $table = $type === 'container' ? 'container_bookings' : 'truck_bookings';
        
        // Get on-time delivery performance
        $performance = DB::table($table)
            ->select(
                'carrier',
                DB::raw('COUNT(*) as total_deliveries'),
                DB::raw('SUM(CASE WHEN status = "delivered" AND delivered_at <= estimated_delivery THEN 1 ELSE 0 END) as on_time'),
                DB::raw('SUM(CASE WHEN delivered_at > estimated_delivery THEN DATEDIFF(delivered_at, estimated_delivery) ELSE 0 END) as total_delay_days'),
                DB::raw('AVG(CASE WHEN delivered_at > estimated_delivery THEN DATEDIFF(delivered_at, estimated_delivery) ELSE 0 END) as avg_delay')
            )
            ->whereIn('status', ['delivered', 'completed'])
            ->whereNotNull('delivered_at')
            ->groupBy('carrier')
            ->orderBy('on_time', 'desc')
            ->limit(10)
            ->get();

        $labels = $performance->pluck('carrier')->toArray();
        $onTimePercentages = $performance->map(function($p) {
            return round(($p->on_time / $p->total_deliveries) * 100, 1);
        })->toArray();
        $avgDelays = $performance->pluck('avg_delay')->map(fn($v) => round($v, 1))->toArray();

        // Monthly performance trend
        $monthlyPerf = DB::table($table)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(CASE WHEN delivered_at <= estimated_delivery THEN 1 ELSE 0 END) / COUNT(*) * 100 as on_time_pct')
            )
            ->whereIn('status', ['delivered', 'completed'])
            ->whereNotNull('delivered_at')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'التسليم في الموعد (%)',
                        'data' => $onTimePercentages,
                        'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    ],
                ],
            ],
            'delay_chart' => [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'متوسط التأخير (أيام)',
                        'data' => $avgDelays,
                        'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    ],
                ],
            ],
            'carriers' => $performance->map(function($p) {
                return [
                    'carrier' => $p->carrier,
                    'total_deliveries' => $p->total_deliveries,
                    'on_time' => $p->on_time,
                    'on_time_percentage' => round(($p->on_time / $p->total_deliveries) * 100, 1),
                    'avg_delay_days' => round($p->avg_delay, 1),
                    'performance' => ($p->on_time / $p->total_deliveries) >= 0.9 ? 'excellent' : 
                                   (($p->on_time / $p->total_deliveries) >= 0.75 ? 'good' : 'poor'),
                ];
            })->toArray(),
            'monthly_trend' => $monthlyPerf->pluck('on_time_pct', 'month')->map(fn($v) => round($v, 1))->toArray(),
            'overall_on_time' => round(
                DB::table($table)
                    ->whereIn('status', ['delivered', 'completed'])
                    ->whereNotNull('delivered_at')
                    ->whereRaw('delivered_at <= estimated_delivery')
                    ->count() / 
                DB::table($table)
                    ->whereIn('status', ['delivered', 'completed'])
                    ->whereNotNull('delivered_at')
                    ->count() * 100,
                1
            ),
        ];
    }

    // ==================== ADS PERFORMANCE ANALYTICS ====================

    /**
     * Get advertisements performance metrics
     * 
     * @param int $days Number of days to analyze
     * @return array
     */
    public function getAdsPerformance(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        // Get ad performance data
        $adsData = DB::table('ads_analytics as aa')
            ->join('dashboard_ads as da', 'aa.ad_id', '=', 'da.id')
            ->select(
                'da.id',
                'da.title',
                DB::raw('SUM(aa.impressions) as total_impressions'),
                DB::raw('SUM(aa.clicks) as total_clicks'),
                DB::raw('SUM(aa.conversions) as total_conversions'),
                DB::raw('SUM(aa.clicks) / SUM(aa.impressions) * 100 as ctr'),
                DB::raw('SUM(aa.conversions) / SUM(aa.clicks) * 100 as conversion_rate'),
                DB::raw('SUM(aa.revenue) as total_revenue'),
                DB::raw('SUM(aa.cost) as total_cost'),
                DB::raw('(SUM(aa.revenue) - SUM(aa.cost)) / SUM(aa.cost) * 100 as roi')
            )
            ->where('aa.date', '>=', $startDate)
            ->groupBy('da.id', 'da.title')
            ->orderBy('total_impressions', 'desc')
            ->limit(10)
            ->get();

        $labels = $adsData->pluck('title')->toArray();
        $ctrs = $adsData->pluck('ctr')->map(fn($v) => round($v, 2))->toArray();
        $conversionRates = $adsData->pluck('conversion_rate')->map(fn($v) => round($v, 2))->toArray();

        // Daily trend
        $dailyTrend = DB::table('ads_analytics')
            ->select(
                'date',
                DB::raw('SUM(impressions) as impressions'),
                DB::raw('SUM(clicks) as clicks'),
                DB::raw('SUM(conversions) as conversions')
            )
            ->where('date', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'chart' => [
                'type' => 'bar',
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'معدل النقر (CTR %)',
                        'data' => $ctrs,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                    ],
                    [
                        'label' => 'معدل التحويل (%)',
                        'data' => $conversionRates,
                        'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    ],
                ],
            ],
            'ads' => $adsData->map(function($ad) {
                return [
                    'ad_id' => $ad->id,
                    'title' => $ad->title,
                    'impressions' => $ad->total_impressions,
                    'clicks' => $ad->total_clicks,
                    'conversions' => $ad->total_conversions,
                    'ctr' => round($ad->ctr, 2),
                    'conversion_rate' => round($ad->conversion_rate, 2),
                    'revenue' => round($ad->total_revenue, 2),
                    'cost' => round($ad->total_cost, 2),
                    'roi' => round($ad->roi, 2),
                    'performance' => $ad->ctr >= 2 ? 'excellent' : ($ad->ctr >= 1 ? 'good' : 'poor'),
                ];
            })->toArray(),
            'daily_trend' => $dailyTrend->pluck('impressions', 'date')->toArray(),
            'summary' => [
                'total_impressions' => $adsData->sum('total_impressions'),
                'total_clicks' => $adsData->sum('total_clicks'),
                'total_conversions' => $adsData->sum('total_conversions'),
                'overall_ctr' => round($adsData->sum('total_clicks') / $adsData->sum('total_impressions') * 100, 2),
                'overall_conversion_rate' => round($adsData->sum('total_conversions') / $adsData->sum('total_clicks') * 100, 2),
                'total_revenue' => round($adsData->sum('total_revenue'), 2),
                'total_cost' => round($adsData->sum('total_cost'), 2),
                'overall_roi' => round(($adsData->sum('total_revenue') - $adsData->sum('total_cost')) / $adsData->sum('total_cost') * 100, 2),
            ],
        ];
    }

    // ==================== CUSTOMER SATISFACTION ANALYTICS ====================

    /**
     * Get customer satisfaction metrics (CSAT, NPS)
     * 
     * @param int $days Number of days to analyze
     * @return array
     */
    public function getCustomerSatisfaction(int $days = 90): array
    {
        $startDate = Carbon::now()->subDays($days);
        
        // Get feedback data
        $feedbackData = DB::table('customer_feedback as cf')
            ->select(
                DB::raw("DATE_FORMAT(cf.created_at, '%Y-%m') as month"),
                DB::raw('AVG(cf.csat_score) as avg_csat'),
                DB::raw('AVG(cf.nps_score) as avg_nps'),
                DB::raw('COUNT(*) as total_responses'),
                DB::raw('SUM(CASE WHEN cf.nps_score >= 9 THEN 1 ELSE 0 END) as promoters'),
                DB::raw('SUM(CASE WHEN cf.nps_score <= 6 THEN 1 ELSE 0 END) as detractors')
            )
            ->where('cf.created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = $feedbackData->pluck('month')->toArray();
        $csatScores = $feedbackData->pluck('avg_csat')->map(fn($v) => round($v, 1))->toArray();
        $npsScores = $feedbackData->pluck('avg_nps')->map(fn($v) => round($v, 1))->toArray();

        // Calculate overall NPS
        $totalResponses = $feedbackData->sum('total_responses');
        $totalPromoters = $feedbackData->sum('promoters');
        $totalDetractors = $feedbackData->sum('detractors');
        $overallNPS = round((($totalPromoters - $totalDetractors) / $totalResponses) * 100, 1);

        // Get feedback by service type
        $serviceBreakdown = DB::table('customer_feedback')
            ->select(
                'service_type',
                DB::raw('AVG(csat_score) as avg_csat'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy('service_type')
            ->get();

        // Get common feedback themes
        $feedbackThemes = DB::table('customer_feedback')
            ->select(
                'sentiment',
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('sentiment')
            ->groupBy('sentiment')
            ->get();

        return [
            'chart' => [
                'type' => 'line',
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'CSAT (رضا العملاء)',
                        'data' => $csatScores,
                        'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                        'borderColor' => 'rgb(34, 197, 94)',
                        'tension' => 0.4,
                    ],
                    [
                        'label' => 'NPS (صافي نقاط الترويج)',
                        'data' => $npsScores,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'borderColor' => 'rgb(59, 130, 246)',
                        'tension' => 0.4,
                    ],
                ],
            ],
            'summary' => [
                'avg_csat' => round($feedbackData->avg('avg_csat'), 1),
                'avg_nps' => round($feedbackData->avg('avg_nps'), 1),
                'overall_nps' => $overallNPS,
                'total_responses' => $totalResponses,
                'promoters' => $totalPromoters,
                'detractors' => $totalDetractors,
                'nps_category' => $overallNPS >= 50 ? 'excellent' : ($overallNPS >= 0 ? 'good' : 'poor'),
            ],
            'service_breakdown' => $serviceBreakdown->map(function($service) {
                return [
                    'service_type' => $service->service_type,
                    'avg_csat' => round($service->avg_csat, 1),
                    'count' => $service->count,
                ];
            })->toArray(),
            'sentiment_distribution' => $feedbackThemes->pluck('count', 'sentiment')->toArray(),
            'monthly_trend' => $feedbackData->map(function($month) {
                return [
                    'month' => $month->month,
                    'csat' => round($month->avg_csat, 1),
                    'nps' => round($month->avg_nps, 1),
                    'responses' => $month->total_responses,
                ];
            })->toArray(),
        ];
    }
}
