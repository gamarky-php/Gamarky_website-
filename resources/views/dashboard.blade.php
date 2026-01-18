@extends('layouts.app')
@section('title','لوحة التحكم')

@section('content')
    <div class="min-h-[60vh] flex items-center justify-center px-4 py-10" dir="rtl">
        <div class="w-full max-w-xl bg-white rounded-xl shadow p-6 space-y-4 text-center">
            <h2 class="text-2xl font-semibold text-gray-800">تم تسجيل الدخول بنجاح</h2>
            <p class="text-gray-700">مرحبًا {{ auth()->user()->name }}</p>

            <div class="flex items-center justify-center gap-3 pt-2">
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg border hover:bg-gray-50">الصفحة الرئيسية</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
