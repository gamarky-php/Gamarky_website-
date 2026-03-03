@extends('layouts.app')

@section('title', __('auth.verify_phone') . ' - ' . __('nav.brand'))

@section('content')
{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-white py-12 px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            {{-- Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ __('auth.verify_phone') }}</h1>
                <p class="text-gray-600 text-sm">{{ __('auth.verify_phone_intro') }}</p>
            </div>

            {{-- Alert Messages --}}
            @if (session('status') == 'sms-sent')
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-center">✓ {{ __('auth.sms_sent') }}</p>
                </div>
            @endif

            @if (session('status') == 'phone-verified')
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-center">✓ {{ __('auth.phone_verified_success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            {{-- Phone Number Form --}}
            @if (!session('status') || session('status') !== 'sms-sent')
            <form method="POST" action="{{ route('phone.send') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('auth.phone_number') }}</label>
                    <input type="tel" 
                           name="phone" 
                           value="{{ old('phone', Auth::user()->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 ltr"
                           placeholder="+966501234567"
                           required>
                    <p class="text-xs text-gray-500 mt-1">{{ __('auth.example_phone') }}</p>
                </div>

                <button type="submit" 
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-lg transition-colors">
                    {{ __('auth.send_code') }}
                </button>
            </form>
            @else
            {{-- Verification Code Form --}}
            <form method="POST" action="{{ route('phone.verify.submit') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">{{ __('auth.verification_code') }}</label>
                    <input type="text" 
                           name="code" 
                           maxlength="6"
                           pattern="[0-9]{6}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-2xl tracking-widest focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="{{ __('auth.code_placeholder') }}"
                           required
                           autofocus>
                          <p class="text-sm text-gray-500 mt-2 text-center">{{ __('auth.code_placeholder') }}</p>
                </div>

                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition-colors mb-3">
                    {{ __('auth.verify_code') }}
                </button>

                <a href="{{ route('phone.verify') }}" 
                   class="block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg transition-colors">
                    {{ __('auth.change_phone') }}
                </a>
            </form>

            <p class="text-xs text-gray-500 text-center mt-4">
                {{ __('auth.code_valid_for') }}
            </p>
            @endif

            {{-- Skip Link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('front.home') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    {{ __('auth.skip_for_now') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
