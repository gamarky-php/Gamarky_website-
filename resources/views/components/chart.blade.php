<!-- resources/views/components/chart.blade.php -->
@props([
    'type' => 'line', // line, bar, pie, doughnut
    'chartId' => null,
    'data' => [],
    'options' => [],
    'height' => '300',
])

@php
    $id = $chartId ?? 'chart-' . uniqid();
    $chartData = is_string($data) ? $data : json_encode($data);
    $chartOptions = is_string($options) ? $options : json_encode($options);
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <canvas id="{{ $id }}" style="max-height: {{ $height }}px;"></canvas>
</div>

@once
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush
@endonce

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $id }}');
        
        if (!ctx) {
            console.error('Canvas element not found: {{ $id }}');
            return;
        }
        
        const chartData = {!! $chartData !!};
        const chartOptions = {!! $chartOptions !!};
        
        // Default RTL configuration
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    rtl: true,
                    textDirection: 'rtl',
                },
                tooltip: {
                    rtl: true,
                    textDirection: 'rtl',
                }
            },
            scales: {
                x: {
                    reverse: true, // RTL
                },
            }
        };
        
        // Merge options
        const finalOptions = Object.assign({}, defaultOptions, chartOptions);
        
        // Create chart
        new Chart(ctx, {
            type: '{{ $type }}',
            data: chartData,
            options: finalOptions
        });
    });
</script>
@endpush
