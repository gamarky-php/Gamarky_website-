@extends('layouts.dashboard')

@section('title', 'حجوزات الحاويات')

@section('content')
@php
    // بيانات وهمية للعرض
    $stats = [
        ['title' => 'إجمالي الحجوزات', 'count' => 247, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'blue'],
        ['title' => 'قيد المراجعة', 'count' => 34, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'yellow'],
        ['title' => 'مؤكدة', 'count' => 189, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green'],
        ['title' => 'ملغاة', 'count' => 24, 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red'],
    ];

    $bookings = [
        ['id' => 'BK-2026-001', 'client' => 'شركة التجارة العالمية', 'shipping_line' => 'Maersk', 'container_type' => '40 قدم (HC)', 'origin' => 'شنغهاي', 'destination' => 'جدة الإسلامي', 'status' => 'confirmed', 'created_at' => '2026-01-10'],
        ['id' => 'BK-2026-002', 'client' => 'مؤسسة الاستيراد الحديثة', 'shipping_line' => 'MSC', 'container_type' => '20 قدم (DRY)', 'origin' => 'دبي', 'destination' => 'جدة الإسلامي', 'status' => 'pending', 'created_at' => '2026-01-12'],
        ['id' => 'BK-2026-003', 'client' => 'شركة النقل البحري', 'shipping_line' => 'COSCO', 'container_type' => '40 قدم (RF)', 'origin' => 'سنغافورة', 'destination' => 'الدمام', 'status' => 'confirmed', 'created_at' => '2026-01-11'],
        ['id' => 'BK-2026-004', 'client' => 'مجموعة الشحن السريع', 'shipping_line' => 'CMA CGM', 'container_type' => '20 قدم (DRY)', 'origin' => 'كولومبو', 'destination' => 'ينبع', 'status' => 'cancelled', 'created_at' => '2026-01-09'],
        ['id' => 'BK-2026-005', 'client' => 'شركة اللوجستيات الذكية', 'shipping_line' => 'Evergreen', 'container_type' => '40 قدم (HC)', 'origin' => 'هونج كونج', 'destination' => 'جدة الإسلامي', 'status' => 'confirmed', 'created_at' => '2026-01-13'],
        ['id' => 'BK-2026-006', 'client' => 'مؤسسة الخليج التجارية', 'shipping_line' => 'Hapag-Lloyd', 'container_type' => '20 قدم (OT)', 'origin' => 'بانكوك', 'destination' => 'الدمام', 'status' => 'pending', 'created_at' => '2026-01-14'],
        ['id' => 'BK-2026-007', 'client' => 'شركة المستوردون المتحدون', 'shipping_line' => 'Maersk', 'container_type' => '40 قدم (HC)', 'origin' => 'شنتشن', 'destination' => 'جدة الإسلامي', 'status' => 'confirmed', 'created_at' => '2026-01-08'],
        ['id' => 'BK-2026-008', 'client' => 'شركة التوريدات العامة', 'shipping_line' => 'MSC', 'container_type' => '20 قدم (DRY)', 'origin' => 'دبي', 'destination' => 'ينبع', 'status' => 'confirmed', 'created_at' => '2026-01-07'],
        ['id' => 'BK-2026-009', 'client' => 'مجموعة الأعمال الدولية', 'shipping_line' => 'OOCL', 'container_type' => '40 قدم (RF)', 'origin' => 'ملبورن', 'destination' => 'الدمام', 'status' => 'pending', 'created_at' => '2026-01-12'],
        ['id' => 'BK-2026-010', 'client' => 'شركة النقل المتطور', 'shipping_line' => 'Yang Ming', 'container_type' => '45 قدم (HC)', 'origin' => 'كاوهسيونج', 'destination' => 'جدة الإسلامي', 'status' => 'confirmed', 'created_at' => '2026-01-11'],
    ];

    $statusColors = [
        'confirmed' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'label' => 'مؤكد'],
        'pending' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'text' => 'text-yellow-800 dark:text-yellow-300', 'label' => 'قيد المراجعة'],
        'cancelled' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-300', 'label' => 'ملغي'],
    ];
@endphp

<div class="space-y-6" dir="rtl">
    
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                حجوزات الحاويات
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">إدارة حجوزات الحاويات ومتابعة الشحنات</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button disabled 
                class="px-5 py-2.5 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تصدير CSV
            </button>
            <button disabled 
                class="px-5 py-2.5 bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                حجز جديد
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $stat)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $stat['title'] }}</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stat['count'] }}</p>
                    </div>
                    <div class="p-3 bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30 rounded-lg">
                        <svg class="w-8 h-8 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bookings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">قائمة الحجوزات</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">عرض جميع حجوزات الحاويات النشطة</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">رقم الحجز</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">خط الشحن</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نوع الحاوية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">المسار</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاريخ الإنشاء</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg ml-3">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $booking['id'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">{{ $booking['client'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center ml-2">
                                        <span class="text-xs font-bold text-white">{{ substr($booking['shipping_line'], 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $booking['shipping_line'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 dark:text-white">{{ $booking['container_type'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $booking['origin'] }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">{{ $booking['destination'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$booking['status']]['bg'] }} {{ $statusColors[$booking['status']]['text'] }}">
                                    {{ $statusColors[$booking['status']]['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $booking['created_at'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors" title="عرض التفاصيل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-colors" title="تعديل">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination Placeholder -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    عرض <span class="font-medium">1-10</span> من <span class="font-medium">{{ count($bookings) }}</span> نتيجة
                </div>
                <div class="flex gap-2">
                    <button disabled class="px-3 py-1 text-sm bg-gray-200 dark:bg-gray-700 text-gray-400 rounded cursor-not-allowed">
                        السابق
                    </button>
                    <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded">
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
