{{--
Laravel Implementation Notes:
=========================

Validation Rules:
- name: required|string|max:100
- email: required|email|unique:users,email
- password: required|min:8|confirmed
- country: required|string|max:2
- phone: required|string|max:20
- activity_type: required|in:import,export,manufacturing,broker,containers,agent
- business_sector: nullable|string|max:100

Migration columns needed:
- country, phone, activity_type, business_sector
--}}

@extends('layouts.app')

@section('title', 'تسجيل حساب - جماركي')

@section('content')
<div class="bg-gradient-to-br from-slate-50 to-white py-6 px-4" dir="rtl">
  <div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

      {{-- Left Side - Welcome Card (Hidden on small screens) --}}
      <div class="hidden lg:block">
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-5 max-w-[520px] mx-auto max-h-[420px] overflow-auto">
          <h1 class="text-xl md:text-2xl font-semibold text-gray-900 mb-2">ابدأ رحلتك مع جمـاركي</h1>
          <p class="text-gray-600 mb-3 text-sm leading-tight">منصة شاملة لجميع احتياجاتك الجمركية والتجارية</p>

          <ul class="space-y-2">
            <li class="flex items-center">
              <div class="w-2 h-2 bg-amber-400 rounded-full ml-2 flex-shrink-0"></div>
              <span class="text-gray-700 text-sm leading-tight">إدارة العمليات الجمركية بسهولة</span>
            </li>
            <li class="flex items-center">
              <div class="w-2 h-2 bg-amber-400 rounded-full ml-2 flex-shrink-0"></div>
              <span class="text-gray-700 text-sm leading-tight">متابعة الشحنات والحاويات</span>
            </li>
            <li class="flex items-center">
              <div class="w-2 h-2 bg-amber-400 rounded-full ml-2 flex-shrink-0"></div>
              <span class="text-gray-700 text-sm leading-tight">شبكة واسعة من الوكلاء المعتمدين</span>
            </li>
            <li class="flex items-center">
              <div class="w-2 h-2 bg-amber-400 rounded-full ml-2 flex-shrink-0"></div>
              <span class="text-gray-700 text-sm leading-tight">تقارير مفصلة وتحليلات دقيقة</span>
            </li>
          </ul>
        </div>
      </div>

      {{-- Right Side - Registration Form --}}
      <div class="w-full">
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-5 max-w-[520px] mx-auto">

          <h2 class="text-lg font-semibold text-gray-900 mb-3">تسجيل حساب جديد</h2>

          {{-- Validation Errors --}}
          @if ($errors->any())
            <div class="mb-3 rounded-lg border border-red-200 bg-red-50 p-2 text-red-700">
              <ul class="list-disc ps-5 space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          {{-- marker: REGISTER_VIEW_ACTIVE --}}
          {{-- Social Login Buttons - Google & Apple OAuth --}}
          <div class="grid grid-cols-2 gap-2 mb-3">
            {{-- Google Button --}}
            <a href="{{ route('auth.google.redirect') }}"
               class="flex items-center justify-center h-9 px-3 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
              <svg class="w-4 h-4 me-1" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
              </svg>
              Google
            </a>

            {{-- Apple Button - Only show if route exists and Apple is enabled --}}
            @if (Route::has('auth.apple.redirect') && config('services.apple.enabled'))
              <a href="{{ route('auth.apple.redirect') }}"
                 class="flex items-center justify-center h-9 px-3 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 me-1" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                  <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                </svg>
                Apple
              </a>
            @endif
          </div>

          {{-- Divider --}}
          <div class="relative my-3">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-xs">
              <span class="px-2 bg-white text-gray-500">أو باستخدام البريد الإلكتروني</span>
            </div>
          </div>

          {{-- Registration Form --}}
          <form method="POST" action="{{ route('register.store') }}" class="space-y-3">
            @csrf

            {{-- Name Field --}}
            <div>
              <label for="name" class="block text-xs font-medium text-gray-700 mb-1">الاسم الكامل <span class="text-red-500">*</span></label>
              <input type="text"
                     id="name"
                     name="name"
                     value="{{ old('name') }}"
                     required
                     autofocus
                     autocomplete="name"
                     aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                     class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight @error('name') border-red-500 @enderror"
                     placeholder="أدخل اسمك الكامل">
              @error('name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Email Field --}}
            <div>
              <label for="email" class="block text-xs font-medium text-gray-700 mb-1">البريد الإلكتروني <span class="text-red-500">*</span></label>
              <input type="email"
                     id="email"
                     name="email"
                     value="{{ old('email') }}"
                     required
                     autocomplete="email"
                     aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                     class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight @error('email') border-red-500 @enderror"
                     placeholder="example@company.com">
              @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Password Field --}}
            <div>
              <label for="password" class="block text-xs font-medium text-gray-700 mb-1">كلمة المرور <span class="text-red-500">*</span></label>
              <input type="password"
                     id="password"
                     name="password"
                     required
                     autocomplete="new-password"
                     aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                     class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight @error('password') border-red-500 @enderror"
                     placeholder="8 أحرف على الأقل">
              @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Password Confirmation Field --}}
            <div>
              <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
              <input type="password"
                     id="password_confirmation"
                     name="password_confirmation"
                     required
                     autocomplete="new-password"
                     class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight"
                     placeholder="أعد إدخال كلمة المرور">
            </div>

            {{-- Country Field --}}
            <div>
              <label for="country" class="block text-xs font-medium text-gray-700 mb-1">الدولة <span class="text-red-500">*</span></label>
              <select id="country"
                      name="country"
                      required
                      aria-invalid="{{ $errors->has('country') ? 'true' : 'false' }}"
                      class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight @error('country') border-red-500 @enderror">
                <option value="">-- اختر الدولة --</option>
                <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>مصر</option>
                <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>السعودية</option>
                <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>الإمارات</option>
                <option value="KW" {{ old('country') == 'KW' ? 'selected' : '' }}>الكويت</option>
                <option value="QA" {{ old('country') == 'QA' ? 'selected' : '' }}>قطر</option>
                <option value="BH" {{ old('country') == 'BH' ? 'selected' : '' }}>البحرين</option>
                <option value="OM" {{ old('country') == 'OM' ? 'selected' : '' }}>عمان</option>
                <option value="JO" {{ old('country') == 'JO' ? 'selected' : '' }}>الأردن</option>
                <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>لبنان</option>
                <option value="PS" {{ old('country') == 'PS' ? 'selected' : '' }}>فلسطين</option>
                <option value="IQ" {{ old('country') == 'IQ' ? 'selected' : '' }}>العراق</option>
                <option value="SY" {{ old('country') == 'SY' ? 'selected' : '' }}>سوريا</option>
                <option value="YE" {{ old('country') == 'YE' ? 'selected' : '' }}>اليمن</option>
                <option value="MA" {{ old('country') == 'MA' ? 'selected' : '' }}>المغرب</option>
                <option value="DZ" {{ old('country') == 'DZ' ? 'selected' : '' }}>الجزائر</option>
                <option value="TN" {{ old('country') == 'TN' ? 'selected' : '' }}>تونس</option>
                <option value="LY" {{ old('country') == 'LY' ? 'selected' : '' }}>ليبيا</option>
                <option value="SD" {{ old('country') == 'SD' ? 'selected' : '' }}>السودان</option>
                <option value="MR" {{ old('country') == 'MR' ? 'selected' : '' }}>موريتانيا</option>
                <option value="DJ" {{ old('country') == 'DJ' ? 'selected' : '' }}>جيبوتي</option>
                <option value="SO" {{ old('country') == 'SO' ? 'selected' : '' }}>الصومال</option>
                <option value="KM" {{ old('country') == 'KM' ? 'selected' : '' }}>جزر القمر</option>
                <option value="XX" {{ old('country') == 'XX' ? 'selected' : '' }}>دولة أخرى</option>
              </select>
              @error('country')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Phone Field --}}
            <div>
              <label for="phone" class="block text-xs font-medium text-gray-700 mb-1">رقم الهاتف <span class="text-red-500">*</span></label>
              <input type="tel"
                     id="phone"
                     name="phone"
                     value="{{ old('phone') }}"
                     required
                     autocomplete="tel"
                     pattern="^\+?[0-9]{8,20}$"
                     aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                     class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight @error('phone') border-red-500 @enderror"
                     placeholder="+201234567890">
              <p class="mt-1 text-xs text-amber-600">
                سيتم إرسال رسالة تأكيد لاحقاً
              </p>
              @error('phone')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Activity Type Selection (Radio Buttons) --}}
            {{-- نوع النشاط --}}
<div class="mb-3">
  <label for="activity_type" class="block text-xs font-medium text-gray-700 mb-1">نوع النشاط *</label>

  <select id="activity_type" name="activity_type" required
          class="w-full h-10 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-amber-500 focus:border-amber-500 text-right">
    <option value="" disabled {{ old('activity_type') ? '' : 'selected' }}>-- اختر نوع النشاط --</option>
    <option value="import"        {{ old('activity_type')=='import' ? 'selected' : '' }}>الاستيراد</option>
    <option value="export"        {{ old('activity_type')=='export' ? 'selected' : '' }}>التصدير</option>
    <option value="manufacturing" {{ old('activity_type')=='manufacturing' ? 'selected' : '' }}>التصنيع</option>
    <option value="broker"        {{ old('activity_type')=='broker' ? 'selected' : '' }}>المستخلص الجمركي</option>
    <option value="containers"    {{ old('activity_type')=='containers' ? 'selected' : '' }}>بورصة الحاويات والنقل</option>
    <option value="agent"         {{ old('activity_type')=='agent' ? 'selected' : '' }}>الوكيل</option>
  </select>

  @error('activity_type')
    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
  @enderror
</div>


            {{-- Business Sector Field --}}
            <div>
              <label for="business_sector" class="block text-xs font-medium text-gray-700 mb-1">مجال النشاط</label>
              <select id="business_sector" name="business_sector" aria-invalid="{{ $errors->has('business_sector') ? 'true' : 'false' }}" class="w-full h-9 px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent text-right text-sm leading-tight @error('business_sector') border-red-500 @enderror">
                <option value="">-- اختر مجال النشاط --</option>
                <option value="الديكور" {{ old('business_sector') == 'الديكور' ? 'selected' : '' }}>الديكور</option>
                <option value="الملابس" {{ old('business_sector') == 'الملابس' ? 'selected' : '' }}>الملابس</option>
                <option value="قطع غيار السيارات" {{ old('business_sector') == 'قطع غيار السيارات' ? 'selected' : '' }}>قطع غيار السيارات</option>
                <option value="الإلكترونيات" {{ old('business_sector') == 'الإلكترونيات' ? 'selected' : '' }}>الإلكترونيات</option>
                <option value="المواد الغذائية" {{ old('business_sector') == 'المواد الغذائية' ? 'selected' : '' }}>المواد الغذائية</option>
                <option value="الأثاث" {{ old('business_sector') == 'الأثاث' ? 'selected' : '' }}>الأثاث</option>
                <option value="الخدمات اللوجستية" {{ old('business_sector') == 'الخدمات اللوجستية' ? 'selected' : '' }}>الخدمات اللوجستية</option>
                <option value="مواد البناء" {{ old('business_sector') == 'مواد البناء' ? 'selected' : '' }}>مواد البناء</option>
                <option value="الآلات والمعدات" {{ old('business_sector') == 'الآلات والمعدات' ? 'selected' : '' }}>الآلات والمعدات</option>
                <option value="المنتجات الطبية" {{ old('business_sector') == 'المنتجات الطبية' ? 'selected' : '' }}>المنتجات الطبية</option>
                <option value="المواد الكيماوية" {{ old('business_sector') == 'المواد الكيماوية' ? 'selected' : '' }}>المواد الكيماوية</option>
                <option value="المنسوجات" {{ old('business_sector') == 'المنسوجات' ? 'selected' : '' }}>المنسوجات</option>
                <option value="الورق والطباعة" {{ old('business_sector') == 'الورق والطباعة' ? 'selected' : '' }}>الورق والطباعة</option>
                <option value="المجوهرات والإكسسوارات" {{ old('business_sector') == 'المجوهرات والإكسسوارات' ? 'selected' : '' }}>المجوهرات والإكسسوارات</option>
                <option value="الألعاب" {{ old('business_sector') == 'الألعاب' ? 'selected' : '' }}>الألعاب</option>
                <option value="أخرى" {{ old('business_sector') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
              </select>
              @error('business_sector')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            {{-- Newsletter Subscription --}}
            <div class="flex items-center pt-1">
              <input type="checkbox" id="newsletter" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }} class="ml-2 text-amber-500 focus:ring-amber-500 rounded">
              <label for="newsletter" class="text-xs text-gray-700 leading-tight">أرغب في تلقي رسائل الأخبار والتحديثات</label>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full h-10 bg-amber-400 hover:bg-amber-500 text-white font-semibold rounded-lg transition-colors duration-200 text-sm mt-3">
              تسجيل الحساب
            </button>

            {{-- Login Link --}}
            <p class="text-center text-xs text-gray-600 pt-2">
              لديك حساب؟
              <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-500 font-medium transition-colors">تسجيل الدخول</a>
            </p>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
