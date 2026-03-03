@extends('layouts.front')

@section('title', __('front.shipping.container_track.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-map-marked-alt ml-3"></i>
                {{ __('front.shipping.container_track.heading') }}
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl">
                {{ __('front.shipping.container_track.subtitle') }}
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto max-w-7xl px-4 py-12">
        
        {{-- Livewire Tracker Component --}}
        @livewire('shipping.container-tracker')

        {{-- Features Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-satellite text-3xl text-blue-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.container_track.gps_tracking') }}</h3>
                <p class="text-sm text-gray-600">{{ __('front.shipping.container_track.gps_tracking_desc') }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell text-3xl text-green-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.container_track.instant_alerts') }}</h3>
                <p class="text-sm text-gray-600">{{ __('front.shipping.container_track.instant_alerts_desc') }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-3xl text-purple-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.container_track.full_history') }}</h3>
                <p class="text-sm text-gray-600">{{ __('front.shipping.container_track.full_history_desc') }}</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-pdf text-3xl text-orange-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 mb-2">{{ __('front.shipping.container_track.pdf_reports') }}</h3>
                <p class="text-sm text-gray-600">{{ __('front.shipping.container_track.pdf_reports_desc') }}</p>
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
