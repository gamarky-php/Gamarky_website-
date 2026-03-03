@extends('layouts.app')
@section('title', __('لوحة التحكم'))

@section('content')
    {{-- dir inherited from layout --}}
    <div class="min-h-[60vh] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-xl bg-white rounded-xl shadow p-6 space-y-4 text-center">
            <h2 class="text-2xl font-semibold text-gray-800">{{ __('تم تسجيل الدخول بنجاح') }}</h2>
            <p class="text-gray-700">{{ __('مرحبًا') }} {{ auth()->user()->name }}</p>

            <div class="flex items-center justify-center gap-3 pt-2">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">{{ __('الصفحة الرئيسية') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        {{ __('تسجيل الخروج') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
