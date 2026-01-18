@extends('layouts.front')

@section('title', 'حجز حاوية - جماركي')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-10 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-3">
                <i class="fas fa-clipboard-check ml-3"></i>
                حجز حاوية
            </h1>
            <p class="text-lg text-blue-100">
                أكمل بيانات الحجز في 6 خطوات بسيطة
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto max-w-7xl px-4 py-10">
        
        {{-- Livewire Booking Wizard --}}
        @livewire('shipping.container-booking-wizard')

        {{-- Security Note --}}
        <div class="bg-green-50 border-r-4 border-green-500 rounded-lg p-6 mt-8 max-w-4xl mx-auto">
            <div class="flex items-start">
                <i class="fas fa-lock text-green-600 text-2xl ml-3 mt-1"></i>
                <div>
                    <h4 class="font-bold text-green-800 mb-2">معلوماتك آمنة</h4>
                    <p class="text-green-700 text-sm leading-relaxed">
                        جميع بياناتك محمية بتشفير SSL. نحن لا نشارك معلوماتك مع أي جهة خارجية دون إذنك.
                        السعر النهائي شامل جميع الرسوم الظاهرة وقد تُضاف رسوم محلية حسب الميناء.
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
