@extends('layouts.app')

@section('title', 'نسيت كلمة المرور - جماركي')

@push('styles')
<style>
  /* Enhanced OEC-like forgot password styles */
  .forgot-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
  }
  
  .forgot-card {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.2);
  }
  
  .input-field {
    @apply w-full px-4 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500 bg-white;
  }
  
  .input-field:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    transform: translateY(-1px);
  }
  
  .btn-primary {
    @apply w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.4);
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
  
  /* RTL specific adjustments */
  [dir="rtl"] .input-field {
    text-align: right;
  }
  
  /* Responsive adjustments */
  @media (max-width: 640px) {
    .forgot-card {
      margin: 1rem;
      padding: 2rem 1.5rem;
    }
  }
</style>
@endpush

@section('content')
<div class="forgot-container flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-md w-full space-y-8">
    
    {{-- Header Section --}}
    <div class="text-center fade-in-up">
      <div class="mx-auto h-20 w-20 bg-gradient-to-r from-orange-600 to-red-600 rounded-2xl flex items-center justify-center mb-6 shadow-2xl">
        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1721 9z"/>
        </svg>
      </div>
      <h1 class="text-4xl font-bold text-white mb-3">استعادة كلمة المرور</h1>
      <p class="text-xl text-white/90">لا تقلق، سنساعدك</p>
      <p class="text-sm text-white/70 mt-2">أدخل بريدك الإلكتروني لإرسال رابط الاستعادة</p>
    </div>

    {{-- Main Forgot Password Card --}}
    <div class="forgot-card rounded-3xl shadow-2xl p-8 fade-in-up" style="animation-delay: 0.2s;">
      
      {{-- Status & Error Messages --}}
      @if (session('status'))
        <div class="mb-6 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-xl flex items-start">
          <svg class="w-5 h-5 text-green-600 ml-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div class="font-medium">{{ session('status') }}</div>
        </div>
      @endif

      @if ($errors->any())
        <div class="mb-6 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-xl">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-red-600 ml-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
              <div class="font-semibold mb-1">يرجى تصحيح الأخطاء التالية:</div>
              <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      {{-- Information Box --}}
      <div class="mb-6 p-4 text-sm text-blue-800 bg-blue-50 border border-blue-200 rounded-xl flex items-start">
        <svg class="w-5 h-5 text-blue-600 ml-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <div class="font-semibold mb-1">كيف تعمل العملية:</div>
          <ul class="list-disc list-inside space-y-1 text-blue-700">
            <li>أدخل بريدك الإلكتروني المسجل معنا</li>
            <li>سنرسل لك رابط استعادة كلمة المرور</li>
            <li>اتبع التعليمات في البريد لإنشاء كلمة مرور جديدة</li>
            <li>تحقق من مجلد الرسائل غير المرغوبة</li>
          </ul>
        </div>
      </div>

      {{-- Email Form --}}
      <form method="POST" action="{{ route('password.email') ?? '#' }}" class="space-y-6">
        @csrf

        {{-- Email Field --}}
        <div>
          <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">البريد الإلكتروني</label>
          <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
              </svg>
            </div>
            <input
              id="email"
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              autocomplete="email"
              autofocus
              placeholder="أدخل بريدك الإلكتروني"
              class="input-field pr-12 @error('email') border-red-500 focus:ring-red-500 @enderror"
            >
          </div>
          @error('email')
            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
          @enderror
        </div>

        {{-- Submit Button --}}
        <div class="pt-4">
          <button type="submit" class="btn-primary">
            <span class="flex items-center justify-center">
              <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a4 4 0 005.66 0L21 11M3 16l7.89 7.89a4 4 0 005.66 0L21 19"/>
              </svg>
              إرسال رابط الاستعادة
            </span>
          </button>
        </div>
      </form>

      {{-- Alternative Options --}}
      <div class="mt-8 text-center border-t border-gray-200 pt-6 space-y-4">
        <p class="text-sm text-gray-600">
          تذكرت كلمة المرور؟
          <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors">
            تسجيل الدخول
          </a>
        </p>
        
        <p class="text-sm text-gray-600">
          ليس لديك حساب؟
          <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-500 transition-colors">
            إنشاء حساب جديد
          </a>
        </p>
      </div>
    </div>

    {{-- Navigation Links --}}
    <div class="text-center space-y-4 fade-in-up" style="animation-delay: 0.4s;">
      {{-- Back to Home Link --}}
      <div>
        <a href="{{ route('front.home') }}" class="inline-flex items-center text-sm text-white/80 hover:text-white transition-colors">
          <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          العودة للصفحة الرئيسية
        </a>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Enhanced form validation
  document.querySelectorAll('.input-field').forEach(input => {
    input.addEventListener('blur', function() {
      validateField(this);
    });

    input.addEventListener('input', function() {
      clearFieldError(this);
    });
  });

  function validateField(field) {
    const value = field.value.trim();
    const fieldType = field.type;
    
    if (value === '') {
      setFieldError(field, 'هذا الحقل مطلوب');
      return false;
    }
    
    if (fieldType === 'email' && !isValidEmail(value)) {
      setFieldError(field, 'يرجى إدخال بريد إلكتروني صحيح');
      return false;
    }
    
    setFieldSuccess(field);
    return true;
  }

  function setFieldError(field, message) {
    field.classList.add('border-red-500', 'focus:ring-red-500');
    field.classList.remove('border-green-500', 'focus:ring-green-500');
    
    let errorElement = field.parentNode.querySelector('.field-error');
    if (!errorElement) {
      errorElement = document.createElement('p');
      errorElement.className = 'field-error mt-2 text-sm text-red-600 font-medium';
      field.parentNode.appendChild(errorElement);
    }
    errorElement.textContent = message;
  }

  function setFieldSuccess(field) {
    field.classList.add('border-green-500', 'focus:ring-green-500');
    field.classList.remove('border-red-500', 'focus:ring-red-500');
    clearFieldError(field);
  }

  function clearFieldError(field) {
    field.classList.remove('border-red-500', 'focus:ring-red-500', 'border-green-500', 'focus:ring-green-500');
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
      errorElement.remove();
    }
  }

  function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  // Smooth page load animations
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
      
      {{-- Success Message --}}
      @if (session('status'))
        <div class="mb-4 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg">
          <div class="flex">
            <svg class="w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
          </div>
        </div>
      @endif

      {{-- Error Messages --}}
      @if ($errors->any())
        <div class="mb-4 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-lg">
          <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Instructions --}}
      <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex">
          <svg class="w-5 h-5 text-blue-500 ml-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div class="text-sm text-blue-800">
            <p class="font-medium">كيفية إعادة تعيين كلمة المرور:</p>
            <ol class="mt-2 list-decimal list-inside space-y-1 text-xs">
              <li>أدخل بريدك الإلكتروني المسجل في النظام</li>
              <li>اضغط على زر "إرسال رابط إعادة التعيين"</li>
              <li>تحقق من صندوق الوارد في بريدك الإلكتروني</li>
              <li>اتبع الرابط لإنشاء كلمة مرور جديدة</li>
            </ol>
          </div>
        </div>
      </div>

      {{-- Reset Password Form --}}
      <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        {{-- Email Field --}}
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
          <div class="relative">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
              </svg>
            </div>
            <input
              id="email"
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              autofocus
              autocomplete="email"
              placeholder="أدخل بريدك الإلكتروني"
              class="w-full pr-10 pl-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 focus:ring-red-500 @enderror"
            >
          </div>
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Submit Button --}}
        <div>
          <button
            type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105"
          >
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            إرسال رابط إعادة التعيين
          </button>
        </div>
      </form>

      {{-- Back to Login --}}
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
          تذكرت كلمة المرور؟
          <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
            العودة لتسجيل الدخول
          </a>
        </p>
      </div>
    </div>

    {{-- Additional Help --}}
    <div class="text-center">
      <div class="bg-white rounded-lg border border-gray-100 p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-2">تحتاج مساعدة إضافية؟</h3>
        <p class="text-xs text-gray-600 mb-3">
          إذا لم تستلم البريد الإلكتروني، تحقق من مجلد الرسائل غير المرغوب فيها أو تواصل مع الدعم الفني
        </p>
        <div class="flex justify-center space-x-4 space-x-reverse">
          <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500">الدعم الفني</a>
          <span class="text-gray-300">|</span>
          <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500">الأسئلة الشائعة</a>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Add loading state to submit button
  document.querySelector('form').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = `
      <svg class="animate-spin -mr-1 ml-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      جاري الإرسال...
    `;
    
    submitBtn.disabled = true;
  });

  // Email validation feedback
  document.getElementById('email').addEventListener('input', function() {
    const email = this.value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email.length > 0 && emailPattern.test(email)) {
      this.classList.remove('border-red-500', 'focus:ring-red-500');
      this.classList.add('border-green-500', 'focus:ring-green-500');
    } else if (email.length > 0) {
      this.classList.remove('border-green-500', 'focus:ring-green-500');
      this.classList.add('border-red-500', 'focus:ring-red-500');
    } else {
      this.classList.remove('border-red-500', 'focus:ring-red-500', 'border-green-500', 'focus:ring-green-500');
    }
  });

  // Add smooth animations on page load
  document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.bg-white, .space-y-8 > div');
    elements.forEach((el, index) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      
      setTimeout(() => {
        el.style.transition = 'all 0.6s ease-out';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, index * 200);
    });
  });
</script>
@endpush
@endsection
