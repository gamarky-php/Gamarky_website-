@extends('layouts.app')

@section('title', __('ui.auth.login.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="min-h-[70vh] bg-gradient-to-b from-slate-50 to-white py-10">
  <div class="container mx-auto px-4">
    <div class="mx-auto max-w-3xl grid grid-cols-1 md:grid-cols-2 gap-6">
      
      {{-- عمود معلومات جانبي بسيط --}}
      <div class="hidden md:flex flex-col justify-center rounded-2xl bg-white shadow p-8">
        <h2 class="text-2xl font-extrabold mb-3">{{ __('ui.auth.login.welcome_title') }}</h2>
        <p class="text-slate-600 leading-relaxed">
          {{ __('ui.auth.login.welcome_subtitle') }}
        </p>
      </div>

      {{-- بطاقة تسجيل الدخول --}}
      <div class="rounded-2xl bg-white shadow p-8">
        <h1 class="text-3xl font-black mb-6 text-center">{{ __('ui.auth.login.heading') }}</h1>

        {{-- أزرار سوشيال --}}
        <div class="space-y-3">
          <a href="{{ route('auth.google.redirect') }}" 
             class="w-full inline-flex items-center justify-center gap-3 rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
            {{-- أيقونة Google --}}
            <svg width="20" height="20" viewBox="0 0 48 48" aria-hidden="true">
              <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
              <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
              <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
              <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
              <path fill="none" d="M0 0h48v48H0z"/>
            </svg>
            <span class="font-medium">{{ __('ui.auth.login.google_login') }}</span>
          </a>
        </div>

        {{-- Divider --}}
        <div class="flex items-center gap-3 my-6">
          <div class="h-px flex-1 bg-slate-200"></div>
          <span class="text-slate-500 text-sm">{{ __('ui.auth.login.or') }}</span>
          <div class="h-px flex-1 bg-slate-200"></div>
        </div>

        {{-- الأخطاء --}}
        @if ($errors->any())
          <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            <ul class="list-disc pr-5 space-y-1">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- نموذج البريد/كلمة المرور --}}
        <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label class="block mb-1 text-sm text-slate-700">{{ __('ui.auth.common.email') }}</label>
            <input type="email" name="email" required autocomplete="email"
                   class="w-full rounded-xl border px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="name@example.com" value="{{ old('email') }}">
          </div>
          <div>
            <label class="block mb-1 text-sm text-slate-700">{{ __('ui.auth.common.password') }}</label>
            <input type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-xl border px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="••••••••">
          </div>
          <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2 text-sm">
              <input type="checkbox" name="remember" class="rounded border-slate-300">
              {{ __('ui.auth.login.remember_me') }}
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">{{ __('ui.auth.login.forgot_password') }}</a>
          </div>
          <button type="submit" class="w-full rounded-xl bg-blue-600 text-white py-3 font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
            {{ __('ui.auth.login.submit') }}
          </button>
        </form>

        <p class="text-center text-sm text-slate-600 mt-6">
          {{ __('ui.auth.login.no_account') }} <a href="{{ route('register') }}" class="text-blue-600 hover:underline">{{ __('ui.auth.login.create_account') }}</a>
        </p>
      </div>
    </div>
  </div>
</div>
@endsection
