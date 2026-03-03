@extends('layouts.front')
@section('title', __('front.import.procedures.title'))
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

  <h1 class="text-2xl md:text-3xl font-bold mb-6">{{ __('front.import.procedures.heading') }}</h1>
  <p class="text-slate-600 mb-8">{{ __('front.import.procedures.subtitle') }}</p>

  @php
    $docs = [
      __('front.import.procedures.docs.invoice'),
      __('front.import.procedures.docs.bl_awb'),
      __('front.import.procedures.docs.origin_certificate'),
      __('front.import.procedures.docs.packing_list'),
      __('front.import.procedures.docs.lc_transfer'),
      __('front.import.procedures.docs.form4'),
      __('front.import.procedures.docs.delivery_order'),
    ];

    $actors = [
      __('front.import.procedures.actors.customs'),
      __('front.import.procedures.actors.bank'),
      __('front.import.procedures.actors.nafeza'),
      __('front.import.procedures.actors.shipping_company'),
      __('front.import.procedures.actors.goeic'),
      __('front.import.procedures.actors.broker'),
      __('front.import.procedures.actors.tax_authority'),
    ];

    $steps = [
      ['t'=>__('front.import.procedures.steps.select_supplier_t'),'d'=>__('front.import.procedures.steps.select_supplier_d')],
      ['t'=>__('front.import.procedures.steps.open_lc_t'),'d'=>__('front.import.procedures.steps.open_lc_d')],
      ['t'=>__('front.import.procedures.steps.shipping_t'),'d'=>__('front.import.procedures.steps.shipping_d')],
      ['t'=>__('front.import.procedures.steps.pre_clearance_t'),'d'=>__('front.import.procedures.steps.pre_clearance_d')],
      ['t'=>__('front.import.procedures.steps.arrival_t'),'d'=>__('front.import.procedures.steps.arrival_d')],
      ['t'=>__('front.import.procedures.steps.inspection_t'),'d'=>__('front.import.procedures.steps.inspection_d')],
      ['t'=>__('front.import.procedures.steps.release_t'),'d'=>__('front.import.procedures.steps.release_d')],
    ];

    $alerts = [
      __('front.import.procedures.alerts.hs'),
      __('front.import.procedures.alerts.incoterm'),
      __('front.import.procedures.alerts.pre_clearance'),
      __('front.import.procedures.alerts.docs_match'),
    ];
  @endphp

  {{-- المستندات المطلوبة --}}
  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">{{ __('front.import.procedures.required_docs') }}</h2>
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
    <h2 class="text-xl font-semibold mb-3">{{ __('front.import.procedures.authorities') }}</h2>
    <div class="flex flex-wrap gap-2">
      @foreach($actors as $a)
        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm">{{ $a }}</span>
      @endforeach
    </div>
  </div>

  {{-- الخطوات (Timeline) --}}
  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">{{ __('front.import.procedures.steps_title') }}</h2>
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
    <h2 class="text-xl font-semibold mb-3">{{ __('front.import.procedures.important_notes') }}</h2>
    <ul class="grid md:grid-cols-2 gap-3">
      @foreach($alerts as $al)
        <li class="p-4 rounded-2xl bg-amber-50 text-amber-900 shadow">{{ $al }}</li>
      @endforeach
    </ul>
  </div>

  {{-- أزرار لاحقة/قابلة للتطوير --}}
  <div class="flex flex-wrap gap-3">
    <a href="{{ route('front.import.calculator') }}" class="px-4 py-2 rounded-xl bg-blue-700 text-white">{{ __('front.import.procedures.go_to_calculator') }}</a>
    <a href="{{ route('front.shipping.quote') ?? '#' }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">{{ __('front.import.procedures.request_shipping_quote') }}</a>
  </div>
</div>
@endsection
