@extends('layouts.dashboard')

@section('title', __('ui.dashboard.manufacturing_bom.page_title'))

@section('content')
@php
    // Demo KPI data
    $kpis = [
        ['title' => __('ui.dashboard.manufacturing_bom.kpi_total_materials'), 'value' => '24', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color' => 'blue'],
        ['title' => __('ui.dashboard.manufacturing_bom.kpi_total_material_cost'), 'value' => '125,450', 'currency' => __('ui.dashboard.manufacturing_bom.currency_egp'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'emerald'],
        ['title' => __('ui.dashboard.manufacturing_bom.kpi_suppliers_count'), 'value' => '8', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'purple'],
        ['title' => __('ui.dashboard.manufacturing_bom.kpi_estimated_manufacturing_time'), 'value' => '18.5', 'currency' => __('ui.dashboard.manufacturing_bom.hour'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
    ];
    
    // Demo materials
    $materials = [
        ['code' => 'MAT-001', 'name' => __('ui.dashboard.manufacturing_bom.mat_1_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_1_unit'), 'qty_per_unit' => '3.5', 'waste_pct' => '5%', 'unit_cost' => '450', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_1_supplier'), 'lead_time' => '7', 'in_stock' => true, 'updated_at' => '2026-01-14'],
        ['code' => 'MAT-002', 'name' => __('ui.dashboard.manufacturing_bom.mat_2_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_2_unit'), 'qty_per_unit' => '2', 'waste_pct' => '2%', 'unit_cost' => '180', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_2_supplier'), 'lead_time' => '3', 'in_stock' => true, 'updated_at' => '2026-01-13'],
        ['code' => 'MAT-003', 'name' => __('ui.dashboard.manufacturing_bom.mat_3_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_3_unit'), 'qty_per_unit' => '1.2', 'waste_pct' => '8%', 'unit_cost' => '320', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_3_supplier'), 'lead_time' => '5', 'in_stock' => false, 'updated_at' => '2026-01-12'],
        ['code' => 'MAT-004', 'name' => __('ui.dashboard.manufacturing_bom.mat_4_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_4_unit'), 'qty_per_unit' => '1', 'waste_pct' => '0%', 'unit_cost' => '2,500', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_4_supplier'), 'lead_time' => '14', 'in_stock' => true, 'updated_at' => '2026-01-14'],
        ['code' => 'MAT-005', 'name' => __('ui.dashboard.manufacturing_bom.mat_5_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_5_unit'), 'qty_per_unit' => '0.5', 'waste_pct' => '3%', 'unit_cost' => '650', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_5_supplier'), 'lead_time' => '4', 'in_stock' => true, 'updated_at' => '2026-01-13'],
        ['code' => 'MAT-006', 'name' => __('ui.dashboard.manufacturing_bom.mat_6_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_6_unit'), 'qty_per_unit' => '12', 'waste_pct' => '4%', 'unit_cost' => '35', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_6_supplier'), 'lead_time' => '6', 'in_stock' => false, 'updated_at' => '2026-01-11'],
        ['code' => 'MAT-007', 'name' => __('ui.dashboard.manufacturing_bom.mat_7_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_7_unit'), 'qty_per_unit' => '4', 'waste_pct' => '1%', 'unit_cost' => '890', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_7_supplier'), 'lead_time' => '10', 'in_stock' => true, 'updated_at' => '2026-01-14'],
        ['code' => 'MAT-008', 'name' => __('ui.dashboard.manufacturing_bom.mat_8_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_8_unit'), 'qty_per_unit' => '0.8', 'waste_pct' => '6%', 'unit_cost' => '420', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_8_supplier'), 'lead_time' => '3', 'in_stock' => true, 'updated_at' => '2026-01-13'],
        ['code' => 'MAT-009', 'name' => __('ui.dashboard.manufacturing_bom.mat_9_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_9_unit'), 'qty_per_unit' => '1.5', 'waste_pct' => '10%', 'unit_cost' => '750', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_9_supplier'), 'lead_time' => '8', 'in_stock' => false, 'updated_at' => '2026-01-10'],
        ['code' => 'MAT-010', 'name' => __('ui.dashboard.manufacturing_bom.mat_10_name'), 'unit' => __('ui.dashboard.manufacturing_bom.mat_10_unit'), 'qty_per_unit' => '4', 'waste_pct' => '2%', 'unit_cost' => '180', 'supplier' => __('ui.dashboard.manufacturing_bom.mat_10_supplier'), 'lead_time' => '5', 'in_stock' => true, 'updated_at' => '2026-01-14'],
    ];
    
    $operationalNotes = [
        ['title' => __('ui.dashboard.manufacturing_bom.note_1_title'), 'description' => __('ui.dashboard.manufacturing_bom.note_1_desc')],
        ['title' => __('ui.dashboard.manufacturing_bom.note_2_title'), 'description' => __('ui.dashboard.manufacturing_bom.note_2_desc')],
        ['title' => __('ui.dashboard.manufacturing_bom.note_3_title'), 'description' => __('ui.dashboard.manufacturing_bom.note_3_desc')],
        ['title' => __('ui.dashboard.manufacturing_bom.note_4_title'), 'description' => __('ui.dashboard.manufacturing_bom.note_4_desc')],
    ];
@endphp

{{-- dir inherited from layout --}}
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                📦 {{ __('ui.dashboard.manufacturing_bom.page_title') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('ui.dashboard.manufacturing_bom.page_subtitle') }}</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dashboard.manufacturing.index') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('ui.dashboard.manufacturing_bom.tab_dashboard') }}
            </a>
            <a href="{{ route('dashboard.manufacturing.costs') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                {{ __('ui.dashboard.manufacturing_bom.tab_cost_calculator') }}
            </a>
            <a href="{{ route('dashboard.manufacturing.bom') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                BOM
            </a>
            <a href="{{ route('dashboard.manufacturing.quotes') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ __('ui.dashboard.manufacturing_bom.tab_quotes') }}
            </a>
        </div>
    </div>

    <!-- BOM Setup Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ __('ui.dashboard.manufacturing_bom.setup_title') }}
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Product Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.manufacturing_bom.product') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('ui.dashboard.manufacturing_bom.select_product') }}</option>
                    <option value="A">{{ __('ui.dashboard.manufacturing_bom.product_a') }}</option>
                    <option value="B">{{ __('ui.dashboard.manufacturing_bom.product_b') }}</option>
                    <option value="C">{{ __('ui.dashboard.manufacturing_bom.product_c') }}</option>
                </select>
            </div>

            <!-- Production Quantity -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.manufacturing_bom.target_production_qty') }}</label>
                <input 
                    type="number" 
                    placeholder="{{ __('ui.dashboard.manufacturing_bom.qty_placeholder') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <!-- Unit of Measurement -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.manufacturing_bom.measurement_unit') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">{{ __('ui.dashboard.manufacturing_bom.select_unit') }}</option>
                    <option value="piece">{{ __('ui.dashboard.manufacturing_bom.unit_piece') }}</option>
                    <option value="carton">{{ __('ui.dashboard.manufacturing_bom.unit_carton') }}</option>
                    <option value="kg">{{ __('ui.dashboard.manufacturing_bom.unit_kg') }}</option>
                </select>
            </div>

            <!-- BOM Version -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('ui.dashboard.manufacturing_bom.bom_version') }}</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="v1">v1</option>
                    <option value="v2">v2</option>
                    <option value="v3">v3</option>
                </select>
            </div>

            <!-- Apply Button -->
            <div class="flex items-end">
                <button 
                    class="w-full px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center justify-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('ui.dashboard.manufacturing_bom.apply') }}
                </button>
            </div>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                    @if(isset($kpi['currency']))
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">{{ $kpi['currency'] }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Materials Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                {{ __('ui.dashboard.manufacturing_bom.materials_list_title') }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('ui.dashboard.manufacturing_bom.materials_list_subtitle') }}</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_code') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_material') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_unit') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_qty_per_product') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_waste_pct') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_unit_cost') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_supplier') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_lead_time') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_stock') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_last_update') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.manufacturing_bom.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($materials as $material)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-1.5 bg-blue-100 dark:bg-blue-900/30 rounded ml-2">
                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $material['code'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $material['name'] }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $material['unit'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $material['qty_per_unit'] }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm text-amber-600 dark:text-amber-400 font-medium">{{ $material['waste_pct'] }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-bold text-emerald-700 dark:text-emerald-400">{{ $material['unit_cost'] }} {{ __('ui.dashboard.manufacturing_bom.currency_egp') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $material['supplier'] }}">
                                    {{ $material['supplier'] }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $material['lead_time'] }} {{ __('ui.dashboard.manufacturing_bom.day') }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($material['in_stock'])
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        {{ __('ui.dashboard.manufacturing_bom.available') }}
                                    </span>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                        {{ __('ui.dashboard.manufacturing_bom.unavailable') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $material['updated_at'] }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <button disabled 
                                            class="text-gray-300 dark:text-gray-600 cursor-not-allowed" 
                                            title="{{ __('ui.dashboard.manufacturing_bom.details') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button disabled 
                                            class="text-gray-300 dark:text-gray-600 cursor-not-allowed" 
                                            title="{{ __('ui.dashboard.manufacturing_bom.replace_supplier') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Operational Notes Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            {{ __('ui.dashboard.manufacturing_bom.operational_notes') }}
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($operationalNotes as $note)
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $note['title'] }}
                    </h3>
                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $note['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
