@extends('layouts.front')

@section('title', __('front.shipping.container_quote.title'))

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    
    {{-- Hero Section --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-12 px-4">
        <div class="container mx-auto max-w-7xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <i class="fas fa-ship ml-3"></i>
                {{ __('front.shipping.container_quote.heading') }}
            </h1>
            <p class="text-xl text-blue-100 max-w-3xl">
                {{ __('front.shipping.container_quote.subtitle') }}
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto max-w-7xl px-4 py-12">
        
        {{-- Livewire Component --}}
        @livewire('shipping.container-quote-form')

        {{-- Info Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-teal-500">
                <div class="flex items-center mb-4">
                    <div class="bg-teal-100 rounded-full p-3 ml-4">
                        <i class="fas fa-clock text-2xl text-teal-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">{{ __('front.shipping.container_quote.instant_quotes') }}</h3>
                </div>
                <p class="text-gray-600 text-sm">
                    {{ __('front.shipping.container_quote.instant_quotes_desc') }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-blue-500">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 rounded-full p-3 ml-4">
                        <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">{{ __('front.shipping.container_quote.transparent_prices') }}</h3>
                </div>
                <p class="text-gray-600 text-sm">
                    {{ __('front.shipping.container_quote.transparent_prices_desc') }}
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-purple-500">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 rounded-full p-3 ml-4">
                        <i class="fas fa-headset text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">{{ __('front.shipping.container_quote.continuous_support') }}</h3>
                </div>
                <p class="text-gray-600 text-sm">
                    {{ __('front.shipping.container_quote.continuous_support_desc') }}
                </p>
            </div>
        </div>

        {{-- Note --}}
        <div class="bg-orange-50 border-r-4 border-orange-400 rounded-lg p-6 mt-8">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-orange-600 text-2xl ml-3 mt-1"></i>
                <div>
                    <h4 class="font-bold text-orange-800 mb-2">{{ __('front.shipping.container_quote.important_note') }}</h4>
                    <p class="text-orange-700 text-sm leading-relaxed">
                        {{ __('front.shipping.container_quote.important_note_desc') }}
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
