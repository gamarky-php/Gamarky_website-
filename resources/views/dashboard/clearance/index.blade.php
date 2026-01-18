@extends('layouts.dashboard')

@section('title', 'التخليص الجمركي')

@section('content')
@php
    // بيانات KPI وهمية
    $kpis = [
        ['title' => 'ملفات اليوم', 'value' => '12', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'blue'],
        ['title' => 'قيد المراجعة', 'value' => '8', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'purple'],
        ['title' => 'تحتاج مستندات', 'value' => '5', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'yellow'],
        ['title' => 'تم التخليص', 'value' => '156', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green'],
    ];
    
    // ملفات تخليص تجريبية
    $clearanceJobs = [
        ['jobId' => 'CLR-2026-001', 'importer' => 'شركة التجارة العالمية', 'port' => 'جدة الإسلامي', 'status' => 'cleared', 'updated_at' => '2026-01-14 10:30'],
        ['jobId' => 'CLR-2026-002', 'importer' => 'مؤسسة الاستيراد الحديثة', 'port' => 'الدمام', 'status' => 'under_review', 'updated_at' => '2026-01-14 09:15'],
        ['jobId' => 'CLR-2026-003', 'importer' => 'شركة النقل البحري', 'port' => 'جدة الإسلامي', 'status' => 'needs_documents', 'updated_at' => '2026-01-14 08:45'],
        ['jobId' => 'CLR-2026-004', 'importer' => 'مجموعة الشحن السريع', 'port' => 'ينبع', 'status' => 'cleared', 'updated_at' => '2026-01-13 16:20'],
        ['jobId' => 'CLR-2026-005', 'importer' => 'شركة اللوجستيات الذكية', 'port' => 'الدمام', 'status' => 'under_review', 'updated_at' => '2026-01-13 14:50'],
        ['jobId' => 'CLR-2026-006', 'importer' => 'مؤسسة الخليج التجارية', 'port' => 'جدة الإسلامي', 'status' => 'needs_documents', 'updated_at' => '2026-01-13 11:30'],
        ['jobId' => 'CLR-2026-007', 'importer' => 'شركة المستوردون المتحدون', 'port' => 'الجبيل', 'status' => 'cleared', 'updated_at' => '2026-01-12 15:40'],
        ['jobId' => 'CLR-2026-008', 'importer' => 'شركة التوريدات العامة', 'port' => 'جدة الإسلامي', 'status' => 'rejected', 'updated_at' => '2026-01-12 10:25'],
        ['jobId' => 'CLR-2026-009', 'importer' => 'مجموعة الأعمال الدولية', 'port' => 'الدمام', 'status' => 'under_review', 'updated_at' => '2026-01-11 13:15'],
        ['jobId' => 'CLR-2026-010', 'importer' => 'شركة النقل المتطور', 'port' => 'ينبع', 'status' => 'cleared', 'updated_at' => '2026-01-11 09:00'],
    ];
    
    $statusConfig = [
        'cleared' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'label' => 'تم التخليص'],
        'under_review' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-800 dark:text-blue-300', 'label' => 'قيد المراجعة'],
        'needs_documents' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-800 dark:text-yellow-300', 'label' => 'تحتاج مستندات'],
        'rejected' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-300', 'label' => 'مرفوض'],
    ];
@endphp

<div class="space-y-6" dir="rtl">
    
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                التخليص الجمركي
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">إدارة ومتابعة ملفات التخليص الجمركي</p>
        </div>
    </div>

    <!-- Navigation Tabs & Search -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">
            <!-- Tabs -->
            <div class="flex-1">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.clearance.index') }}" 
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        لوحة التخليص
                    </a>
                    <a href="{{ route('dashboard.clearance.costs') }}" 
                       class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors flex items-center gap-2">
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

            <!-- Search Box -->
            <div class="flex gap-2">
                <input 
                    type="text" 
                    id="jobIdSearch"
                    placeholder="رقم ملف التخليص (مثال: CLR-2026-001)"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                >
                <button 
                    id="searchBtn"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    بحث
                </button>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($kpis as $kpi)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $kpi['title'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                    </div>
                    <div class="p-3 bg-{{ $kpi['color'] }}-100 dark:bg-{{ $kpi['color'] }}-900/30 rounded-lg">
                        <svg class="w-8 h-8 text-{{ $kpi['color'] }}-600 dark:text-{{ $kpi['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Clearance Jobs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                قائمة ملفات التخليص
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">عرض جميع ملفات التخليص النشطة</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Job ID</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المستورد</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الميناء</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">آخر تحديث</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($clearanceJobs as $job)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg ml-3">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $job['jobId'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $job['importer'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                    <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $job['port'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusConfig[$job['status']]['bg'] }} {{ $statusConfig[$job['status']]['text'] }}">
                                    {{ $statusConfig[$job['status']]['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $job['updated_at'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('dashboard.clearance.timeline.job', ['jobId' => $job['jobId']]) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors" 
                                       title="عرض التايملاين">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('dashboard.clearance.costs') }}" 
                                       class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors" 
                                       title="التكاليف">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    <button disabled 
                                            class="text-gray-300 dark:text-gray-600 cursor-not-allowed" 
                                            title="تحديد كمعلّق">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
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
                    عرض <span class="font-medium">1-10</span> من <span class="font-medium">{{ count($clearanceJobs) }}</span> نتيجة
                </div>
                <div class="flex gap-2">
                    <button disabled class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-400 rounded cursor-not-allowed">
                        السابق
                    </button>
                    <button class="px-3 py-1 text-sm bg-indigo-600 text-white rounded">
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

<script>
// بحث عن ملف تخليص والتوجيه للتايملاين
document.getElementById('searchBtn').addEventListener('click', function() {
    const jobId = document.getElementById('jobIdSearch').value.trim();
    if (jobId) {
        const url = "{{ route('dashboard.clearance.timeline.job', ['jobId' => '__JOB_ID__']) }}".replace('__JOB_ID__', encodeURIComponent(jobId));
        window.location.href = url;
    } else {
        alert('الرجاء إدخال رقم ملف التخليص');
    }
});

// السماح بالضغط على Enter للبحث
document.getElementById('jobIdSearch').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('searchBtn').click();
    }
});
</script>
@endsection
