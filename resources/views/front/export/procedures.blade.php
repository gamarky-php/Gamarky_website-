@extends('layouts.front')
@section('title', __('front.export.procedures.title'))
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
  <h1 class="text-2xl md:text-3xl font-bold mb-6">{{ __('front.export.procedures.heading') }}</h1>
  <p class="text-slate-600 mb-8">{{ __('front.export.procedures.subtitle') }}</p>

  @php
    $docs = [
      __('front.export.procedures.docs.invoice'),
      __('front.export.procedures.docs.packing_list'),
      __('front.export.procedures.docs.bl_awb'),
      __('front.export.procedures.docs.origin_certificate'),
      __('front.export.procedures.docs.export_permit'),
      __('front.export.procedures.docs.exporters_registry'),
      __('front.export.procedures.docs.compliance_certs'),
    ];
    $actors = [
      __('front.export.procedures.actors.customs_authority'),
      __('front.export.procedures.actors.goeic'),
      __('front.export.procedures.actors.chamber'),
      __('front.export.procedures.actors.carrier'),
      __('front.export.procedures.actors.nafeza_export_portal'),
    ];
    $steps = [
      ['t'=>__('front.export.procedures.steps.select_product_market_t'),'d'=>__('front.export.procedures.steps.select_product_market_d')],
      ['t'=>__('front.export.procedures.steps.contract_importer_t'),'d'=>__('front.export.procedures.steps.contract_importer_d')],
      ['t'=>__('front.export.procedures.steps.prepare_docs_t'),'d'=>__('front.export.procedures.steps.prepare_docs_d')],
      ['t'=>__('front.export.procedures.steps.register_nafeza_t'),'d'=>__('front.export.procedures.steps.register_nafeza_d')],
      ['t'=>__('front.export.procedures.steps.inspection_t'),'d'=>__('front.export.procedures.steps.inspection_d')],
      ['t'=>__('front.export.procedures.steps.issue_permit_t'),'d'=>__('front.export.procedures.steps.issue_permit_d')],
      ['t'=>__('front.export.procedures.steps.ship_followup_t'),'d'=>__('front.export.procedures.steps.ship_followup_d')],
    ];
    $alerts = [
      __('front.export.procedures.alerts.registry_valid'),
      __('front.export.procedures.alerts.invoice_packing_bl_match'),
      __('front.export.procedures.alerts.destination_requirements'),
      __('front.export.procedures.alerts.keep_digital_copies'),
    ];
  @endphp

  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">{{ __('front.export.procedures.required_docs') }}</h2>
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
    <h2 class="text-xl font-semibold mb-3">{{ __('front.export.procedures.authorities') }}</h2>
    <div class="flex flex-wrap gap-2">
      @foreach($actors as $a)
        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm">{{ $a }}</span>
      @endforeach
    </div>
  </div>

  <div class="mb-10">
    <h2 class="text-xl font-semibold mb-3">{{ __('front.export.procedures.steps_title') }}</h2>
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
    <h2 class="text-xl font-semibold mb-3">{{ __('front.export.procedures.important_notes') }}</h2>
    <ul class="grid md:grid-cols-2 gap-3">
      @foreach($alerts as $al)
        <li class="p-4 rounded-2xl bg-amber-50 text-amber-900 shadow">{{ $al }}</li>
      @endforeach
    </ul>
  </div>

  <div class="flex flex-wrap gap-3">
    <a href="{{ route('front.export.calculator') }}" class="px-4 py-2 rounded-xl bg-blue-700 text-white">{{ __('front.export.procedures.go_to_calculator') }}</a>
    <a href="{{ route('front.shipping.quote') ?? '#' }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">{{ __('front.export.procedures.request_shipping_quote') }}</a>
  </div>
</div>
@endsection
