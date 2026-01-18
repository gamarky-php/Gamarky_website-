<?php

namespace App\Livewire\Analytics;

use App\Services\AnalyticsService;
use Livewire\Component;

/**
 * Mini Funnel Widget - Can be embedded anywhere
 * Displays conversion funnel summary with sparkline chart
 */
class FunnelWidget extends Component
{
    public $section = 'container'; // container|truck|import|export
    public $period = 'daily';
    public $days = 7;
    public $showChart = true;

    protected $analytics;

    public function boot(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * ACTION: Get funnel data for Chart.js
     */
    public function getFunnelChartData()
    {
        $data = $this->analytics->getFunnelData($this->period, $this->days, $this->section);
        
        return [
            'type' => 'line',
            'data' => [
                'labels' => $data['chart']['labels'],
                'datasets' => $data['chart']['datasets'],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => ['display' => false],
                ],
                'scales' => [
                    'y' => ['display' => false],
                    'x' => ['display' => false],
                ],
                'elements' => [
                    'line' => ['borderWidth' => 2],
                    'point' => ['radius' => 0],
                ],
            ],
        ];
    }

    /**
     * COMPUTED: Get funnel summary stats
     */
    public function getFunnelDataProperty()
    {
        return $this->analytics->getFunnelData($this->period, $this->days, $this->section);
    }

    /**
     * ACTION: Update section and reload
     */
    public function updateSection($section)
    {
        $this->section = $section;
        $this->dispatch('widget-updated');
    }

    public function render()
    {
        return view('livewire.analytics.funnel-widget', [
            'data' => $this->funnelData,
            'chartConfig' => $this->getFunnelChartData(),
        ]);
    }
}
