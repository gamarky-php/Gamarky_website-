@extends('layouts.dashboard')

@section('title', __('ui.dashboard.container_costs.page_title'))

@section('content')
@php
    // Demo KPI
    $kpis = [
        ['title' => __('ui.dashboard.container_costs.kpi_avg_cost'), 'value' => '$2,450', 'change' => '+5.2%', 'color' => 'blue'],
        ['title' => __('ui.dashboard.container_costs.kpi_lowest_cost'), 'value' => '$1,850', 'change' => '-3.1%', 'color' => 'green'],
        ['title' => __('ui.dashboard.container_costs.kpi_highest_cost'), 'value' => '$3,200', 'change' => '+12%', 'color' => 'red'],
        ['title' => __('ui.dashboard.container_costs.kpi_quotes_count'), 'value' => '127', 'change' => '+8', 'color' => 'purple'],
    ];
    
    // Cost breakdown
    $costBreakdown = [
        ['item' => __('ui.dashboard.container_costs.cb_1_item'), 'cost' => '1,500.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_1_note')],
        ['item' => __('ui.dashboard.container_costs.cb_2_item'), 'cost' => '185.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_2_note')],
        ['item' => __('ui.dashboard.container_costs.cb_3_item'), 'cost' => '220.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_3_note')],
        ['item' => __('ui.dashboard.container_costs.cb_4_item'), 'cost' => '75.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_4_note')],
        ['item' => __('ui.dashboard.container_costs.cb_5_item'), 'cost' => '15.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_5_note')],
        ['item' => __('ui.dashboard.container_costs.cb_6_item'), 'cost' => '50.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_6_note')],
        ['item' => __('ui.dashboard.container_costs.cb_7_item'), 'cost' => '150.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_7_note')],
        ['item' => __('ui.dashboard.container_costs.cb_8_item'), 'cost' => '85.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_8_note')],
        ['item' => __('ui.dashboard.container_costs.cb_9_item'), 'cost' => '120.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_9_note')],
        ['item' => __('ui.dashboard.container_costs.cb_10_item'), 'cost' => '50.00', 'currency' => 'USD', 'note' => __('ui.dashboard.container_costs.cb_10_note')],
    ];
    
    // Recent quotes
    $recentQuotes = [
        ['date' => '2026-01-14', 'line' => 'Maersk', 'type' => '40HC', 'pol' => 'Shanghai', 'pod' => 'Jeddah', 'price' => '$2,350', 'status' => 'new'],
        ['date' => '2026-01-13', 'line' => 'MSC', 'type' => '20GP', 'pol' => 'Dubai', 'pod' => 'Dammam', 'price' => '$1,850', 'status' => 'confirmed'],
        ['date' => '2026-01-13', 'line' => 'COSCO', 'type' => '40GP', 'pol' => 'Singapore', 'pod' => 'Jeddah', 'price' => '$2,180', 'status' => 'new'],
        ['date' => '2026-01-12', 'line' => 'CMA CGM', 'type' => '40HC', 'pol' => 'Colombo', 'pod' => 'Yanbu', 'price' => '$2,450', 'status' => 'expired'],
        ['date' => '2026-01-12', 'line' => 'Evergreen', 'type' => '20GP', 'pol' => 'Hong Kong', 'pod' => 'Jeddah', 'price' => '$1,920', 'status' => 'confirmed'],
        ['date' => '2026-01-11', 'line' => 'Hapag-Lloyd', 'type' => '40HC', 'pol' => 'Bangkok', 'pod' => 'Dammam', 'price' => '$2,600', 'status' => 'new'],
        ['date' => '2026-01-11', 'line' => 'OOCL', 'type' => '40GP', 'pol' => 'Shenzhen', 'pod' => 'Jeddah', 'price' => '$2,280', 'status' => 'confirmed'],
        ['date' => '2026-01-10', 'line' => 'Yang Ming', 'type' => '20GP', 'pol' => 'Kaohsiung', 'pod' => 'Yanbu', 'price' => '$1,780', 'status' => 'expired'],
        ['date' => '2026-01-10', 'line' => 'ONE', 'type' => '40HC', 'pol' => 'Mumbai', 'pod' => 'Jeddah', 'price' => '$2,890', 'status' => 'new'],
        ['date' => '2026-01-09', 'line' => 'ZIM', 'type' => '40GP', 'pol' => 'Haifa', 'pod' => 'Dammam', 'price' => '$3,200', 'status' => 'confirmed'],
    ];
    
    $statusConfig = [
        'new' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-800 dark:text-blue-300', 'label' => __('ui.dashboard.container_costs.status_new')],
        'confirmed' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'label' => __('ui.dashboard.container_costs.status_confirmed')],
        'expired' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-300', 'label' => __('ui.dashboard.container_costs.status_expired')],
    ];
@endphp

{{-- dir inherited from layout --}}
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('ui.dashboard.container_costs.page_title') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('ui.dashboard.container_costs.page_subtitle') }}</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            {{ __('ui.dashboard.container_costs.filters') }}
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            <!-- Container Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.container_costs.container_type') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">{{ __('ui.dashboard.container_costs.all_types') }}</option>
                    <option value="20GP">{{ __('ui.dashboard.container_costs.type_20gp') }}</option>
                    <option value="40GP">{{ __('ui.dashboard.container_costs.type_40gp') }}</option>
                    <option value="40HC">{{ __('ui.dashboard.container_costs.type_40hc') }}</option>
                    <option value="20RF">{{ __('ui.dashboard.container_costs.type_20rf') }}</option>
                    <option value="40RF">{{ __('ui.dashboard.container_costs.type_40rf') }}</option>
                </select>
            </div>

            <!-- Shipping Line -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.container_costs.shipping_line') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">{{ __('ui.dashboard.container_costs.all_lines') }}</option>
                    <option value="maersk">Maersk</option>
                    <option value="msc">MSC</option>
                    <option value="cosco">COSCO</option>
                    <option value="cma">CMA CGM</option>
                    <option value="evergreen">Evergreen</option>
                    <option value="hapag">Hapag-Lloyd</option>
                </select>
            </div>

            <!-- POL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.container_costs.pol') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">{{ __('ui.dashboard.container_costs.select_port') }}</option>
                    <option value="shanghai">Shanghai</option>
                    <option value="singapore">Singapore</option>
                    <option value="dubai">Dubai</option>
                    <option value="hongkong">Hong Kong</option>
                    <option value="mumbai">Mumbai</option>
                </select>
            </div>

            <!-- POD -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.container_costs.pod') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">{{ __('ui.dashboard.container_costs.select_port') }}</option>
                    <option value="jeddah">{{ __('ui.dashboard.container_costs.port_jeddah') }}</option>
                    <option value="dammam">{{ __('ui.dashboard.container_costs.port_dammam') }}</option>
                    <option value="yanbu">{{ __('ui.dashboard.container_costs.port_yanbu') }}</option>
                    <option value="jubail">{{ __('ui.dashboard.container_costs.port_jubail') }}</option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.container_costs.date_range') }}</label>
                <div class="flex gap-2">
                    <input type="date" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    <input type="date" class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-4">
            <button class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('ui.dashboard.container_costs.apply') }}
            </button>
            <button class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('ui.dashboard.container_costs.reset') }}
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($kpis as $kpi)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $kpi['title'] }}</h3>
                    <div class="p-2 bg-{{ $kpi['color'] }}-100 dark:bg-{{ $kpi['color'] }}-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-{{ $kpi['color'] }}-600 dark:text-{{ $kpi['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                    <span class="text-sm font-medium {{ str_contains($kpi['change'], '+') ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $kpi['change'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cost Breakdown & Recent Quotes Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Cost Breakdown Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('ui.dashboard.container_costs.cost_breakdown_title') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('ui.dashboard.container_costs.cost_breakdown_subtitle') }}</p>
            </div>

            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_item') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_cost') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_currency') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_note') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($costBreakdown as $cost)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $cost['item'] }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $cost['cost'] }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $cost['currency'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-xs text-gray-600 dark:text-gray-400">{{ $cost['note'] }}</div>
                                </td>
                            </tr>
                        @endforeach
                        <!-- Total Row -->
                        <tr class="bg-emerald-50 dark:bg-emerald-900/20 font-bold">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ __('ui.dashboard.container_costs.total') }}</td>
                            <td class="px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">2,450.00</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">USD</td>
                            <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">{{ __('ui.dashboard.container_costs.total_cost') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Quotes Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    {{ __('ui.dashboard.container_costs.recent_quotes_title') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('ui.dashboard.container_costs.recent_quotes_subtitle') }}</p>
            </div>

            <div class="overflow-x-auto max-h-[500px] overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                        <tr>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_date') }}</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_line') }}</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_type') }}</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">POL</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">POD</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_price') }}</th>
                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_costs.col_status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentQuotes as $quote)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $quote['date'] }}
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded flex items-center justify-center ml-2">
                                            <span class="text-xs font-bold text-white">{{ substr($quote['line'], 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote['line'] }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $quote['type'] }}</span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-xs text-gray-700 dark:text-gray-300">{{ $quote['pol'] }}</span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-xs text-gray-700 dark:text-gray-300">{{ $quote['pod'] }}</span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ $quote['price'] }}</span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusConfig[$quote['status']]['bg'] }} {{ $statusConfig[$quote['status']]['text'] }}">
                                        {{ $statusConfig[$quote['status']]['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
