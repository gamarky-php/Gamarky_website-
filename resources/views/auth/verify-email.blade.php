@extends('layouts.app')

@section('title', 'تأكيد البريد الإلكتروني - جماركي')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-white py-12 px-4" dir="rtl">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            {{-- Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">تأكيد البريد الإلكتروني</h1>
                <p class="text-gray-600 text-sm leading-relaxed">
                    قبل المتابعة، يرجى تأكيد عنوان بريدك الإلكتروني بالضغط على الرابط الذي أرسلناه إليك.
                </p>
                <p class="text-gray-700 font-medium mt-2">{{ Auth::user()->email }}</p>
            </div>

            {{-- Success Message --}}
            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-center">
                        ✓ تم إرسال رابط تأكيد جديد إلى بريدك الإلكتروني
                    </p>
                </div>
            @endif

            @if (request()->get('verified') == '1')
                <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-6">
                    <p class="text-sm font-medium text-center">
                        ✓ تم تأكيد بريدك الإلكتروني بنجاح!
                    </p>
                </div>
            @endif

            {{-- Instructions --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-900 leading-relaxed">
                    <strong>ملاحظة:</strong> تحقق من صندوق الوارد وصندوق الرسائل غير المرغوب فيها (Spam).
                    إذا لم تستلم الرسالة، يمكنك طلب إرسال رابط جديد.
                </p>
            </div>

            {{-- Resend Button --}}
            <form method="POST" action="{{ route('verification.send') }}" class="mb-6">
                @csrf
                <button type="submit" 
                        class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-lg transition-colors">
                    إعادة إرسال رابط التأكيد
                </button>
            </form>

            {{-- Action Links --}}
            <div class="flex flex-col gap-3 text-center">
                <a href="{{ route('profile.show') }}" 
                   class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    تعديل البريد الإلكتروني
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-sm text-gray-500 hover:text-gray-700">
                        تسجيل الخروج
                    </button>
                </form>
            </div>
        </div>

        {{-- Email Tips --}}
        <div class="mt-6 bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold text-gray-900 mb-2 text-sm">نصائح للعثور على الرسالة:</h3>
            <ul class="text-xs text-gray-600 space-y-1">
                <li>• تحقق من مجلد الرسائل غير المرغوبة (Spam)</li>
                <li>• ابحث عن رسالة من "جماركي" أو "noreply@"</li>
                <li>• انتظر بضع دقائق قد تستغرق الرسالة وقتاً للوصول</li>
                <li>• تأكد من كتابة البريد بشكل صحيح عند التسجيل</li>
            </ul>
        </div>
    </div>
</div>
@endsection
