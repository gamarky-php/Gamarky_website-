<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        لوحة التحكم الرئيسية
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        آخر تحديث: {{ $lastUpdate }}
                    </p>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Auto Refresh Toggle --}}
                    <button 
                        wire:click="toggleAutoRefresh"
                        class="inline-flex items-center px-4 py-2 rounded-lg transition-all
                            {{ $autoRefresh 
                                ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' 
                                : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' 
                            }}"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        {{ $autoRefresh ? 'التحديث التلقائي مفعّل' : 'التحديث التلقائي معطّل' }}
                    </button>
                    
                    {{-- Manual Refresh --}}
                    <button 
                        wire:click="refresh"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg 
                            hover:bg-indigo-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        تحديث الآن
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" 
        @if($autoRefresh) wire:poll.60s="refresh" @endif>
        
        {{-- KPI Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            
            {{-- 1. إجمالي الحجوزات هذا الشهر --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-indigo-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    @if($totalBookings['trend']['direction'] !== 'neutral')
                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                            {{ $totalBookings['trend']['color'] === 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            @if($totalBookings['trend']['direction'] === 'up')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ $totalBookings['trend']['percentage'] }}%
                        </span>
                    @endif
                </div>
                
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                    إجمالي الحجوزات
                </h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ number_format($totalBookings['value']) }}
                </p>
                
                {{-- Breakdown --}}
                <div class="grid grid-cols-2 gap-2 text-xs">
                    @foreach($totalBookings['breakdown'] as $type => $count)
                        <div class="flex justify-between">
                            <span class="text-gray-500">{{ $type }}:</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. متوسط زمن التخليص --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 
                {{ $avgClearanceTime['color'] === 'green' ? 'border-green-500' : 
                   ($avgClearanceTime['color'] === 'amber' ? 'border-amber-500' : 'border-red-500') }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg
                        {{ $avgClearanceTime['color'] === 'green' ? 'bg-green-100 dark:bg-green-900' : 
                           ($avgClearanceTime['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900' : 'bg-red-100 dark:bg-red-900') }}">
                        <svg class="w-6 h-6 
                            {{ $avgClearanceTime['color'] === 'green' ? 'text-green-600 dark:text-green-400' : 
                               ($avgClearanceTime['color'] === 'amber' ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if($avgClearanceTime['trend']['direction'] !== 'neutral')
                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                            {{ $avgClearanceTime['trend']['color'] === 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            @if($avgClearanceTime['trend']['direction'] === 'down')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ $avgClearanceTime['trend']['percentage'] }}%
                        </span>
                    @endif
                </div>
                
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                    متوسط زمن التخليص
                </h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ $avgClearanceTime['value'] }} <span class="text-lg">{{ $avgClearanceTime['unit'] }}</span>
                </p>
                
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-500">الهدف:</span>
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                        {{ $avgClearanceTime['target'] }} أيام
                    </span>
                </div>
            </div>

            {{-- 3. نسبة الالتزام بالمواعيد --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 
                {{ $onTimeRate['color'] === 'green' ? 'border-green-500' : 
                   ($onTimeRate['color'] === 'amber' ? 'border-amber-500' : 'border-red-500') }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg
                        {{ $onTimeRate['color'] === 'green' ? 'bg-green-100 dark:bg-green-900' : 
                           ($onTimeRate['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900' : 'bg-red-100 dark:bg-red-900') }}">
                        <svg class="w-6 h-6 
                            {{ $onTimeRate['color'] === 'green' ? 'text-green-600 dark:text-green-400' : 
                               ($onTimeRate['color'] === 'amber' ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if($onTimeRate['trend']['direction'] !== 'neutral')
                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                            {{ $onTimeRate['trend']['color'] === 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            @if($onTimeRate['trend']['direction'] === 'up')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ $onTimeRate['trend']['percentage'] }}%
                        </span>
                    @endif
                </div>
                
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                    الالتزام بالمواعيد
                </h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ $onTimeRate['value'] }}<span class="text-lg">{{ $onTimeRate['unit'] }}</span>
                </p>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">في الموعد:</span>
                        <span class="font-semibold text-green-600">{{ $onTimeRate['on_time'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">الهدف:</span>
                        <span class="font-semibold text-indigo-600">{{ $onTimeRate['target'] }}%</span>
                    </div>
                </div>
            </div>

            {{-- 4. إيراد الاشتراكات --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-emerald-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if($subscriptionRevenue['trend']['direction'] !== 'neutral')
                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                            {{ $subscriptionRevenue['trend']['color'] === 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            @if($subscriptionRevenue['trend']['direction'] === 'up')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ $subscriptionRevenue['trend']['percentage'] }}%
                        </span>
                    @endif
                </div>
                
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                    إيراد الاشتراكات
                </h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ $subscriptionRevenue['formatted'] }}
                </p>
                
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-500">اشتراكات جديدة:</span>
                    <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                        {{ $subscriptionRevenue['new_subscriptions'] }}
                    </span>
                </div>
            </div>

            {{-- 5. CTR للإعلانات --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 
                {{ $adsCtr['color'] === 'green' ? 'border-green-500' : 
                   ($adsCtr['color'] === 'amber' ? 'border-amber-500' : 'border-red-500') }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 rounded-lg
                        {{ $adsCtr['color'] === 'green' ? 'bg-green-100 dark:bg-green-900' : 
                           ($adsCtr['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900' : 'bg-red-100 dark:bg-red-900') }}">
                        <svg class="w-6 h-6 
                            {{ $adsCtr['color'] === 'green' ? 'text-green-600 dark:text-green-400' : 
                               ($adsCtr['color'] === 'amber' ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400') }}" 
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    @if($adsCtr['trend']['direction'] !== 'neutral')
                        <span class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full
                            {{ $adsCtr['trend']['color'] === 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            @if($adsCtr['trend']['direction'] === 'up')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            {{ $adsCtr['trend']['percentage'] }}%
                        </span>
                    @endif
                </div>
                
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                    معدل النقر CTR
                </h3>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    {{ $adsCtr['value'] }}<span class="text-lg">{{ $adsCtr['unit'] }}</span>
                </p>
                
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">نقرات:</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">
                            {{ number_format($adsCtr['clicks']) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">الهدف:</span>
                        <span class="font-semibold text-indigo-600">{{ $adsCtr['target'] }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- 1. Funnel Chart (أسبوعي) --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        قمع التحويل - {{ $funnelPeriod === 'daily' ? 'يومي' : ($funnelPeriod === 'weekly' ? 'أسبوعي' : 'شهري') }}
                    </h2>
                    
                    {{-- Period Selector --}}
                    <div class="flex gap-2">
                        <button 
                            wire:click="updateFunnelPeriod('daily')"
                            class="px-3 py-1 text-sm rounded-lg transition-colors
                                {{ $funnelPeriod === 'daily' 
                                    ? 'bg-indigo-600 text-white' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            يومي
                        </button>
                        <button 
                            wire:click="updateFunnelPeriod('weekly')"
                            class="px-3 py-1 text-sm rounded-lg transition-colors
                                {{ $funnelPeriod === 'weekly' 
                                    ? 'bg-indigo-600 text-white' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            أسبوعي
                        </button>
                        <button 
                            wire:click="updateFunnelPeriod('monthly')"
                            class="px-3 py-1 text-sm rounded-lg transition-colors
                                {{ $funnelPeriod === 'monthly' 
                                    ? 'bg-indigo-600 text-white' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            شهري
                        </button>
                    </div>
                </div>
                
                {{-- Canvas --}}
                <div class="relative h-80">
                    <canvas id="funnelChart"></canvas>
                </div>
                
                {{-- Summary --}}
                @if(isset($funnelChart['summary']))
                <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">معدل التحويل الكلي</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ number_format($funnelChart['summary']['overall_conversion'], 1) }}%
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">بحث → عروض</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($funnelChart['summary']['search_to_quote'], 1) }}%
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">عروض → حجز</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($funnelChart['summary']['quote_to_booking'], 1) }}%
                        </p>
                    </div>
                </div>
                @endif
            </div>

            {{-- 2. أداء الموانئ (Bar Chart) --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                    أداء الموانئ
                </h2>
                
                {{-- Canvas --}}
                <div class="relative h-80">
                    <canvas id="portsChart"></canvas>
                </div>
                
                {{-- Average --}}
                @if(isset($portsChart['average']))
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">المتوسط العام</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($portsChart['average'], 1) }} يوم
                    </p>
                </div>
                @endif
            </div>

            {{-- 3. توزيع أنواع الحاويات (Doughnut) --}}
            <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                    توزيع أنواع الحاويات (آخر 30 يوم)
                </h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Chart --}}
                    <div class="lg:col-span-1 flex items-center justify-center">
                        <div class="relative w-64 h-64">
                            <canvas id="containersChart"></canvas>
                        </div>
                    </div>
                    
                    {{-- Legend with percentages --}}
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-2 gap-4">
                            @if(isset($containersChart['labels']))
                                @foreach($containersChart['labels'] as $index => $label)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-4 h-4 rounded-full" 
                                            style="background-color: {{ $containersChart['datasets'][0]['backgroundColor'][$index] }}">
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $label }}
                                        </span>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $containersChart['datasets'][0]['data'][$index] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $containersChart['percentages'][$index] }}%
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        <div class="mt-4 p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-300">إجمالي الحاويات</p>
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ number_format($containersChart['total'] ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let funnelChartInstance = null;
    let portsChartInstance = null;
    let containersChartInstance = null;

    function initCharts() {
        // 1. Funnel Chart
        if (funnelChartInstance) funnelChartInstance.destroy();
        const funnelCtx = document.getElementById('funnelChart');
        if (funnelCtx) {
            const funnelData = @json($funnelChart);
            funnelChartInstance = new Chart(funnelCtx, {
                type: funnelData.type,
                data: {
                    labels: funnelData.labels,
                    datasets: funnelData.datasets
                },
                options: funnelData.options
            });
        }

        // 2. Ports Chart
        if (portsChartInstance) portsChartInstance.destroy();
        const portsCtx = document.getElementById('portsChart');
        if (portsCtx) {
            const portsData = @json($portsChart);
            portsChartInstance = new Chart(portsCtx, {
                type: portsData.type,
                data: {
                    labels: portsData.labels,
                    datasets: portsData.datasets
                },
                options: portsData.options
            });
        }

        // 3. Containers Chart
        if (containersChartInstance) containersChartInstance.destroy();
        const containersCtx = document.getElementById('containersChart');
        if (containersCtx) {
            const containersData = @json($containersChart);
            containersChartInstance = new Chart(containersCtx, {
                type: containersData.type,
                data: {
                    labels: containersData.labels,
                    datasets: containersData.datasets
                },
                options: containersData.options
            });
        }
    }

    initCharts();

    // Re-render charts on Livewire update
    Livewire.on('dashboard-refreshed', () => {
        setTimeout(initCharts, 100);
    });

    // Re-render on wire:navigate
    document.addEventListener('livewire:navigated', () => {
        setTimeout(initCharts, 100);
    });
});
</script>
@endpush
