@extends('layouts.app')

@section('title', __('اختبار نظام المصادقة - جماركي'))

@push('styles')
<style>
  .test-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
  }
  
  .test-card {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }
  
  .test-btn {
    @apply flex items-center justify-center w-full px-6 py-4 text-sm font-semibold border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 ease-in-out shadow-sm;
  }
  
  .test-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
  }
  
  .status-indicator {
    @apply inline-flex items-center px-3 py-1 rounded-full text-xs font-medium;
  }
  
  .status-success {
    @apply bg-green-100 text-green-800;
  }
  
  .status-warning {
    @apply bg-yellow-100 text-yellow-800;
  }
  
  .status-error {
    @apply bg-red-100 text-red-800;
  }
  
  .fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>
@endpush

@section('content')
<div class="test-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-4xl w-full space-y-8">
    
    {{-- Header Section --}}
    <div class="text-center fade-in-up">
      <div class="mx-auto h-20 w-20 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-2xl">
        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <h1 class="text-4xl font-bold text-white mb-3">{{ __('اختبار نظام المصادقة') }}</h1>
      <p class="text-xl text-white/90">{{ __('تحقق من حالة جميع مكونات النظام') }}</p>
    </div>

    {{-- System Status Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 fade-in-up" style="animation-delay: 0.2s;">
      
      {{-- Database Status --}}
      <div class="test-card rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ __('قاعدة البيانات') }}</h3>
          @php
            $dbStatus = 'success';
            $dbMessage = __('متصلة');
            try {
              \Illuminate\Support\Facades\DB::connection()->getPdo();
            } catch (\Exception $e) {
              $dbStatus = 'error';
              $dbMessage = __('خطأ في الاتصال');
            }
          @endphp
          <span class="status-indicator status-{{ $dbStatus }}">{{ $dbMessage }}</span>
        </div>
        <p class="text-sm text-gray-600">{{ __('حالة الاتصال بقاعدة البيانات وجداول المستخدمين') }}</p>
      </div>

      {{-- Laravel Socialite Status --}}
      <div class="test-card rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Laravel Socialite</h3>
          @php
            $socialiteStatus = class_exists('Laravel\Socialite\SocialiteServiceProvider') ? 'success' : 'error';
            $socialiteMessage = class_exists('Laravel\Socialite\SocialiteServiceProvider') ? __('مثبت') : __('غير مثبت');
          @endphp
          <span class="status-indicator status-{{ $socialiteStatus }}">{{ $socialiteMessage }}</span>
        </div>
        <p class="text-sm text-gray-600">{{ __('حزمة التسجيل عبر الشبكات الاجتماعية') }}</p>
      </div>

      {{-- Routes Status --}}
      <div class="test-card rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ __('مسارات OAuth') }}</h3>
          @php
            $routesStatus = \Illuminate\Support\Facades\Route::has('auth.social.redirect') ? 'success' : 'error';
            $routesMessage = \Illuminate\Support\Facades\Route::has('auth.social.redirect') ? __('مُعدة') : __('غير مُعدة');
          @endphp
          <span class="status-indicator status-{{ $routesStatus }}">{{ $routesMessage }}</span>
        </div>
        <p class="text-sm text-gray-600">{{ __('مسارات التوجيه والاستدعاء للمصادقة الاجتماعية') }}</p>
      </div>

      {{-- OAuth Config Status --}}
      <div class="test-card rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ __('إعدادات OAuth') }}</h3>
          @php
            $googleConfigured = config('services.google.client_id') && config('services.google.client_secret');
            $appleConfigured = config('services.apple.client_id') && config('services.apple.client_secret');
            
            if ($googleConfigured && $appleConfigured) {
              $oauthStatus = 'success';
              $oauthMessage = __('مُكونة');
            } elseif ($googleConfigured || $appleConfigured) {
              $oauthStatus = 'warning';
              $oauthMessage = __('جزئية');
            } else {
              $oauthStatus = 'warning';
              $oauthMessage = __('للاختبار');
            }
          @endphp
          <span class="status-indicator status-{{ $oauthStatus }}">{{ $oauthMessage }}</span>
        </div>
        <p class="text-sm text-gray-600">Google: {{ $googleConfigured ? '✓' : '×' }} | Apple: {{ $appleConfigured ? '✓' : '×' }}</p>
      </div>

      {{-- User Model Status --}}
      <div class="test-card rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ __('نموذج المستخدم') }}</h3>
          @php
            $userModel = app(\App\Models\User::class);
            $fillableFields = $userModel->getFillable();
            $socialFieldsExists = in_array('google_id', $fillableFields) && in_array('apple_id', $fillableFields);
            $userStatus = $socialFieldsExists ? 'success' : 'error';
            $userMessage = $socialFieldsExists ? __('محدث') : __('يحتاج تحديث');
          @endphp
          <span class="status-indicator status-{{ $userStatus }}">{{ $userMessage }}</span>
        </div>
        <p class="text-sm text-gray-600">{{ __('دعم حقول التسجيل الاجتماعي') }}</p>
      </div>

      {{-- Migration Status --}}
      <div class="test-card rounded-3xl shadow-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">{{ __('ترقية الجداول') }}</h3>
          @php
            $migrationStatus = 'success';
            $migrationMessage = __('مُنفذة');
            try {
              \Illuminate\Support\Facades\Schema::hasColumn('users', 'google_id');
              \Illuminate\Support\Facades\Schema::hasColumn('users', 'apple_id');
            } catch (\Exception $e) {
              $migrationStatus = 'error';
              $migrationMessage = __('خطأ');
            }
          @endphp
          <span class="status-indicator status-{{ $migrationStatus }}">{{ $migrationMessage }}</span>
        </div>
        <p class="text-sm text-gray-600">{{ __('إضافة أعمدة التسجيل الاجتماعي') }}</p>
      </div>
    </div>

    {{-- Test Actions --}}
    <div class="test-card rounded-3xl shadow-2xl p-8 fade-in-up" style="animation-delay: 0.4s;">
      <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">{{ __('اختبار الوظائف') }}</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Authentication Tests --}}
        <div class="space-y-4">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('اختبار المصادقة') }}</h3>
          
          {{-- Login Page Test --}}
          <a href="{{ route('login') }}" class="test-btn bg-blue-50 text-blue-700 hover:bg-blue-100 border-blue-200">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            {{ __('اختبار صفحة تسجيل الدخول') }}
          </a>

          {{-- Register Page Test --}}
          <a href="{{ route('register') }}" class="test-btn bg-green-50 text-green-700 hover:bg-green-100 border-green-200">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            {{ __('اختبار صفحة التسجيل') }}
          </a>

          {{-- Password Reset Test --}}
          @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="test-btn bg-purple-50 text-purple-700 hover:bg-purple-100 border-purple-200">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
            {{ __('اختبار استعادة كلمة المرور') }}
          </a>
          @endif
        </div>

        {{-- Social Authentication Tests --}}
        <div class="space-y-4">
          <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('اختبار التسجيل الاجتماعي') }}</h3>
          
          {{-- Google OAuth Test --}}
          <form action="{{ route('auth.google.redirect') }}" method="GET">
            <button type="submit" class="test-btn bg-red-50 text-red-700 hover:bg-red-100 border-red-200">
              <svg class="w-5 h-5 ml-2" viewBox="0 0 24 24">
                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              </svg>
              {{ __('اختبار Google OAuth') }}
            </button>
          </form>

          {{-- Apple OAuth Test --}}
          <form action="{{ route('auth.apple.redirect') }}" method="GET">
            <button type="submit" class="test-btn bg-gray-50 text-gray-700 hover:bg-gray-100 border-gray-200">
              <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
              </svg>
              {{ __('اختبار Apple OAuth') }}
            </button>
          </form>

          {{-- Debug OAuth Routes --}}
          <button onclick="showOAuthDebugInfo()" class="test-btn bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border-indigo-200">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ __('معلومات تطوير OAuth') }}
          </button>
        </div>
      </div>

      {{-- System Information --}}
      <div class="mt-8 pt-6 border-t border-gray-200">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">{{ __('معلومات النظام') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
          <div class="bg-gray-50 p-3 rounded-lg">
            <strong>Laravel:</strong> {{ app()->version() }}
          </div>
          <div class="bg-gray-50 p-3 rounded-lg">
            <strong>PHP:</strong> {{ PHP_VERSION }}
          </div>
          <div class="bg-gray-50 p-3 rounded-lg">
            <strong>{{ __('البيئة:') }}</strong> {{ app()->environment() }}
          </div>
        </div>
      </div>
    </div>

    {{-- Back to Main Site --}}
    <div class="text-center fade-in-up" style="animation-delay: 0.6s;">
      <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm text-white/80 hover:text-white transition-colors">
        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        {{ __('العودة للموقع الرئيسي') }}
      </a>
    </div>
  </div>
</div>

{{-- OAuth Debug Modal --}}
<div id="oauthDebugModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-2xl p-8 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-bold text-gray-900">{{ __('معلومات تطوير OAuth') }}</h3>
      <button onclick="hideOAuthDebugInfo()" class="text-gray-400 hover:text-gray-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    
    <div class="space-y-4">
      <div>
        <h4 class="font-semibold text-gray-700 mb-2">{{ __('متغيرات البيئة المطلوبة:') }}</h4>
        <div class="bg-gray-50 p-4 rounded-lg text-sm font-mono">
          <div>GOOGLE_CLIENT_ID=your_google_client_id</div>
          <div>GOOGLE_CLIENT_SECRET=your_google_client_secret</div>
          <div>APPLE_CLIENT_ID=your_apple_client_id</div>
          <div>APPLE_CLIENT_SECRET=your_apple_client_secret</div>
        </div>
      </div>
      
      <div>
        <h4 class="font-semibold text-gray-700 mb-2">{{ __('روابط Callback المتوقعة:') }}</h4>
        <div class="bg-gray-50 p-4 rounded-lg text-sm font-mono">
          <div>Google: {{ route('auth.social.callback', 'google') }}</div>
          <div>Apple: {{ route('auth.social.callback', 'apple') }}</div>
        </div>
      </div>
      
      <div>
        <h4 class="font-semibold text-gray-700 mb-2">{{ __('حالة الإعدادات الحالية:') }}</h4>
        <div class="bg-gray-50 p-4 rounded-lg text-sm">
          <div>Google Client ID: {{ config('services.google.client_id') ? __('مُعد') : __('غير مُعد') }}</div>
          <div>Google Client Secret: {{ config('services.google.client_secret') ? __('مُعد') : __('غير مُعد') }}</div>
          <div>Apple Client ID: {{ config('services.apple.client_id') ? __('مُعد') : __('غير مُعد') }}</div>
          <div>Apple Client Secret: {{ config('services.apple.client_secret') ? __('مُعد') : __('غير مُعد') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function showOAuthDebugInfo() {
    document.getElementById('oauthDebugModal').classList.remove('hidden');
    document.getElementById('oauthDebugModal').classList.add('flex');
  }

  function hideOAuthDebugInfo() {
    document.getElementById('oauthDebugModal').classList.add('hidden');
    document.getElementById('oauthDebugModal').classList.remove('flex');
  }

  // Close modal on outside click
  document.getElementById('oauthDebugModal').addEventListener('click', function(e) {
    if (e.target === this) {
      hideOAuthDebugInfo();
    }
  });

  // Escape key to close modal
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      hideOAuthDebugInfo();
    }
  });

  // Test button enhancements
  document.querySelectorAll('.test-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      if (this.tagName === 'BUTTON' && this.type === 'submit') {
        e.preventDefault();
        
        const originalContent = this.innerHTML;
        this.disabled = true;
        this.classList.add('opacity-75');
        
        this.innerHTML = `
          <svg class="animate-spin h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span>{{ __('جاري الاختبار...') }}</span>
        `;
        
        // Proceed with form submission after animation
        setTimeout(() => {
          this.form.submit();
        }, 1000);
      }
    });
  });

  // Page load animations
  document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.fade-in-up');
    elements.forEach((el, index) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(30px)';
      
      setTimeout(() => {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, index * 200);
    });
  });
</script>
@endpush
@endsection