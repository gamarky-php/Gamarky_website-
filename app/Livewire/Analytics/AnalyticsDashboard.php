<?php

namespace App\Livewire\Analytics;

use App\Services\AnalyticsService;
use Livewire\Component;
use Livewire\Attributes\On;

class AnalyticsDashboard extends Component
{
    public $activeTab = 'funnel'; // funnel|clearance|errors|sla|shipping|ads|satisfaction
    public $period = 'daily'; // daily|weekly|monthly
    public $section = 'container'; // For funnel: container|truck|import|export
    public $groupBy = 'port'; // For errors: port|broker
    public $shippingType = 'container'; // For shipping: container|truck
    public $days = 30; // For time-based analytics
    public $limit = 10; // For top N items
    public $timeUnits = 30; // Number of time units (days/weeks/months)

    // Chart refresh control
    public $chartData = [];
    public $lastUpdate = null;

    protected $analytics;

    public function boot(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function mount()
    {
        // Initialize with default chart data
        $this->refreshChartData();
        $this->lastUpdate = now()->format('H:i:s');
    }

    /**
     * ACTION: Switch active tab and reload chart data
     */
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->refreshChartData();
    }

    /**
     * ACTION: Refresh chart data for current tab
     */
    #[On('refresh-analytics')]
    public function refreshChartData()
    {
        $this->chartData = $this->getCurrentTabData();
        $this->lastUpdate = now()->format('H:i:s');
        
        // Dispatch browser event to update Chart.js
        $this->dispatch('chart-data-updated', chartData: $this->chartData);
    }

    /**
     * ACTION: Update period filter and reload funnel data
     */
    public function updatePeriod($period)
    {
        $this->period = $period;
        if ($this->activeTab === 'funnel') {
            $this->refreshChartData();
        }
    }

    /**
     * ACTION: Update section filter and reload funnel data
     */
    public function updateSection($section)
    {
        $this->section = $section;
        if ($this->activeTab === 'funnel') {
            $this->refreshChartData();
        }
    }

    /**
     * ACTION: Update groupBy filter and reload error data
     */
    public function updateGroupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        if ($this->activeTab === 'errors') {
            $this->refreshChartData();
        }
    }

    /**
     * ACTION: Update shipping type filter and reload shipping data
     */
    public function updateShippingType($type)
    {
        $this->shippingType = $type;
        if ($this->activeTab === 'shipping') {
            $this->refreshChartData();
        }
    }

    /**
     * ACTION: Update time range (days) and reload data
     */
    public function updateTimeRange($days)
    {
        $this->days = $days;
        if (in_array($this->activeTab, ['ads', 'satisfaction'])) {
            $this->refreshChartData();
        }
    }

    /**
     * ACTION: Export current chart data as JSON
     */
    public function exportChartData()
    {
        $data = $this->getCurrentTabData();
        
        return response()->json([
            'tab' => $this->activeTab,
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ]);
    }

    /**
     * ACTION: Get Chart.js config for specific analytics type
     */
    public function getChartConfig($type)
    {
        $data = $this->analytics->{$this->getMethodName($type)}(...$this->getMethodParams($type));
        
        return [
            'type' => $data['chart']['type'],
            'data' => [
                'labels' => $data['chart']['labels'],
                'datasets' => $data['chart']['datasets'],
            ],
            'options' => $this->getChartOptions($type),
        ];
    }

    /**
     * ACTION: Get summary statistics for current tab
     */
    public function getSummaryStats()
    {
        $data = $this->getCurrentTabData();
        
        return $data['summary'] ?? [];
    }

    /**
     * ACTION: Compare two time periods
     */
    public function compareTimePeriods($period1, $period2)
    {
        // This would be extended to compare different time ranges
        return [
            'period1' => $this->analytics->getFunnelData($period1, 30, $this->section),
            'period2' => $this->analytics->getFunnelData($period2, 30, $this->section),
            'change' => [], // Calculate % change
        ];
    }

    /**
     * HELPER: Get current tab data based on active tab
     */
    protected function getCurrentTabData()
    {
        return match ($this->activeTab) {
            'funnel' => $this->funnelData,
            'clearance' => $this->clearanceData,
            'errors' => $this->errorData,
            'sla' => $this->slaData,
            'shipping' => $this->shippingData,
            'ads' => $this->adsData,
            'satisfaction' => $this->satisfactionData,
            default => [],
        };
    }

    /**
     * HELPER: Get method name for analytics type
     */
    protected function getMethodName($type)
    {
        return match ($type) {
            'funnel' => 'getFunnelData',
            'clearance' => 'getClearanceTimeByPort',
            'errors' => 'getDocumentErrorRate',
            'sla' => 'getBrokerSLACompliance',
            'shipping' => 'getShippingPerformance',
            'ads' => 'getAdsPerformance',
            'satisfaction' => 'getCustomerSatisfaction',
            default => null,
        };
    }

    /**
     * HELPER: Get method parameters for analytics type
     */
    protected function getMethodParams($type)
    {
        return match ($type) {
            'funnel' => [$this->period, $this->timeUnits, $this->section],
            'clearance' => [$this->limit],
            'errors' => [$this->groupBy],
            'sla' => [$this->limit],
            'shipping' => [$this->shippingType],
            'ads' => [$this->days],
            'satisfaction' => [$this->days],
            default => [],
        };
    }

    /**
     * HELPER: Get Chart.js options for specific chart type
     */
    protected function getChartOptions($type)
    {
        $baseOptions = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'rtl' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'rtl' => true,
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];

        // Add specific options based on chart type
        return match ($type) {
            'funnel' => array_merge($baseOptions, [
                'scales' => [
                    'y' => ['beginAtZero' => true],
                ],
            ]),
            'clearance', 'errors', 'sla', 'shipping' => array_merge($baseOptions, [
                'indexAxis' => 'y',
                'scales' => [
                    'x' => ['beginAtZero' => true],
                ],
            ]),
            'ads' => array_merge($baseOptions, [
                'scales' => [
                    'y' => ['beginAtZero' => true, 'max' => 100],
                ],
            ]),
            'satisfaction' => array_merge($baseOptions, [
                'scales' => [
                    'y' => ['beginAtZero' => true, 'max' => 10],
                ],
            ]),
            default => $baseOptions,
        };
    }

    public function getFunnelDataProperty()
    {
        return $this->analytics->getFunnelData($this->period, $this->timeUnits, $this->section);
    }

    public function getClearanceDataProperty()
    {
        return $this->analytics->getClearanceTimeByPort($this->limit);
    }

    public function getErrorDataProperty()
    {
        return $this->analytics->getDocumentErrorRate($this->groupBy);
    }

    public function getSlaDataProperty()
    {
        return $this->analytics->getBrokerSLACompliance($this->limit);
    }

    public function getShippingDataProperty()
    {
        return $this->analytics->getShippingPerformance($this->shippingType);
    }

    public function getAdsDataProperty()
    {
        return $this->analytics->getAdsPerformance($this->days);
    }

    public function getSatisfactionDataProperty()
    {
        return $this->analytics->getCustomerSatisfaction($this->days);
    }

    /**
     * ENDPOINT: Get chart data as JSON (for AJAX calls)
     */
    public function getChartDataJson($type = null)
    {
        $analyticsType = $type ?? $this->activeTab;
        $data = $this->getCurrentTabData();
        
        return response()->json([
            'success' => true,
            'type' => $analyticsType,
            'chart' => $data['chart'] ?? [],
            'summary' => $data['summary'] ?? [],
            'metadata' => [
                'period' => $this->period,
                'section' => $this->section,
                'groupBy' => $this->groupBy,
                'days' => $this->days,
                'lastUpdate' => $this->lastUpdate,
            ],
        ]);
    }

    public function render()
    {
        $data = [];
        
        switch ($this->activeTab) {
            case 'funnel':
                $data = $this->funnelData;
                break;
            case 'clearance':
                $data = $this->clearanceData;
                break;
            case 'errors':
                $data = $this->errorData;
                break;
            case 'sla':
                $data = $this->slaData;
                break;
            case 'shipping':
                $data = $this->shippingData;
                break;
            case 'ads':
                $data = $this->adsData;
                break;
            case 'satisfaction':
                $data = $this->satisfactionData;
                break;
        }

        return view('livewire.analytics.analytics-dashboard', [
            'data' => $data,
        ]);
    }
}
