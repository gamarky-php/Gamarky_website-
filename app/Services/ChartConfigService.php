<?php

namespace App\Services;

/**
 * Chart Configuration Service
 * 
 * Provides Chart.js configurations for different chart types:
 * - Line Charts (اتجاهات - Trends)
 * - Bar Charts (مقارنات - Comparisons)
 * - Doughnut/Pie Charts (حصص - Shares/Proportions)
 * - Radar Charts (مصفوفة تنافسية - Competitive Matrix)
 * - Gauge/Progress Charts (نسب أهداف - Goal Percentages)
 */
class ChartConfigService
{
    /**
     * RTL Colors Palette
     */
    protected $colors = [
        'primary' => ['bg' => 'rgba(99, 102, 241, 0.2)', 'border' => 'rgb(99, 102, 241)', 'solid' => '#6366f1'],
        'success' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'border' => 'rgb(16, 185, 129)', 'solid' => '#10b981'],
        'danger' => ['bg' => 'rgba(239, 68, 68, 0.2)', 'border' => 'rgb(239, 68, 68)', 'solid' => '#ef4444'],
        'warning' => ['bg' => 'rgba(245, 158, 11, 0.2)', 'border' => 'rgb(245, 158, 11)', 'solid' => '#f59e0b'],
        'info' => ['bg' => 'rgba(59, 130, 246, 0.2)', 'border' => 'rgb(59, 130, 246)', 'solid' => '#3b82f6'],
        'purple' => ['bg' => 'rgba(168, 85, 247, 0.2)', 'border' => 'rgb(168, 85, 247)', 'solid' => '#a855f7'],
        'pink' => ['bg' => 'rgba(236, 72, 153, 0.2)', 'border' => 'rgb(236, 72, 153)', 'solid' => '#ec4899'],
        'emerald' => ['bg' => 'rgba(16, 185, 129, 0.2)', 'border' => 'rgb(16, 185, 129)', 'solid' => '#10b981'],
    ];

    /**
     * LINE CHART - للاتجاهات (Trends over time)
     * 
     * Use cases:
     * - Conversion funnel over time
     * - Customer satisfaction trends
     * - Revenue growth
     * - Performance metrics timeline
     */
    public function lineChart(array $labels, array $datasets, array $options = []): array
    {
        $formattedDatasets = [];
        
        foreach ($datasets as $index => $dataset) {
            $colorKey = array_keys($this->colors)[$index % count($this->colors)];
            $color = $this->colors[$colorKey];
            
            $formattedDatasets[] = [
                'label' => $dataset['label'] ?? "Dataset " . ($index + 1),
                'data' => $dataset['data'],
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'borderWidth' => $dataset['borderWidth'] ?? 2,
                'fill' => $dataset['fill'] ?? false,
                'tension' => $dataset['tension'] ?? 0.4, // Smooth curves
                'pointRadius' => $dataset['pointRadius'] ?? 3,
                'pointHoverRadius' => $dataset['pointHoverRadius'] ?? 5,
            ];
        }

        return [
            'type' => 'line',
            'data' => [
                'labels' => $labels,
                'datasets' => $formattedDatasets,
            ],
            'options' => array_merge([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                        'rtl' => true,
                        'labels' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                            'usePointStyle' => true,
                        ],
                    ],
                    'tooltip' => [
                        'rtl' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleFont' => ['family' => 'Cairo, sans-serif', 'size' => 14],
                        'bodyFont' => ['family' => 'Cairo, sans-serif', 'size' => 12],
                        'padding' => 12,
                        'cornerRadius' => 8,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                        'grid' => [
                            'color' => 'rgba(0, 0, 0, 0.05)',
                        ],
                    ],
                    'x' => [
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                        'grid' => [
                            'display' => false,
                        ],
                    ],
                ],
            ], $options),
        ];
    }

    /**
     * BAR CHART - للمقارنات (Comparisons between items)
     * 
     * Use cases:
     * - Clearance time by port
     * - Broker SLA compliance
     * - Sales by region
     * - Performance comparison
     */
    public function barChart(array $labels, array $datasets, array $options = []): array
    {
        $formattedDatasets = [];
        
        foreach ($datasets as $index => $dataset) {
            $colorKey = array_keys($this->colors)[$index % count($this->colors)];
            $color = $this->colors[$colorKey];
            
            // Support custom colors per bar
            $backgroundColor = $dataset['backgroundColor'] ?? $color['bg'];
            $borderColor = $dataset['borderColor'] ?? $color['border'];
            
            if (is_array($backgroundColor) && isset($backgroundColor[0])) {
                // Array of colors for each bar
                $formattedDatasets[] = [
                    'label' => $dataset['label'] ?? "Dataset " . ($index + 1),
                    'data' => $dataset['data'],
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'borderWidth' => $dataset['borderWidth'] ?? 1,
                    'borderRadius' => $dataset['borderRadius'] ?? 6,
                ];
            } else {
                // Single color
                $formattedDatasets[] = [
                    'label' => $dataset['label'] ?? "Dataset " . ($index + 1),
                    'data' => $dataset['data'],
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'borderWidth' => $dataset['borderWidth'] ?? 1,
                    'borderRadius' => $dataset['borderRadius'] ?? 6,
                ];
            }
        }

        $indexAxis = $options['indexAxis'] ?? 'x'; // 'y' for horizontal bars

        return [
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => $formattedDatasets,
            ],
            'options' => array_merge([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'indexAxis' => $indexAxis,
                'plugins' => [
                    'legend' => [
                        'display' => count($datasets) > 1,
                        'position' => 'top',
                        'rtl' => true,
                        'labels' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                    ],
                    'tooltip' => [
                        'rtl' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleFont' => ['family' => 'Cairo, sans-serif'],
                        'bodyFont' => ['family' => 'Cairo, sans-serif'],
                    ],
                ],
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                        'grid' => [
                            'color' => $indexAxis === 'y' ? 'rgba(0, 0, 0, 0.05)' : 'rgba(0, 0, 0, 0)',
                        ],
                    ],
                    'x' => [
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                        'grid' => [
                            'color' => $indexAxis === 'x' ? 'rgba(0, 0, 0, 0.05)' : 'rgba(0, 0, 0, 0)',
                        ],
                    ],
                ],
            ], $options),
        ];
    }

    /**
     * DOUGHNUT CHART - للحصص والنسب (Shares and proportions)
     * 
     * Use cases:
     * - Market share
     * - Budget allocation
     * - Service type distribution
     * - Status breakdown
     */
    public function doughnutChart(array $labels, array $data, array $options = []): array
    {
        $colors = array_column($this->colors, 'solid');
        $backgroundColors = array_slice($colors, 0, count($data));

        return [
            'type' => 'doughnut',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => $options['colors'] ?? $backgroundColors,
                        'borderColor' => '#ffffff',
                        'borderWidth' => 2,
                        'hoverOffset' => 10,
                    ],
                ],
            ],
            'options' => array_merge([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'cutout' => '70%', // Donut hole size
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'bottom',
                        'rtl' => true,
                        'labels' => [
                            'font' => ['family' => 'Cairo, sans-serif', 'size' => 12],
                            'padding' => 15,
                            'usePointStyle' => true,
                            'generateLabels' => null, // Auto-generate with percentages
                        ],
                    ],
                    'tooltip' => [
                        'rtl' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleFont' => ['family' => 'Cairo, sans-serif'],
                        'bodyFont' => ['family' => 'Cairo, sans-serif'],
                        'callbacks' => [
                            'label' => null, // Will show percentage
                        ],
                    ],
                ],
            ], $options),
        ];
    }

    /**
     * PIE CHART - similar to doughnut but without center hole
     */
    public function pieChart(array $labels, array $data, array $options = []): array
    {
        $config = $this->doughnutChart($labels, $data, $options);
        $config['type'] = 'pie';
        $config['options']['cutout'] = 0; // No hole
        
        return $config;
    }

    /**
     * RADAR CHART - للمصفوفة التنافسية (Competitive matrix/comparison)
     * 
     * Use cases:
     * - Broker capabilities comparison
     * - Service quality metrics
     * - Multi-dimensional analysis
     * - Skill assessment
     */
    public function radarChart(array $labels, array $datasets, array $options = []): array
    {
        $formattedDatasets = [];
        
        foreach ($datasets as $index => $dataset) {
            $colorKey = array_keys($this->colors)[$index % count($this->colors)];
            $color = $this->colors[$colorKey];
            
            $formattedDatasets[] = [
                'label' => $dataset['label'] ?? "Dataset " . ($index + 1),
                'data' => $dataset['data'],
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'borderWidth' => 2,
                'pointBackgroundColor' => $color['solid'],
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => $color['border'],
            ];
        }

        return [
            'type' => 'radar',
            'data' => [
                'labels' => $labels,
                'datasets' => $formattedDatasets,
            ],
            'options' => array_merge([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'top',
                        'rtl' => true,
                        'labels' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                    ],
                    'tooltip' => [
                        'rtl' => true,
                        'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                        'titleFont' => ['family' => 'Cairo, sans-serif'],
                        'bodyFont' => ['family' => 'Cairo, sans-serif'],
                    ],
                ],
                'scales' => [
                    'r' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                            'stepSize' => $options['stepSize'] ?? 20,
                        ],
                        'pointLabels' => [
                            'font' => ['family' => 'Cairo, sans-serif', 'size' => 12],
                        ],
                        'grid' => [
                            'color' => 'rgba(0, 0, 0, 0.1)',
                        ],
                    ],
                ],
            ], $options),
        ];
    }

    /**
     * GAUGE CHART - لنسب الأهداف (Goal percentages)
     * 
     * Note: Chart.js doesn't have native gauge support
     * This creates a doughnut chart styled as a gauge (semi-circle)
     * 
     * Use cases:
     * - Goal completion percentage
     * - SLA compliance gauge
     * - Performance score
     * - Target achievement
     */
    public function gaugeChart(float $value, float $max = 100, array $options = []): array
    {
        $percentage = ($value / $max) * 100;
        $remaining = $max - $value;

        // Determine color based on percentage
        $color = $this->getGaugeColor($percentage);

        return [
            'type' => 'doughnut',
            'data' => [
                'labels' => ['مكتمل', 'متبقي'],
                'datasets' => [
                    [
                        'data' => [$value, $remaining],
                        'backgroundColor' => [
                            $color,
                            'rgba(229, 231, 235, 0.3)', // Light gray for remaining
                        ],
                        'borderColor' => ['#ffffff', '#ffffff'],
                        'borderWidth' => 0,
                        'circumference' => 180, // Half circle
                        'rotation' => 270, // Start from bottom
                    ],
                ],
            ],
            'options' => array_merge([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'cutout' => '75%',
                'plugins' => [
                    'legend' => [
                        'display' => false,
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'rtl' => true,
                        'callbacks' => [
                            'label' => null, // Custom label showing percentage
                        ],
                    ],
                ],
            ], $options),
            'centerText' => [
                'value' => round($percentage, 1) . '%',
                'label' => $options['label'] ?? 'الإنجاز',
            ],
        ];
    }

    /**
     * PROGRESS BAR (Horizontal bar as progress indicator)
     */
    public function progressBar(string $label, float $current, float $target, array $options = []): array
    {
        $percentage = ($current / $target) * 100;
        $remaining = $target - $current;
        
        $color = $this->getGaugeColor($percentage);

        return [
            'type' => 'bar',
            'data' => [
                'labels' => [$label],
                'datasets' => [
                    [
                        'label' => 'مكتمل',
                        'data' => [$current],
                        'backgroundColor' => $color,
                        'borderRadius' => 8,
                    ],
                    [
                        'label' => 'متبقي',
                        'data' => [$remaining],
                        'backgroundColor' => 'rgba(229, 231, 235, 0.3)',
                        'borderRadius' => 8,
                    ],
                ],
            ],
            'options' => array_merge([
                'indexAxis' => 'y',
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'display' => false,
                    ],
                    'tooltip' => [
                        'rtl' => true,
                    ],
                ],
                'scales' => [
                    'x' => [
                        'stacked' => true,
                        'max' => $target,
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                    ],
                    'y' => [
                        'stacked' => true,
                        'ticks' => [
                            'font' => ['family' => 'Cairo, sans-serif'],
                        ],
                    ],
                ],
            ], $options),
            'percentage' => round($percentage, 1),
        ];
    }

    /**
     * MIXED CHART - Combination of line and bar
     * 
     * Use cases:
     * - Revenue (bars) vs Growth Rate (line)
     * - Volume (bars) vs Conversion Rate (line)
     */
    public function mixedChart(array $labels, array $datasets, array $options = []): array
    {
        $formattedDatasets = [];
        
        foreach ($datasets as $index => $dataset) {
            $colorKey = array_keys($this->colors)[$index % count($this->colors)];
            $color = $this->colors[$colorKey];
            
            $formattedDatasets[] = [
                'label' => $dataset['label'],
                'data' => $dataset['data'],
                'type' => $dataset['type'] ?? 'bar', // 'bar' or 'line'
                'backgroundColor' => $dataset['type'] === 'line' ? 'transparent' : $color['bg'],
                'borderColor' => $color['border'],
                'borderWidth' => 2,
                'fill' => $dataset['fill'] ?? false,
                'yAxisID' => $dataset['yAxisID'] ?? 'y',
            ];
        }

        return [
            'type' => 'bar', // Default type
            'data' => [
                'labels' => $labels,
                'datasets' => $formattedDatasets,
            ],
            'options' => array_merge([
                'responsive' => true,
                'maintainAspectRatio' => false,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'rtl' => true,
                    ],
                ],
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'position' => 'right',
                        'beginAtZero' => true,
                    ],
                    'y1' => [
                        'type' => 'linear',
                        'position' => 'left',
                        'beginAtZero' => true,
                        'grid' => [
                            'drawOnChartArea' => false,
                        ],
                    ],
                ],
            ], $options),
        ];
    }

    /**
     * HELPER: Get gauge color based on percentage
     */
    protected function getGaugeColor(float $percentage): string
    {
        if ($percentage >= 90) {
            return $this->colors['success']['solid'];
        } elseif ($percentage >= 75) {
            return $this->colors['info']['solid'];
        } elseif ($percentage >= 50) {
            return $this->colors['warning']['solid'];
        } else {
            return $this->colors['danger']['solid'];
        }
    }

    /**
     * HELPER: Get color by name
     * 
     * @param string $name Color name (primary, success, danger, warning, info, purple, pink, emerald)
     * @return array
     */
    public function getColor(string $name): array
    {
        return $this->colors[$name] ?? $this->colors['primary'];
    }

    /**
     * HELPER: Get all available chart types
     */
    public function getAvailableTypes(): array
    {
        return [
            'line' => [
                'name' => 'Line Chart',
                'name_ar' => 'رسم الاتجاهات',
                'description' => 'للاتجاهات عبر الزمن',
                'icon' => '📈',
            ],
            'bar' => [
                'name' => 'Bar Chart',
                'name_ar' => 'رسم المقارنات',
                'description' => 'للمقارنة بين العناصر',
                'icon' => '📊',
            ],
            'doughnut' => [
                'name' => 'Doughnut Chart',
                'name_ar' => 'رسم الحصص',
                'description' => 'للنسب والحصص',
                'icon' => '🍩',
            ],
            'pie' => [
                'name' => 'Pie Chart',
                'name_ar' => 'رسم دائري',
                'description' => 'للتوزيع النسبي',
                'icon' => '🥧',
            ],
            'radar' => [
                'name' => 'Radar Chart',
                'name_ar' => 'مصفوفة تنافسية',
                'description' => 'للمقارنة متعددة الأبعاد',
                'icon' => '🎯',
            ],
            'gauge' => [
                'name' => 'Gauge Chart',
                'name_ar' => 'مقياس الأهداف',
                'description' => 'لنسب الإنجاز',
                'icon' => '⏱️',
            ],
            'progress' => [
                'name' => 'Progress Bar',
                'name_ar' => 'شريط التقدم',
                'description' => 'لتتبع الأهداف',
                'icon' => '📊',
            ],
        ];
    }
}
