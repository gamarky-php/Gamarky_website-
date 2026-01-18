<?php

namespace App\Livewire\Analytics;

use App\Services\AnalyticsService;
use Livewire\Component;

/**
 * Real-Time Analytics Monitor
 * Auto-refreshes analytics data every N seconds
 */
class RealTimeMonitor extends Component
{
    public $refreshInterval = 30; // seconds
    public $metrics = ['funnel', 'clearance', 'sla']; // Which metrics to monitor
    public $autoRefresh = true;

    protected $analytics;

    public function boot(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * ACTION: Toggle auto-refresh
     */
    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    /**
     * ACTION: Manual refresh
     */
    public function refresh()
    {
        $this->dispatch('metrics-updated', metrics: $this->getLatestMetrics());
    }

    /**
     * ENDPOINT: Get latest metrics as JSON
     */
    public function getLatestMetrics()
    {
        $data = [];

        foreach ($this->metrics as $metric) {
            $data[$metric] = $this->getMetricData($metric);
        }

        return [
            'timestamp' => now()->toIso8601String(),
            'metrics' => $data,
        ];
    }

    /**
     * HELPER: Get data for specific metric
     */
    protected function getMetricData($metric)
    {
        return match ($metric) {
            'funnel' => $this->analytics->getFunnelData('daily', 7, 'container')['summary'],
            'clearance' => ['avg_days' => $this->analytics->getClearanceTimeByPort(1)['overall_avg']],
            'sla' => ['compliance' => $this->analytics->getBrokerSLACompliance(1)['overall_compliance']],
            'shipping' => ['on_time' => $this->analytics->getShippingPerformance('container')['overall_on_time']],
            'satisfaction' => $this->analytics->getCustomerSatisfaction(7)['summary'],
            default => [],
        };
    }

    public function render()
    {
        return view('livewire.analytics.real-time-monitor', [
            'latestMetrics' => $this->getLatestMetrics(),
        ]);
    }
}
