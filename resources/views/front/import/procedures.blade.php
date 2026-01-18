@extends('layouts.front')
@section('title','دليل إجراءات الاستيراد')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

  <h1 class="text-2xl md:text-3xl font-bold mb-6">دليل إجراءات الاستيراد</h1>
  <p class="text-slate-600 mb-8">خدمة تفاعلية تُرشدك خطوة بخطوة منذ التعاقد حتى الإفراج الجمركي.</p>

  @php
    $docs = [
      'فاتورة تجارية (Commercial Invoice)',
      'بوليصة الشحن (B/L أو AWB)',
      'شهادة منشأ',
      'Packing List',
      'اعتماد مستندي/تحويل بنكي',
      'نموذج 4 (للبنوك المصرية)',
      'إذن تسليم',
    ];

    $actors = [
      'الجمارك','البنك','منصة نافذة','شركة الشحن','هيئة الرقابة على الصادرات والواردات',
      'مستخلص جمركي','مصلحة الضرائب/القيمة المضافة (عند اللزوم)'
    ];

    $steps = [
      ['t'=>'اختيار المورد والتعاقد','d'=>'تأكيد المواصفات وشروط الدفع والشحن والإنكوترمز.'],
      ['t'=>'فتح اعتماد / تحويل','d'=>'تنسيق مع البنك وإصدار نموذج 4 إن لزم.'],
      ['t'=>'الشحن والمتابعة','d'=>'الحصول على بوليصة الشحن وتتبع الرحلة.'],
      ['t'=>'التخليص المسبق','d'=>'تسجيل الشحنة على نافذة وتجهيز المستندات.'],
      ['t'=>'وصول البضاعة','d'=>'سداد الرسوم والمصروفات وإصدار إذن التسليم.'],
      ['t'=>'المعاينة والفحص','d'=>'فحص الجهات المختصة وإصدار الإفراج.'],
      ['t'=>'الإفراج الجمركي','d'=>'استلام البضاعة وترتيب النقل الداخلي.'],
    ];

    $alerts = [
      'طابق رمز HS الصحيح لتجنّب اختلاف الرسوم أو تأخير الفحص.',
      'راجع شروط الإنكوترمز لأنّها تحدد من يتحمل المخاطر والتكاليف.',
      'استخدم التخليص المسبق لتقليل زمن الإفراج.',
      'تأكد من تطابق الفاتورة، البوليصة، وقائمة التعبئة.',
    ];
  @endphp

  {{-- المستندات المطلوبة --}}
  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">المستندات المطلوبة</h2>
    <div class="grid md:grid-cols-2 gap-3">
      @foreach($docs as $i => $doc)
        <div class="flex items-start gap-3 p-4 rounded-2xl bg-white shadow">
          <span class="shrink-0 mt-1 h-6 w-6 rounded-full bg-blue-600 text-white grid place-content-center">{{ $i+1 }}</span>
          <span class="text-slate-700">{{ $doc }}</span>
        </div>
      @endforeach
    </div>
  </div>

  {{-- الجهات المعنية --}}
  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">الجهات المعنية</h2>
    <div class="flex flex-wrap gap-2">
      @foreach($actors as $a)
        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm">{{ $a }}</span>
      @endforeach
    </div>
  </div>

  {{-- الخطوات (Timeline) --}}
  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">الخطوات التنفيذية</h2>
    <ol class="relative border-s-2 border-slate-200 ps-5 space-y-6">
      @foreach($steps as $i => $s)
      <li>
        <span class="absolute -start-3.5 mt-1 h-7 w-7 rounded-full bg-blue-600 text-white text-sm grid place-content-center">{{ $i+1 }}</span>
        <h3 class="font-semibold text-slate-800">{{ $s['t'] }}</h3>
        <p class="text-slate-600">{{ $s['d'] }}</p>
      </li>
      @endforeach
    </ol>
  </div>

  {{-- تنبيهات --}}
  <div class="mb-12">
    <h2 class="text-xl font-semibold mb-3">تنبيهات مهمة</h2>
    <ul class="grid md:grid-cols-2 gap-3">
      @foreach($alerts as $al)
        <li class="p-4 rounded-2xl bg-amber-50 text-amber-900 shadow">{{ $al }}</li>
      @endforeach
    </ul>
  </div>

  {{-- أزرار لاحقة/قابلة للتطوير --}}
  <div class="flex flex-wrap gap-3">
    <a href="{{ route('front.import.calculator') }}" class="px-4 py-2 rounded-xl bg-blue-700 text-white">انتقل إلى حاسبة الاستيراد</a>
    <a href="{{ route('front.shipping.quote') ?? '#' }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">اطلب عرض سعر شحن</a>
  </div>
</div>
@endsection
