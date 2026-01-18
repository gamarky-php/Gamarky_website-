@extends('layouts.front')

@section('title', 'حجز شاحنة - جماركي')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-10 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-3">
                <i class="fas fa-truck-loading ml-3"></i>
                حجز شاحنة نقل
            </h1>
            <p class="text-lg text-blue-100">
                أكمل بيانات الحجز للنقل البري
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto max-w-7xl px-4 py-10">
        
        {{-- Livewire Booking Wizard --}}
        @livewire('shipping.truck-booking-wizard')

        {{-- Advantages --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                <i class="fas fa-clock text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">توصيل سريع</h3>
                <p class="text-teal-100 text-sm">
                    خدمة توصيل سريعة وموثوقة في المواعيد المحددة
                </p>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <i class="fas fa-shield-alt text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">تأمين شامل</h3>
                <p class="text-blue-100 text-sm">
                    جميع الشحنات مؤمنة ضد الأضرار والفقد
                </p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <i class="fas fa-route text-4xl mb-4"></i>
                <h3 class="text-xl font-bold mb-2">تتبع مباشر</h3>
                <p class="text-purple-100 text-sm">
                    تتبع شحنتك لحظياً عبر GPS على مدار الساعة
                </p>
            </div>
        </div>

        {{-- Note --}}
        <div class="bg-green-50 border-r-4 border-green-500 rounded-lg p-6 mt-8 max-w-4xl mx-auto">
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-600 text-2xl ml-3 mt-1"></i>
                <div>
                    <h4 class="font-bold text-green-800 mb-2">ضمان الجودة</h4>
                    <p class="text-green-700 text-sm leading-relaxed">
                        نعمل فقط مع شركات نقل معتمدة ومرخصة. جميع السائقين مدربون ومؤهلون.
                        السعر النهائي شامل جميع الرسوم الظاهرة.
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
