@extends('layouts.front')

@section('title', 'عرض سعر شاحنة - جماركي')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-truck ml-3"></i>
                عرض سعر شاحنة نقل
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl">
                احصل على عروض نقل بري داخلي وإقليمي من أفضل شركات النقل. خدمة سريعة ومضمونة.
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto max-w-7xl px-4 py-12">
        
        {{-- Livewire Component --}}
        @livewire('shipping.truck-quote-form')

        {{-- Service Types --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-teal-500">
                <div class="flex items-center mb-4">
                    <div class="bg-teal-100 rounded-full p-3 ml-4">
                        <i class="fas fa-shipping-fast text-2xl text-teal-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">نقل سريع</h3>
                </div>
                <p class="text-gray-600 text-sm">
                    خدمة توصيل سريعة للشحنات العاجلة خلال 24-48 ساعة
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 rounded-full p-3 ml-4">
                        <i class="fas fa-truck-loading text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">شحن FTL/LTL</h3>
                </div>
                <p class="text-gray-600 text-sm">
                    شاحنة كاملة أو شحنة جزئية حسب حجم بضاعتك
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 rounded-full p-3 ml-4">
                        <i class="fas fa-temperature-low text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">نقل مبرد</h3>
                </div>
                <p class="text-gray-600 text-sm">
                    شاحنات مبردة للمواد الغذائية والأدوية
                </p>
            </div>
        </div>

        {{-- Coverage Areas --}}
        <div class="bg-blue-50 rounded-xl p-8 mt-12">
            <h3 class="text-2xl font-bold text-[#0F2E5D] mb-6 text-center">
                <i class="fas fa-globe ml-2"></i>
                مناطق التغطية
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-4 shadow">
                    <i class="fas fa-map-marker-alt text-3xl text-teal-600 mb-2"></i>
                    <h4 class="font-bold text-gray-800">داخل المملكة</h4>
                    <p class="text-sm text-gray-600">جميع المدن</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <i class="fas fa-map-marker-alt text-3xl text-blue-600 mb-2"></i>
                    <h4 class="font-bold text-gray-800">دول الخليج</h4>
                    <p class="text-sm text-gray-600">GCC</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <i class="fas fa-map-marker-alt text-3xl text-purple-600 mb-2"></i>
                    <h4 class="font-bold text-gray-800">الشرق الأوسط</h4>
                    <p class="text-sm text-gray-600">MENA</p>
                </div>
                <div class="bg-white rounded-lg p-4 shadow">
                    <i class="fas fa-map-marker-alt text-3xl text-orange-600 mb-2"></i>
                    <h4 class="font-bold text-gray-800">دولي</h4>
                    <p class="text-sm text-gray-600">أوروبا وآسيا</p>
                </div>
            </div>
        </div>

        {{-- Note --}}
        <div class="bg-orange-50 border-r-4 border-orange-400 rounded-lg p-6 mt-8">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-orange-600 text-2xl ml-3 mt-1"></i>
                <div>
                    <h4 class="font-bold text-orange-800 mb-2">ملاحظة هامة</h4>
                    <p class="text-orange-700 text-sm leading-relaxed">
                        الأسعار شاملة الوقود والسائق. قد تُضاف رسوم إضافية للمناطق النائية أو التحميل/التفريغ الخاص.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('head')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { font-family: 'Cairo', sans-serif; }
</style>
@endpush
@endsection
