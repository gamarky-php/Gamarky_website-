@extends('layouts.front')
@section('title','دليل إجراءات التصدير')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
  <h1 class="text-2xl md:text-3xl font-bold mb-6">دليل إجراءات التصدير</h1>
  <p class="text-slate-600 mb-8">خدمة تفاعلية توضّح المستندات والجهات والخطوات من التعاقد حتى شحن البضاعة.</p>

  @php
    $docs = [
      'فاتورة تجارية','قائمة التعبئة','بوليصة الشحن/بوليصة جوية',
      'شهادة المنشأ (من الغرفة التجارية)','إذن التصدير الجمركي',
      'القيد بسجل المصدرين (ساري)','شهادات مطابقة/فحص (إن وجدت)',
    ];
    $actors = [
      'مصلحة الجمارك','الهيئة العامة للرقابة على الصادرات والواردات (GOEIC)',
      'الغرفة التجارية','شركة الشحن/الخط الملاحي','منصة نافذة / بوابة الصادرات',
    ];
    $steps = [
      ['t'=>'تحديد المنتج والسوق المستهدف','d'=>'تحليل الطلب الخارجي ومتطلبات الدولة المستوردة.'],
      ['t'=>'التعاقد مع المستورد','d'=>'الاتفاق على الدفع والشحن (Incoterms).'],
      ['t'=>'تجهيز المستندات','d'=>'فاتورة، شهادة منشأ، بوليصة شحن، قائمة تعبئة.'],
      ['t'=>'التسجيل على نافذة','d'=>'رفع المستندات وتحديد منفذ الخروج.'],
      ['t'=>'الفحص والمعاينة','d'=>'الجمارك و/أو الجهات المختصة وفق نوع السلعة.'],
      ['t'=>'إصدار إذن التصدير','d'=>'بعد استيفاء المتطلبات وسداد الرسوم إن وجدت.'],
      ['t'=>'الشحن والمتابعة','d'=>'إرسال البوليصة وتتبع رحلة الشحن حتى التسليم.'],
    ];
    $alerts = [
      'تأكد من سريان قيد سجل المصدرين.',
      'طابق بنود الفاتورة مع قائمة التعبئة والبوليصة.',
      'راجِع اشتراطات بلد المقصد لتفادي الرفض.',
      'احتفِظ بنسخ إلكترونية على نافذة/بوابة الصادرات.',
    ];
  @endphp

  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">المستندات المطلوبة</h2>
    <div class="grid md:grid-cols-2 gap-3">
      @foreach($docs as $i => $doc)
        <div class="flex items-start gap-3 p-4 rounded-2xl bg-white shadow">
          <span class="mt-1 h-6 w-6 rounded-full bg-blue-600 text-white grid place-content-center">{{ $i+1 }}</span>
          <span class="text-slate-700">{{ $doc }}</span>
        </div>
      @endforeach
    </div>
  </div>

  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">الجهات المعنية</h2>
    <div class="flex flex-wrap gap-2">
      @foreach($actors as $a)
        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm">{{ $a }}</span>
      @endforeach
    </div>
  </div>

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

  <div class="mb-12">
    <h2 class="text-xl font-semibold mb-3">ملاحظات هامة</h2>
    <ul class="grid md:grid-cols-2 gap-3">
      @foreach($alerts as $al)
        <li class="p-4 rounded-2xl bg-amber-50 text-amber-900 shadow">{{ $al }}</li>
      @endforeach
    </ul>
  </div>

  <div class="flex flex-wrap gap-3">
    <a href="{{ route('front.export.calculator') }}" class="px-4 py-2 rounded-xl bg-blue-700 text-white">انتقل إلى حاسبة التصدير</a>
    <a href="{{ route('front.shipping.quote') ?? '#' }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">اطلب عرض سعر شحن</a>
  </div>
</div>
@endsection
