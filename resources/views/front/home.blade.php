@extends('layouts.front')

@section('title','الرئيسية | جماركي')

@section('content')
  <div class="container mx-auto px-4 py-12">
    {{-- Hero Section --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
      <h1 class="text-4xl font-bold text-[#143B6E] mb-4">مرحبًا بك في جماركي</h1>
      <p class="text-lg text-slate-600 mb-6">منصة رجال الأعمال للخدمات الجمركية والتجارة الدولية</p>
      <div class="flex gap-4 justify-center">
        <a href="#services" class="bg-[#143B6E] text-white px-6 py-3 rounded-lg hover:bg-[#1D4ED8] transition">استكشف الخدمات</a>
        <a href="{{ url('/register') }}" class="bg-white text-[#143B6E] border-2 border-[#143B6E] px-6 py-3 rounded-lg hover:bg-slate-50 transition">ابدأ الآن</a>
      </div>
    </div>

    {{-- Services Grid --}}
    <div id="services" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">📦</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">الاستيراد</h3>
        <p class="text-slate-600 mb-4">حاسبة الاستيراد والإجراءات الجمركية</p>
        <a href="{{ route('front.import.calculator') }}" class="text-[#2563EB] hover:underline">اعرف المزيد →</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🚢</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">التصدير</h3>
        <p class="text-slate-600 mb-4">حاسبة التصدير واستكشاف الأسواق</p>
        <a href="{{ route('front.export.calculator') }}" class="text-[#2563EB] hover:underline">اعرف المزيد →</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🏭</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">التصنيع</h3>
        <p class="text-slate-600 mb-4">حاسبة التصنيع والربط الجمركي</p>
        <a href="{{ route('front.manufacturing.calculator') }}" class="text-[#2563EB] hover:underline">اعرف المزيد →</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🧾</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">المستخدم الجمركي</h3>
        <p class="text-slate-600 mb-4">حاسبة الجمارك والصفحات الإجرائية</p>
        <a href="{{ route('front.customs.index') }}" class="text-[#2563EB] hover:underline">اعرف المزيد →</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">👤</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">الوكيل</h3>
        <p class="text-slate-600 mb-4">لوحة الوكلاء الجمركيين</p>
        <a href="{{ route('front.agent.shipping') }}" class="text-[#2563EB] hover:underline">اعرف المزيد →</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">📊</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">بورصة الحاويات</h3>
        <p class="text-slate-600 mb-4">أسعار الحاويات واختيار الحاوية المناسبة</p>
        <a href="{{ route('front.shipping.quote') }}" class="text-[#2563EB] hover:underline">اعرف المزيد →</a>
      </div>
    </div>
  </div>
@endsection
