<div dir="rtl" class="space-y-6" x-data="{ 
    charts: {},
    initChart(id, config) {
        const ctx = document.getElementById(id);
        if (!ctx) return;
        
        // Destroy existing chart if exists
        if (this.charts[id]) {
            this.charts[id].destroy();
        }
        
        this.charts[id] = new Chart(ctx, config);
    }
}">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold">📊 لوحة التحليلات والرسوم البيانية</h2>
        <p class="text-indigo-100 mt-2">تحليل شامل للأداء عبر جميع أقسام المنصة</p>
    </div>

    {{-- Navigation Tabs --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-wrap border-b border-gray-200 overflow-x-auto">
            <button wire:click="switchTab('funnel')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'funnel' ? 'text-indigo-600 border-b-2 border-indigo-600 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                📈 مسار التحويل
            </button>
            <button wire:click="switchTab('clearance')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'clearance' ? 'text-green-600 border-b-2 border-green-600 bg-green-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                ⏱️ زمن التخليص
            </button>
            <button wire:click="switchTab('errors')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'errors' ? 'text-red-600 border-b-2 border-red-600 bg-red-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                ⚠️ أخطاء المستندات
            </button>
            <button wire:click="switchTab('sla')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'sla' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                ✓ التزام SLA
            </button>
            <button wire:click="switchTab('shipping')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'shipping' ? 'text-amber-600 border-b-2 border-amber-600 bg-amber-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                🚚 أداء النقل
            </button>
            <button wire:click="switchTab('ads')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'ads' ? 'text-purple-600 border-b-2 border-purple-600 bg-purple-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                📢 أداء الإعلانات
            </button>
            <button wire:click="switchTab('satisfaction')" 
                    class="px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors duration-150 {{ $activeTab === 'satisfaction' ? 'text-emerald-600 border-b-2 border-emerald-600 bg-emerald-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50' }}">
                😊 رضا العملاء
            </button>
        </div>
    </div>

    {{-- Funnel Analytics --}}
    @if($activeTab === 'funnel')
        <div class="space-y-6">
            {{-- Filters --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الفترة الزمنية</label>
                        <select wire:model.live="period" class="w-full rounded-lg border-gray-300">
                            <option value="daily">يومي</option>
                            <option value="weekly">أسبوعي</option>
                            <option value="monthly">شهري</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">القسم</label>
                        <select wire:model.live="section" class="w-full rounded-lg border-gray-300">
                            <option value="container">حاويات</option>
                            <option value="truck">شاحنات</option>
                            <option value="import">استيراد</option>
                            <option value="export">تصدير</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">معدل التحويل الكلي</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $data['summary']['overall_conversion'] }}%</p>
                    <p class="text-xs text-gray-500 mt-2">من البحث إلى الحجز</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">بحث → عروض أسعار</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $data['summary']['search_to_quote'] }}%</p>
                    <p class="text-xs text-gray-500 mt-2">{{ number_format($data['summary']['total_quotes']) }} عرض</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">عروض → حجوزات</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $data['summary']['quote_to_booking'] }}%</p>
                    <p class="text-xs text-gray-500 mt-2">{{ number_format($data['summary']['total_bookings']) }} حجز</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">مسار التحويل - {{ $period === 'daily' ? 'يومي' : ($period === 'weekly' ? 'أسبوعي' : 'شهري') }}</h3>
                <canvas id="funnelChart" 
                        x-init="$nextTick(() => initChart('funnelChart', {
                            type: '{{ $data['chart']['type'] }}',
                            data: {
                                labels: @js($data['chart']['labels']),
                                datasets: @js($data['chart']['datasets'])
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'top', rtl: true },
                                    tooltip: { rtl: true }
                                },
                                scales: {
                                    y: { beginAtZero: true }
                                }
                            }
                        }))"
                        style="height: 400px;"></canvas>
            </div>
        </div>
    @endif

    {{-- Clearance Time Analytics --}}
    @if($activeTab === 'clearance')
        <div class="space-y-6">
            {{-- Summary KPI --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-600">متوسط زمن التخليص الكلي</p>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ $data['overall_avg'] }} <span class="text-2xl text-gray-500">يوم</span></p>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">متوسط أيام التخليص لكل ميناء</h3>
                <canvas id="clearanceChart" 
                        x-init="$nextTick(() => initChart('clearanceChart', {
                            type: '{{ $data['chart']['type'] }}',
                            data: {
                                labels: @js($data['chart']['labels']),
                                datasets: @js($data['chart']['datasets'])
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { rtl: true }
                                }
                            }
                        }))"
                        style="height: 500px;"></canvas>
            </div>

            {{-- Detailed Table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">تفاصيل الموانئ</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الميناء</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">متوسط الأيام</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحد الأدنى</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحد الأقصى</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد العمليات</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأداء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($data['ports'] as $port)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $port['port'] }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900">{{ $port['avg_days'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $port['min_days'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $port['max_days'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $port['total_clearances'] }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $port['performance'] === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $port['performance'] === 'amber' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $port['performance'] === 'red' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $port['performance'] === 'green' ? 'ممتاز' : ($port['performance'] === 'amber' ? 'جيد' : 'ضعيف') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Document Errors Analytics --}}
    @if($activeTab === 'errors')
        <div class="space-y-6">
            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">التجميع حسب</label>
                <select wire:model.live="groupBy" class="w-full rounded-lg border-gray-300 md:w-64">
                    <option value="port">ميناء</option>
                    <option value="broker">مستخلص جمركي</option>
                </select>
            </div>

            {{-- Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-600">معدل الأخطاء الكلي</p>
                <p class="text-4xl font-bold text-red-600 mt-2">{{ $data['overall_error_rate'] }}%</p>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">نسبة أخطاء المستندات - حسب {{ $groupBy === 'port' ? 'الميناء' : 'المستخلص' }}</h3>
                <canvas id="errorsChart" 
                        x-init="$nextTick(() => initChart('errorsChart', {
                            type: '{{ $data['chart']['type'] }}',
                            data: {
                                labels: @js($data['chart']['labels']),
                                datasets: @js($data['chart']['datasets'])
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y',
                                plugins: {
                                    legend: { display: false }
                                }
                            }
                        }))"
                        style="height: 500px;"></canvas>
            </div>
        </div>
    @endif

    {{-- SLA Compliance --}}
    @if($activeTab === 'sla')
        <div class="space-y-6">
            {{-- Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-600">نسبة الالتزام الكلية بـ SLA</p>
                <p class="text-4xl font-bold text-blue-600 mt-2">{{ $data['overall_compliance'] }}%</p>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">التزام المستخلصين بـ SLA</h3>
                <canvas id="slaChart" 
                        x-init="$nextTick(() => initChart('slaChart', {
                            type: '{{ $data['chart']['type'] }}',
                            data: {
                                labels: @js($data['chart']['labels']),
                                datasets: @js($data['chart']['datasets'])
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y'
                            }
                        }))"
                        style="height: 500px;"></canvas>
            </div>

            {{-- Detailed Table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">تفاصيل المستخلصين</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخلص</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">نسبة الالتزام</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">طلبات ملتزمة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجمالي الطلبات</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">متوسط الساعات الفعلية</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأداء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($data['brokers'] as $broker)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $broker['company_name'] }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-blue-600">{{ $broker['sla_compliance'] }}%</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $broker['met_sla_count'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $broker['total_requests'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ $broker['avg_actual_hours'] }}h</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $broker['performance'] === 'excellent' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $broker['performance'] === 'good' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $broker['performance'] === 'poor' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $broker['performance'] === 'excellent' ? 'ممتاز' : ($broker['performance'] === 'good' ? 'جيد' : 'ضعيف') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Shipping Performance --}}
    @if($activeTab === 'shipping')
        <div class="space-y-6">
            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع النقل</label>
                <select wire:model.live="shippingType" class="w-full rounded-lg border-gray-300 md:w-64">
                    <option value="container">حاويات</option>
                    <option value="truck">شاحنات</option>
                </select>
            </div>

            {{-- Summary --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <p class="text-sm text-gray-600">نسبة التسليم في الموعد</p>
                <p class="text-4xl font-bold text-green-600 mt-2">{{ $data['overall_on_time'] }}%</p>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">التسليم في الموعد (%)</h3>
                    <canvas id="shippingOnTimeChart" 
                            x-init="$nextTick(() => initChart('shippingOnTimeChart', {
                                type: '{{ $data['chart']['type'] }}',
                                data: {
                                    labels: @js($data['chart']['labels']),
                                    datasets: @js($data['chart']['datasets'])
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    indexAxis: 'y'
                                }
                            }))"
                            style="height: 400px;"></canvas>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">متوسط التأخير (أيام)</h3>
                    <canvas id="shippingDelayChart" 
                            x-init="$nextTick(() => initChart('shippingDelayChart', {
                                type: '{{ $data['delay_chart']['type'] }}',
                                data: {
                                    labels: @js($data['delay_chart']['labels']),
                                    datasets: @js($data['delay_chart']['datasets'])
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    indexAxis: 'y'
                                }
                            }))"
                            style="height: 400px;"></canvas>
                </div>
            </div>
        </div>
    @endif

    {{-- Ads Performance --}}
    @if($activeTab === 'ads')
        <div class="space-y-6">
            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">الفترة (أيام)</label>
                <select wire:model.live="days" class="w-full rounded-lg border-gray-300 md:w-64">
                    <option value="7">آخر 7 أيام</option>
                    <option value="30">آخر 30 يوم</option>
                    <option value="90">آخر 90 يوم</option>
                </select>
            </div>

            {{-- Summary KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">إجمالي المشاهدات</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-2">{{ number_format($data['summary']['total_impressions']) }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">معدل النقر (CTR)</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">{{ $data['summary']['overall_ctr'] }}%</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">معدل التحويل</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">{{ $data['summary']['overall_conversion_rate'] }}%</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">عائد الاستثمار (ROI)</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-2">{{ $data['summary']['overall_roi'] }}%</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">أداء الإعلانات</h3>
                <canvas id="adsChart" 
                        x-init="$nextTick(() => initChart('adsChart', {
                            type: '{{ $data['chart']['type'] }}',
                            data: {
                                labels: @js($data['chart']['labels']),
                                datasets: @js($data['chart']['datasets'])
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        }))"
                        style="height: 400px;"></canvas>
            </div>
        </div>
    @endif

    {{-- Customer Satisfaction --}}
    @if($activeTab === 'satisfaction')
        <div class="space-y-6">
            {{-- Filter --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">الفترة (أيام)</label>
                <select wire:model.live="days" class="w-full rounded-lg border-gray-300 md:w-64">
                    <option value="30">آخر 30 يوم</option>
                    <option value="90">آخر 90 يوم</option>
                    <option value="180">آخر 180 يوم</option>
                </select>
            </div>

            {{-- Summary KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">CSAT (رضا العملاء)</p>
                    <p class="text-4xl font-bold text-green-600 mt-2">{{ $data['summary']['avg_csat'] }}<span class="text-xl text-gray-500">/10</span></p>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">NPS (صافي نقاط الترويج)</p>
                    <p class="text-4xl font-bold text-blue-600 mt-2">{{ $data['summary']['overall_nps'] }}</p>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full mt-2 inline-block
                        {{ $data['summary']['nps_category'] === 'excellent' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $data['summary']['nps_category'] === 'good' ? 'bg-amber-100 text-amber-800' : '' }}
                        {{ $data['summary']['nps_category'] === 'poor' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $data['summary']['nps_category'] === 'excellent' ? 'ممتاز' : ($data['summary']['nps_category'] === 'good' ? 'جيد' : 'ضعيف') }}
                    </span>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-600">إجمالي التقييمات</p>
                    <p class="text-4xl font-bold text-indigo-600 mt-2">{{ number_format($data['summary']['total_responses']) }}</p>
                </div>
            </div>

            {{-- Chart --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">اتجاه رضا العملاء</h3>
                <canvas id="satisfactionChart" 
                        x-init="$nextTick(() => initChart('satisfactionChart', {
                            type: '{{ $data['chart']['type'] }}',
                            data: {
                                labels: @js($data['chart']['labels']),
                                datasets: @js($data['chart']['datasets'])
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: { beginAtZero: true, max: 10 }
                                }
                            }
                        }))"
                        style="height: 400px;"></canvas>
            </div>
        </div>
    @endif

    {{-- Chart.js CDN --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush
</div>
