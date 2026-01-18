<?php

namespace App\Livewire\Analytics;

use App\Services\ChartConfigService;
use Livewire\Component;

/**
 * Chart Types Demo
 * 
 * Demonstrates all available chart types with sample data
 */
class ChartTypesDemo extends Component
{
    public $activeType = 'line';
    public $chartTypes = [];

    protected $chartConfig;

    public function boot(ChartConfigService $chartConfig)
    {
        $this->chartConfig = $chartConfig;
    }

    public function mount()
    {
        $this->chartTypes = $this->chartConfig->getAvailableTypes();
    }

    /**
     * Switch chart type
     */
    public function switchType($type)
    {
        $this->activeType = $type;
    }

    /**
     * LINE CHART DEMO - اتجاهات
     */
    public function getLineChartProperty()
    {
        $labels = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'];
        $datasets = [
            [
                'label' => 'المبيعات',
                'data' => [120, 150, 180, 140, 200, 230],
            ],
            [
                'label' => 'الحجوزات',
                'data' => [90, 110, 150, 120, 170, 190],
            ],
        ];

        return $this->chartConfig->lineChart($labels, $datasets);
    }

    /**
     * BAR CHART DEMO - مقارنات
     */
    public function getBarChartProperty()
    {
        $labels = ['ميناء جدة', 'ميناء الدمام', 'ميناء الجبيل', 'ميناء ينبع'];
        $datasets = [
            [
                'label' => 'متوسط أيام التخليص',
                'data' => [4.5, 7.2, 5.8, 6.1],
                'backgroundColor' => [
                    'rgba(16, 185, 129, 0.2)',  // Green
                    'rgba(245, 158, 11, 0.2)',  // Amber
                    'rgba(59, 130, 246, 0.2)',  // Blue
                    'rgba(245, 158, 11, 0.2)',  // Amber
                ],
                'borderColor' => [
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(59, 130, 246)',
                    'rgb(245, 158, 11)',
                ],
            ],
        ];

        return $this->chartConfig->barChart($labels, $datasets, ['indexAxis' => 'y']);
    }

    /**
     * DOUGHNUT CHART DEMO - حصص
     */
    public function getDoughnutChartProperty()
    {
        $labels = ['حاويات', 'شاحنات', 'استيراد', 'تصدير'];
        $data = [450, 320, 280, 150];

        return $this->chartConfig->doughnutChart($labels, $data);
    }

    /**
     * PIE CHART DEMO - حصص
     */
    public function getPieChartProperty()
    {
        $labels = ['مكتملة', 'قيد التنفيذ', 'معلقة', 'ملغاة'];
        $data = [65, 25, 8, 2];

        return $this->chartConfig->pieChart($labels, $data);
    }

    /**
     * RADAR CHART DEMO - مصفوفة تنافسية
     */
    public function getRadarChartProperty()
    {
        $labels = ['السرعة', 'الدقة', 'السعر', 'الخدمة', 'التوفر'];
        $datasets = [
            [
                'label' => 'المستخلص أ',
                'data' => [90, 85, 75, 88, 92],
            ],
            [
                'label' => 'المستخلص ب',
                'data' => [75, 92, 88, 80, 85],
            ],
            [
                'label' => 'المستخلص ج',
                'data' => [88, 78, 95, 85, 80],
            ],
        ];

        return $this->chartConfig->radarChart($labels, $datasets);
    }

    /**
     * GAUGE CHART DEMO - نسبة الهدف
     */
    public function getGaugeChartProperty()
    {
        return $this->chartConfig->gaugeChart(85, 100, [
            'label' => 'نسبة الإنجاز',
        ]);
    }

    /**
     * PROGRESS BAR DEMO
     */
    public function getProgressChartProperty()
    {
        return $this->chartConfig->progressBar(
            'الهدف الشهري',
            750000,
            1000000
        );
    }

    /**
     * MIXED CHART DEMO - Line + Bar
     */
    public function getMixedChartProperty()
    {
        $labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        $datasets = [
            [
                'label' => 'الإيرادات (مليون ريال)',
                'data' => [2.5, 3.2, 2.8, 4.1],
                'type' => 'bar',
                'yAxisID' => 'y',
            ],
            [
                'label' => 'معدل النمو (%)',
                'data' => [15, 28, -12, 46],
                'type' => 'line',
                'yAxisID' => 'y1',
                'fill' => false,
            ],
        ];

        return $this->chartConfig->mixedChart($labels, $datasets);
    }

    /**
     * Get current chart data based on active type
     */
    public function getCurrentChartProperty()
    {
        return match ($this->activeType) {
            'line' => $this->lineChart,
            'bar' => $this->barChart,
            'doughnut' => $this->doughnutChart,
            'pie' => $this->pieChart,
            'radar' => $this->radarChart,
            'gauge' => $this->gaugeChart,
            'progress' => $this->progressChart,
            'mixed' => $this->mixedChart,
            default => $this->lineChart,
        };
    }

    /**
     * Get code example for current chart type
     */
    public function getCodeExample()
    {
        return match ($this->activeType) {
            'line' => "// LINE CHART - للاتجاهات
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$labels = ['يناير', 'فبراير', 'مارس', 'أبريل'];
\$datasets = [
    [
        'label' => 'المبيعات',
        'data' => [120, 150, 180, 140],
    ],
];

\$config = \$chartConfig->lineChart(\$labels, \$datasets);",

            'bar' => "// BAR CHART - للمقارنات
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$labels = ['ميناء جدة', 'ميناء الدمام', 'ميناء الجبيل'];
\$datasets = [
    [
        'label' => 'متوسط أيام التخليص',
        'data' => [4.5, 7.2, 5.8],
    ],
];

\$config = \$chartConfig->barChart(\$labels, \$datasets, ['indexAxis' => 'y']);",

            'doughnut' => "// DOUGHNUT CHART - للحصص
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$labels = ['حاويات', 'شاحنات', 'استيراد', 'تصدير'];
\$data = [450, 320, 280, 150];

\$config = \$chartConfig->doughnutChart(\$labels, \$data);",

            'pie' => "// PIE CHART - للحصص
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$labels = ['مكتملة', 'قيد التنفيذ', 'معلقة', 'ملغاة'];
\$data = [65, 25, 8, 2];

\$config = \$chartConfig->pieChart(\$labels, \$data);",

            'radar' => "// RADAR CHART - مصفوفة تنافسية
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$labels = ['السرعة', 'الدقة', 'السعر', 'الخدمة', 'التوفر'];
\$datasets = [
    ['label' => 'المستخلص أ', 'data' => [90, 85, 75, 88, 92]],
    ['label' => 'المستخلص ب', 'data' => [75, 92, 88, 80, 85]],
];

\$config = \$chartConfig->radarChart(\$labels, \$datasets);",

            'gauge' => "// GAUGE CHART - نسبة الهدف
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$config = \$chartConfig->gaugeChart(85, 100, [
    'label' => 'نسبة الإنجاز',
]);",

            'progress' => "// PROGRESS BAR - شريط التقدم
use App\Services\ChartConfigService;

\$chartConfig = app(ChartConfigService::class);
\$config = \$chartConfig->progressBar(
    'الهدف الشهري',
    750000,  // Current value
    1000000  // Target
);",

            default => "// مثال الكود",
        };
    }

    public function render()
    {
        return view('livewire.analytics.chart-types-demo', [
            'currentChart' => $this->currentChart,
        ]);
    }
}
