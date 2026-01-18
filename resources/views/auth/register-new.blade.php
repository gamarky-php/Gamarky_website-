@extends('layouts.front')
@section('title', 'إنشاء حساب')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-md mx-auto bg-white shadow-lg rounded-xl p-8">
        <h1 class="text-2xl font-bold text-center mb-6">إنشاء حساب جديد</h1>

        {{-- عرض الأخطاء بشكل صحيح --}}
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 p-4 text-red-700 text-sm">
                <ul class="list-disc ms-6">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4" novalidate>
            @csrf

            <div>
                <label class="block text-sm mb-1" for="name">الاسم الكامل</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm mb-1" for="email">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm mb-1" for="password">كلمة المرور</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm mb-1" for="password_confirmation">تأكيد كلمة المرور</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <button type="submit"
                    class="w-full inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                إنشاء الحساب
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">سجّل الدخول</a>
        </p>
    </div>
</div>
@endsection
