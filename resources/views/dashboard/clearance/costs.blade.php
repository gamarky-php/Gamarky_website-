@extends('layouts.dashboard')

@section('title', 'تكاليف التخليص')

@section('content')
@php
    // بيانات KPI وهمية
    $kpis = [
        ['title' => 'إجمالي التكاليف', 'value' => '485,750', 'currency' => 'EGP', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'blue'],
        ['title' => 'المدفوع', 'value' => '342,500', 'currency' => 'EGP', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green'],
        ['title' => 'المتبقي', 'value' => '143,250', 'currency' => 'EGP', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red'],
        ['title' => 'متوسط تكلفة/ملف', 'value' => '40,479', 'currency' => 'EGP', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'color' => 'purple'],
    ];
    
    // بنود تكلفة تجريبية
    $costItems = [
        ['jobId' => 'CLR-2026-001', 'item' => 'رسوم جمركية', 'category' => 'customs', 'amount' => '45,000', 'paid' => '45,000', 'status' => 'paid', 'updated_at' => '2026-01-14 10:30'],
        ['jobId' => 'CLR-2026-001', 'item' => 'رسوم شحن', 'category' => 'shipping', 'amount' => '12,500', 'paid' => '12,500', 'status' => 'paid', 'updated_at' => '2026-01-14 10:30'],
        ['jobId' => 'CLR-2026-002', 'item' => 'رسوم تخزين', 'category' => 'storage', 'amount' => '8,750', 'paid' => '0', 'status' => 'unpaid', 'updated_at' => '2026-01-14 09:15'],
        ['jobId' => 'CLR-2026-002', 'item' => 'رسوم فحص', 'category' => 'inspection', 'amount' => '5,200', 'paid' => '5,200', 'status' => 'paid', 'updated_at' => '2026-01-14 09:15'],
        ['jobId' => 'CLR-2026-003', 'item' => 'رسوم جمركية', 'category' => 'customs', 'amount' => '38,500', 'paid' => '20,000', 'status' => 'partially_paid', 'updated_at' => '2026-01-14 08:45'],
        ['jobId' => 'CLR-2026-003', 'item' => 'رسوم أخرى', 'category' => 'other', 'amount' => '3,200', 'paid' => '0', 'status' => 'unpaid', 'updated_at' => '2026-01-14 08:45'],
        ['jobId' => 'CLR-2026-004', 'item' => 'رسوم شحن', 'category' => 'shipping', 'amount' => '15,800', 'paid' => '15,800', 'status' => 'paid', 'updated_at' => '2026-01-13 16:20'],
        ['jobId' => 'CLR-2026-005', 'item' => 'رسوم جمركية', 'category' => 'customs', 'amount' => '52,300', 'paid' => '30,000', 'status' => 'partially_paid', 'updated_at' => '2026-01-13 14:50'],
        ['jobId' => 'CLR-2026-005', 'item' => 'رسوم تخزين', 'category' => 'storage', 'amount' => '6,500', 'paid' => '6,500', 'status' => 'paid', 'updated_at' => '2026-01-13 14:50'],
        ['jobId' => 'CLR-2026-006', 'item' => 'رسوم فحص', 'category' => 'inspection', 'amount' => '4,800', 'paid' => '0', 'status' => 'unpaid', 'updated_at' => '2026-01-13 11:30'],
        ['jobId' => 'CLR-2026-007', 'item' => 'رسوم جمركية', 'category' => 'customs', 'amount' => '41,200', 'paid' => '41,200', 'status' => 'paid', 'updated_at' => '2026-01-12 15:40'],
        ['jobId' => 'CLR-2026-008', 'item' => 'رسوم أخرى', 'category' => 'other', 'amount' => '2,900', 'paid' => '1,500', 'status' => 'partially_paid', 'updated_at' => '2026-01-12 10:25'],
    ];
    
    $statusConfig = [
        'paid' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'label' => 'مدفوع'],
        'unpaid' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-300', 'label' => 'غير مدفوع'],
        'partially_paid' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-800 dark:text-yellow-300', 'label' => 'مدفوع جزئياً'],
    ];
    
    $categoryLabels = [
        'customs' => 'رسوم جمركية',
        'shipping' => 'شحن',
        'storage' => 'تخزين',
        'inspection' => 'فحص',
        'other' => 'أخرى',
    ];
@endphp

<div class="space-y-6" dir="rtl">
    
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                تكاليف التخليص
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">إدارة ومتابعة تكاليف ملفات التخليص الجمركي</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dashboard.clearance.index') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                لوحة التخليص
            </a>
            <a href="{{ route('dashboard.clearance.costs') }}" 
               class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                التكاليف
            </a>
            <a href="{{ route('dashboard.clearance.pending') }}" 
               class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                المعلّقة
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            الفلاتر
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Job ID Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">بحث عن Job ID</label>
                <input 
                    type="text" 
                    placeholder="مثال: CLR-2026-001"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="all">الكل</option>
                    <option value="paid">مدفوع</option>
                    <option value="unpaid">غير مدفوع</option>
                    <option value="partially_paid">مدفوع جزئياً</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع البند</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="all">الكل</option>
                    <option value="customs">رسوم جمركية</option>
                    <option value="shipping">شحن</option>
                    <option value="storage">تخزين</option>
                    <option value="inspection">فحص</option>
                    <option value="other">أخرى</option>
                </select>
            </div>

            <!-- Apply Button -->
            <div class="flex items-end">
                <button 
                    class="w-full px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors flex items-center justify-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    تطبيق
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
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">{{ $kpi['currency'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Cost Items Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                بنود التكلفة
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">تفاصيل تكاليف ملفات التخليص</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Job ID</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">البند</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المدفوع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">آخر تحديث</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($costItems as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg ml-3">
                                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item['jobId'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $item['item'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $categoryLabels[$item['category']] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item['amount'] }} EGP</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">{{ $item['paid'] }} EGP</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusConfig[$item['status']]['bg'] }} {{ $statusConfig[$item['status']]['text'] }}">
                                    {{ $statusConfig[$item['status']]['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $item['updated_at'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.clearance.timeline.job', ['jobId' => $item['jobId']]) }}" 
                                       class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors" 
                                       title="فتح التايملاين">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </a>
                                    <button disabled 
                                            class="text-gray-300 dark:text-gray-600 cursor-not-allowed" 
                                            title="تصدير">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    عرض <span class="font-medium">1-12</span> من <span class="font-medium">{{ count($costItems) }}</span> نتيجة
                </div>
                <div class="flex gap-2">
                    <button disabled class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-400 rounded cursor-not-allowed">
                        السابق
                    </button>
                    <button class="px-3 py-1 text-sm bg-emerald-600 text-white rounded">
                        1
                    </button>
                    <button disabled class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-400 rounded cursor-not-allowed">
                        التالي
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
