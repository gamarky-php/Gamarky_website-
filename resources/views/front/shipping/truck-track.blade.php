@extends('layouts.front')

@section('title', 'تتبع شاحنة - جماركي')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-route ml-3"></i>
                تتبع مسار شاحنة
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl">
                تتبع شحنتك البرية لحظة بلحظة من نقطة الانطلاق وحتى الوصول
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto max-w-7xl px-4 py-12">
        
        {{-- Livewire Tracker Component --}}
        @livewire('shipping.truck-tracker')

        {{-- Real-time Features --}}
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl p-8 text-white mt-12">
            <h3 class="text-3xl font-bold mb-6 text-center">
                <i class="fas fa-broadcast-tower ml-2"></i>
                تتبع مباشر بتقنية GPS
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map-pin text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">الموقع الحالي</h4>
                    <p class="text-sm text-blue-100">تحديث كل دقيقة</p>
                </div>

                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-tachometer-alt text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">السرعة</h4>
                    <p class="text-sm text-blue-100">قراءة فورية</p>
                </div>

                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">الوقت المتبقي</h4>
                    <p class="text-sm text-blue-100">تقدير ذكي</p>
                </div>

                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-phone-alt text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">تواصل مباشر</h4>
                    <p class="text-sm text-blue-100">مع السائق</p>
                </div>
            </div>
        </div>

        {{-- Checkpoints --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-green-500">
                <i class="fas fa-check-circle text-3xl text-green-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">نقاط التفتيش</h3>
                <p class="text-sm text-gray-600">
                    مرور الشحنة عبر نقاط تفتيش معتمدة لضمان السلامة
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-blue-500">
                <i class="fas fa-thermometer-half text-3xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">مراقبة الحرارة</h3>
                <p class="text-sm text-gray-600">
                    تتبع درجة الحرارة للشحنات المبردة على مدار الرحلة
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-purple-500">
                <i class="fas fa-bell text-3xl text-purple-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">تنبيهات ذكية</h3>
                <p class="text-sm text-gray-600">
                    إشعارات فورية عند أي تغيير أو وصول لمحطة جديدة
                </p>
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
