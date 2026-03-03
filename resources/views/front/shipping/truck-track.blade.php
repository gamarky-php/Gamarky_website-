@extends('layouts.front')

@section('title', __('front.shipping.truck_track.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-route ml-3"></i>
                {{ __('front.shipping.truck_track.heading') }}
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl">
                {{ __('front.shipping.truck_track.subtitle') }}
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
                {{ __('front.shipping.truck_track.gps_live_title') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-map-pin text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">{{ __('front.shipping.truck_track.current_location') }}</h4>
                    <p class="text-sm text-blue-100">{{ __('front.shipping.truck_track.current_location_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-tachometer-alt text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">{{ __('front.shipping.truck_track.speed') }}</h4>
                    <p class="text-sm text-blue-100">{{ __('front.shipping.truck_track.speed_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">{{ __('front.shipping.truck_track.remaining_time') }}</h4>
                    <p class="text-sm text-blue-100">{{ __('front.shipping.truck_track.remaining_time_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="bg-white bg-opacity-20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-phone-alt text-4xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">{{ __('front.shipping.truck_track.direct_contact') }}</h4>
                    <p class="text-sm text-blue-100">{{ __('front.shipping.truck_track.direct_contact_desc') }}</p>
                </div>
            </div>
        </div>

        {{-- Checkpoints --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-green-500">
                <i class="fas fa-check-circle text-3xl text-green-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.truck_track.checkpoints') }}</h3>
                <p class="text-sm text-gray-600">
                    {{ __('front.shipping.truck_track.checkpoints_desc') }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-blue-500">
                <i class="fas fa-thermometer-half text-3xl text-blue-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.truck_track.temperature_monitoring') }}</h3>
                <p class="text-sm text-gray-600">
                    {{ __('front.shipping.truck_track.temperature_monitoring_desc') }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-r-4 border-purple-500">
                <i class="fas fa-bell text-3xl text-purple-600 mb-3"></i>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.truck_track.smart_alerts') }}</h3>
                <p class="text-sm text-gray-600">
                    {{ __('front.shipping.truck_track.smart_alerts_desc') }}
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
