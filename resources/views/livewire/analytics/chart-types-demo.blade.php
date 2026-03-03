{{-- dir inherited from layout --}}
<div class="space-y-6" x-data="{ 
    charts: {},
    initChart(id, config) {
        const ctx = document.getElementById(id);
        if (!ctx) return;
        
        // Destroy existing chart
        if (this.charts[id]) {
            this.charts[id].destroy();
        }
        
        // Handle gauge chart with center text
        if (config.centerText) {
            this.charts[id] = new Chart(ctx, {
                type: config.type,
                data: config.data,
                options: config.options,
                plugins: [{
                    id: 'centerText',
                    beforeDraw: function(chart) {
                        const width = chart.width;
                        const height = chart.height;
                        const ctx = chart.ctx;
                        
                        ctx.restore();
                        ctx.font = 'bold 32px Cairo, sans-serif';
                        ctx.textBaseline = 'middle';
                        ctx.textAlign = 'center';
                        
                        const text = config.centerText.value;
                        const textX = width / 2;
                        const textY = height / 1.5;
                        
                        ctx.fillStyle = '#1f2937';
                        ctx.fillText(text, textX, textY);
                        
                        // Label below
                        ctx.font = '14px Cairo, sans-serif';
                        ctx.fillStyle = '#6b7280';
                        ctx.fillText(config.centerText.label, textX, textY + 30);
                        
                        ctx.save();
                    }
                }]
            });
        } else {
            this.charts[id] = new Chart(ctx, config);
        }
    }
}">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold">{{ __('📊 أنواع الرسوم البيانية') }}</h2>
        <p class="text-purple-100 mt-2">{{ __('جميع أنواع الرسوم المتاحة مع Chart.js') }}</p>
    </div>

    {{-- Chart Type Selector --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
            @foreach($chartTypes as $type => $info)
                <button 
                    wire:click="switchType('{{ $type }}')"
                    class="flex flex-col items-center justify-center p-4 rounded-lg border-2 transition-all duration-200 {{ $activeType === $type ? 'border-indigo-600 bg-indigo-50' : 'border-gray-200 hover:border-indigo-400 hover:bg-gray-50' }}">
                    <span class="text-3xl mb-2">{{ $info['icon'] }}</span>
                    <span class="text-sm font-medium text-gray-900">{{ $info['name_ar'] }}</span>
                    <span class="text-xs text-gray-500 mt-1 text-center">{{ $info['description'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Chart Display --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $chartTypes[$activeType]['icon'] }} {{ $chartTypes[$activeType]['name_ar'] }}
                </h3>
                <p class="text-sm text-gray-600 mt-1">{{ $chartTypes[$activeType]['description'] }}</p>
            </div>
            
            @if(isset($currentChart['percentage']))
                <div class="bg-indigo-50 px-4 py-2 rounded-lg">
                    <span class="text-2xl font-bold text-indigo-600">{{ $currentChart['percentage'] }}%</span>
                </div>
            @endif
        </div>

        {{-- LINE CHART --}}
        @if($activeType === 'line')
            <canvas id="lineChart" 
                    x-init="$nextTick(() => initChart('lineChart', @js($currentChart)))"
                    style="height: 400px;"></canvas>
        @endif

        {{-- BAR CHART --}}
        @if($activeType === 'bar')
            <canvas id="barChart" 
                    x-init="$nextTick(() => initChart('barChart', @js($currentChart)))"
                    style="height: 400px;"></canvas>
        @endif

        {{-- DOUGHNUT CHART --}}
        @if($activeType === 'doughnut')
            <div class="max-w-md mx-auto">
                <canvas id="doughnutChart" 
                        x-init="$nextTick(() => initChart('doughnutChart', @js($currentChart)))"
                        style="height: 400px;"></canvas>
            </div>
        @endif

        {{-- PIE CHART --}}
        @if($activeType === 'pie')
            <div class="max-w-md mx-auto">
                <canvas id="pieChart" 
                        x-init="$nextTick(() => initChart('pieChart', @js($currentChart)))"
                        style="height: 400px;"></canvas>
            </div>
        @endif

        {{-- RADAR CHART --}}
        @if($activeType === 'radar')
            <div class="max-w-2xl mx-auto">
                <canvas id="radarChart" 
                        x-init="$nextTick(() => initChart('radarChart', @js($currentChart)))"
                        style="height: 500px;"></canvas>
            </div>
        @endif

        {{-- GAUGE CHART --}}
        @if($activeType === 'gauge')
            <div class="max-w-md mx-auto">
                <canvas id="gaugeChart" 
                        x-init="$nextTick(() => initChart('gaugeChart', @js($currentChart)))"
                        style="height: 300px;"></canvas>
            </div>
        @endif

        {{-- PROGRESS BAR --}}
        @if($activeType === 'progress')
            <div class="max-w-2xl mx-auto">
                <canvas id="progressChart" 
                        x-init="$nextTick(() => initChart('progressChart', @js($currentChart)))"
                        style="height: 150px;"></canvas>
            </div>
        @endif

        {{-- MIXED CHART --}}
        @if($activeType === 'mixed')
            <canvas id="mixedChart" 
                    x-init="$nextTick(() => initChart('mixedChart', @js($currentChart)))"
                    style="height: 400px;"></canvas>
        @endif
    </div>

    {{-- Code Example --}}
    <div class="bg-gray-900 rounded-lg shadow-lg p-6 text-white overflow-x-auto">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-bold">{{ __('📝 مثال الكود') }}</h4>
            <button onclick="navigator.clipboard.writeText(this.parentElement.nextElementSibling.textContent)" 
                    class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 rounded text-sm transition">
                {{ __('نسخ') }}
            </button>
        </div>
        <pre class="text-sm text-green-400"><code>{{ $this->getCodeExample() }}</code></pre>
    </div>

    {{-- Use Cases --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-lg font-bold text-gray-900 mb-4">{{ __('💡 حالات الاستخدام') }}</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($activeType === 'line')
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('تتبع مسار التحويل') }}</p>
                        <p class="text-sm text-gray-600">{{ __('من البحث إلى الحجز عبر الزمن') }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('اتجاه رضا العملاء') }}</p>
                        <p class="text-sm text-gray-600">{{ __('CSAT & NPS عبر الشهور') }}</p>
                    </div>
                </div>
            @elseif($activeType === 'bar')
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('مقارنة الموانئ') }}</p>
                        <p class="text-sm text-gray-600">{{ __('متوسط زمن التخليص لكل ميناء') }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('التزام المستخلصين') }}</p>
                        <p class="text-sm text-gray-600">{{ __('نسبة الالتزام بـ SLA') }}</p>
                    </div>
                </div>
            @elseif($activeType === 'doughnut' || $activeType === 'pie')
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('حصة السوق') }}</p>
                        <p class="text-sm text-gray-600">{{ __('توزيع الخدمات حسب النوع') }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('توزيع الحالات') }}</p>
                        <p class="text-sm text-gray-600">{{ __('مكتملة/قيد التنفيذ/معلقة') }}</p>
                    </div>
                </div>
            @elseif($activeType === 'radar')
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('مقارنة المستخلصين') }}</p>
                        <p class="text-sm text-gray-600">{{ __('متعدد الأبعاد (سرعة، دقة، سعر...)') }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('تقييم الأداء') }}</p>
                        <p class="text-sm text-gray-600">{{ __('عدة معايير في رسم واحد') }}</p>
                    </div>
                </div>
            @elseif($activeType === 'gauge' || $activeType === 'progress')
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('نسبة الإنجاز') }}</p>
                        <p class="text-sm text-gray-600">{{ __('الهدف الشهري/السنوي') }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 space-x-reverse">
                    <span class="text-2xl">✓</span>
                    <div>
                        <p class="font-medium text-gray-900">{{ __('مقياس SLA') }}</p>
                        <p class="text-sm text-gray-600">{{ __('نسبة الالتزام بمستوى الخدمة') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush
</div>
