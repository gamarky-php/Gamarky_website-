<?php

namespace App\Livewire\Analytics;

use App\Services\AnalyticsService;
use Livewire\Component;

/**
 * Performance KPI Widget
 * Shows key performance indicators across all sections
 */
class PerformanceKpi extends Component
{
    public $metric = 'all'; // all|clearance|sla|shipping|satisfaction
    public $compact = false; // Compact view for sidebars

    protected $analytics;

    public function boot(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * ENDPOINT: Get all KPIs as JSON
     */
    public function getAllKpis()
    {
        return [
            'clearance' => $this->getClearanceKpi(),
            'sla' => $this->getSlaKpi(),
            'shipping' => $this->getShippingKpi(),
            'satisfaction' => $this->getSatisfactionKpi(),
        ];
    }

    /**
     * ACTION: Get clearance KPI
     */
    public function getClearanceKpi()
    {
        $data = $this->analytics->getClearanceTimeByPort(1);
        
        return [
            'label' => 'متوسط زمن التخليص',
            'value' => $data['overall_avg'] ?? 0,
            'unit' => 'يوم',
            'trend' => $this->calculateTrend($data['trend'] ?? []),
            'color' => $data['overall_avg'] < 5 ? 'green' : ($data['overall_avg'] < 10 ? 'amber' : 'red'),
        ];
    }

    /**
     * ACTION: Get SLA compliance KPI
     */
    public function getSlaKpi()
    {
        $data = $this->analytics->getBrokerSLACompliance(1);
        
        return [
            'label' => 'الالتزام بـ SLA',
            'value' => $data['overall_compliance'] ?? 0,
            'unit' => '%',
            'trend' => $this->calculateTrend($data['monthly_trend'] ?? []),
            'color' => $data['overall_compliance'] >= 90 ? 'green' : ($data['overall_compliance'] >= 75 ? 'amber' : 'red'),
        ];
    }

    /**
     * ACTION: Get shipping performance KPI
     */
    public function getShippingKpi()
    {
        $data = $this->analytics->getShippingPerformance('container');
        
        return [
            'label' => 'التسليم في الموعد',
            'value' => $data['overall_on_time'] ?? 0,
            'unit' => '%',
            'trend' => $this->calculateTrend($data['monthly_trend'] ?? []),
            'color' => $data['overall_on_time'] >= 85 ? 'green' : ($data['overall_on_time'] >= 70 ? 'amber' : 'red'),
        ];
    }

    /**
     * ACTION: Get customer satisfaction KPI
     */
    public function getSatisfactionKpi()
    {
        $data = $this->analytics->getCustomerSatisfaction(30);
        
        return [
            'label' => 'رضا العملاء (CSAT)',
            'value' => $data['summary']['avg_csat'] ?? 0,
            'unit' => '/10',
            'trend' => $this->calculateTrend($data['monthly_trend'] ?? []),
            'color' => $data['summary']['avg_csat'] >= 8 ? 'green' : ($data['summary']['avg_csat'] >= 6 ? 'amber' : 'red'),
            'nps' => $data['summary']['overall_nps'] ?? 0,
        ];
    }

    /**
     * HELPER: Calculate trend from historical data
     */
    protected function calculateTrend($trendData)
    {
        if (empty($trendData) || count($trendData) < 2) {
            return ['direction' => 'neutral', 'percentage' => 0];
        }

        $latest = end($trendData);
        $previous = prev($trendData);

        $latestValue = is_array($latest) ? ($latest['value'] ?? 0) : $latest;
        $previousValue = is_array($previous) ? ($previous['value'] ?? 0) : $previous;

        if ($previousValue == 0) {
            return ['direction' => 'neutral', 'percentage' => 0];
        }

        $change = (($latestValue - $previousValue) / $previousValue) * 100;

        return [
            'direction' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral'),
            'percentage' => abs(round($change, 1)),
        ];
    }

    public function render()
    {
        $kpis = $this->metric === 'all' ? $this->getAllKpis() : [$this->metric => $this->{"get{$this->metric}Kpi"}()];

        return view('livewire.analytics.performance-kpi', [
            'kpis' => $kpis,
        ]);
    }
}
