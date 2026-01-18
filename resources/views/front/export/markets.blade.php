{{-- resources/views/front/export/markets.blade.php --}}
@extends('layouts.front')

@section('title', 'إكتشف الأسواق المستهدفة')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

  {{-- HERO --}}
  <div class="rounded-3xl bg-gradient-to-l from-indigo-700 to-blue-600 text-white p-8 md:p-10 shadow mb-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-2">إكتشف الأسواق المستهدفة</h1>
    <p class="text-white/90">أداة ذكية للمُصدِّر لاختيار أفضل الأسواق حسب البند الجمركي، مع رؤى تنافسية وخدمات استشارية احترافية.</p>
  </div>

  {{-- نموذج البحث (تجريبي الآن) --}}
  <div class="rounded-2xl bg-white shadow p-5 mb-8">
    <form class="grid md:grid-cols-3 gap-4" x-data="{ hs:'', q:'' }" @submit.prevent>
      <div>
        <label class="block text-sm text-slate-600 mb-1">رقم/وصف البند الجمركي (HS Code)</label>
        <input x-model="hs" type="text" placeholder="مثال: 730890 أو مواسير صلب"
               class="w-full rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">سوق مستهدف (اختياري)</label>
        <input x-model="q" type="text" placeholder="مثال: السعودية، ألمانيا، كينيا…"
               class="w-full rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 px-3 py-2">
      </div>
      <div class="flex items-end">
        <button type="submit"
                class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-blue-700 text-white hover:bg-blue-800 transition">
          ابحث
        </button>
      </div>
      <p class="md:col-span-3 text-xs text-slate-500">*سيتم ربط النموذج لاحقًا بمصادر بيانات (UN Comtrade / ITC).</p>
    </form>
  </div>

  {{-- مؤشرات سريعة --}}
  @php
    $kpis = [
      ['t'=>'أعلى 5 دول استيرادًا','v'=>'+ تحليلات حسب HS'],
      ['t'=>'متوسط السعر العالمي','v'=>'—'],
      ['t'=>'اتجاهات آخر 3 أعوام','v'=>'—'],
      ['t'=>'تعريفة/إعفاءات','v'=>'—'],
    ];
  @endphp
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach($kpis as $k)
      <div class="rounded-2xl bg-white shadow p-4">
        <div class="text-slate-500 text-sm">{{ $k['t'] }}</div>
        <div class="text-xl font-semibold mt-1">{{ $k['v'] }}</div>
      </div>
    @endforeach
  </div>

  {{-- جدول الأسواق المقترحة (بيانات تجريبية) --}}
  @php
    $markets = [
      ['country'=>'السعودية','demand'=>'مرتفع','tariff'=>'0–5%','note'=>'طلب مستقر على السلع الإنشائية'],
      ['country'=>'الإمارات','demand'=>'مرتفع','tariff'=>'0%','note'=>'حرّة/عبور إقليمي نشط'],
      ['country'=>'ألمانيا','demand'=>'متوسط','tariff'=>'2–6%','note'=>'اشتراطات جودة مرتفعة'],
      ['country'=>'كينيا','demand'=>'متوسط','tariff'=>'5–10%','note'=>'فرص نمو بسلاسل التجزئة'],
      ['country'=>'المغرب','demand'=>'متوسط','tariff'=>'0–5%','note'=>'اتفاقيات تفضيلية عربية/أفريقية'],
    ];
  @endphp

  <div class="rounded-2xl bg-white shadow overflow-hidden mb-10">
    <div class="px-4 py-3 border-b bg-slate-50 font-semibold">الأسواق المقترحة حسب البند</div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-start">الدولة</th>
            <th class="px-4 py-3 text-start">حجم الطلب</th>
            <th class="px-4 py-3 text-start">تعريفة/تفضيلات</th>
            <th class="px-4 py-3 text-start">ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @foreach($markets as $m)
          <tr class="border-t">
            <td class="px-4 py-3">{{ $m['country'] }}</td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 rounded-full text-xs
                {{ $m['demand']=='مرتفع'?'bg-emerald-100 text-emerald-800':
                   ($m['demand']=='متوسط'?'bg-amber-100 text-amber-800':'bg-slate-100 text-slate-700') }}">
                {{ $m['demand'] }}
              </span>
            </td>
            <td class="px-4 py-3">{{ $m['tariff'] }}</td>
            <td class="px-4 py-3">{{ $m['note'] }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- خدمات مدفوعة تضيف قيمة --}}
  <div class="grid md:grid-cols-2 gap-4 mb-12">
    <div class="rounded-2xl bg-white shadow p-6">
      <h3 class="text-lg font-semibold mb-2">المصفوفة التنافسية (خدمة مدفوعة)</h3>
      <p class="text-slate-600 mb-4">مقارنة أسعار/جودة/قنوات منافسين بالسوق المستهدف مع توصيات تسعير وتموضع.</p>
      <a href="{{ route('front.export.calculator') ?? '#' }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white">
        عرض المصفوفة التنافسية
      </a>
    </div>
    <div class="rounded-2xl bg-white shadow p-6">
      <h3 class="text-lg font-semibold mb-2">استشارة خبير تصدير (مدفوعة)</h3>
      <p class="text-slate-600 mb-4">جلسة مخصصة لتحليل HS والأسواق، الشروط الفنية، التوثيق، والنفاذ التفضيلي.</p>
      <a href="{{ route('front.export.procedures') ?? '#' }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-700 text-white">
        احجز استشارة الآن
      </a>
    </div>
  </div>

  {{-- خطوات الاستخدام --}}
  @php
    $steps = [
      'أدخل رقم HS أو وصف المنتج.',
      'استعرض الدول ذات الطلب الأعلى واتجاهات السوق.',
      'اختر سوقًا واطّلع على التعريفة/الاتفاقيات.',
      'استخدم المصفوفة التنافسية أو اطلب استشارة.',
      'ابدأ إجراءات التصدير بناءً على التوصيات.',
    ];
  @endphp
  <div class="rounded-2xl bg-white shadow p-6 mb-10">
    <h3 class="text-lg font-semibold mb-4">خطوات الاستخدام</h3>
    <ol class="space-y-3">
      @foreach($steps as $i => $s)
        <li class="flex items-start gap-3">
          <span class="h-7 w-7 rounded-full bg-blue-600 text-white text-sm grid place-content-center">{{ $i+1 }}</span>
          <span class="text-slate-700">{{ $s }}</span>
        </li>
      @endforeach
    </ol>
  </div>

  {{-- ملاحظات/تنبيهات --}}
  <div class="rounded-2xl bg-amber-50 text-amber-900 shadow p-5">
    <ul class="list-disc pe-5 space-y-1">
      <li>استخدم البند الصحيح لضمان دقة النتائج.</li>
      <li>سيتم ربط الصفحة لاحقًا بـ UN Comtrade / ITC للحصول على بيانات فعلية.</li>
      <li>يمكن تفعيل اشتراك مدفوع للوصول إلى كل التقارير المتقدمة.</li>
    </ul>
  </div>

</div>
@endsection
