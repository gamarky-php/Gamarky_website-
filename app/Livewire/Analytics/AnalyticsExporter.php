<?php

namespace App\Livewire\Analytics;

use App\Services\AnalyticsService;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

/**
 * Analytics Exporter
 * Export analytics data to JSON, CSV, or Excel
 */
class AnalyticsExporter extends Component
{
    public $format = 'json'; // json|csv|excel
    public $analyticsType = 'funnel';
    public $dateRange = 30;

    protected $analytics;

    public function boot(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    /**
     * ACTION: Export analytics data
     */
    public function export()
    {
        $data = $this->getExportData();

        return match ($this->format) {
            'json' => $this->exportJson($data),
            'csv' => $this->exportCsv($data),
            'excel' => $this->exportExcel($data),
            default => null,
        };
    }

    /**
     * ENDPOINT: Get export data
     */
    protected function getExportData()
    {
        return match ($this->analyticsType) {
            'funnel' => $this->analytics->getFunnelData('daily', $this->dateRange, 'container'),
            'clearance' => $this->analytics->getClearanceTimeByPort(20),
            'errors' => $this->analytics->getDocumentErrorRate('port'),
            'sla' => $this->analytics->getBrokerSLACompliance(20),
            'shipping' => $this->analytics->getShippingPerformance('container'),
            'ads' => $this->analytics->getAdsPerformance($this->dateRange),
            'satisfaction' => $this->analytics->getCustomerSatisfaction($this->dateRange),
            default => [],
        };
    }

    /**
     * ACTION: Export as JSON
     */
    protected function exportJson($data)
    {
        $filename = "analytics_{$this->analyticsType}_" . now()->format('Y-m-d_H-i-s') . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * ACTION: Export as CSV
     */
    protected function exportCsv($data)
    {
        $filename = "analytics_{$this->analyticsType}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function () use ($data) {
            $output = fopen('php://output', 'w');
            
            // Write headers
            if (isset($data['chart']['labels'])) {
                fputcsv($output, ['Label', 'Value']);
                
                foreach ($data['chart']['labels'] as $index => $label) {
                    $value = $data['chart']['datasets'][0]['data'][$index] ?? '';
                    fputcsv($output, [$label, $value]);
                }
            }
            
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * ACTION: Export as Excel (simplified - would need PhpSpreadsheet for full support)
     */
    protected function exportExcel($data)
    {
        // This is a placeholder - full Excel export would require PhpSpreadsheet library
        return $this->exportCsv($data);
    }

    /**
     * ENDPOINT: Get available export formats
     */
    public function getAvailableFormats()
    {
        return [
            'json' => 'JSON',
            'csv' => 'CSV',
            'excel' => 'Excel (XLSX)',
        ];
    }

    public function render()
    {
        return view('livewire.analytics.analytics-exporter');
    }
}
