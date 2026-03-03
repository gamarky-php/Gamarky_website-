{{-- resources/views/front/export/markets.blade.php --}}
@extends('layouts.front')

@section('title', __('front.export.markets.title'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

  {{-- HERO --}}
  <div class="rounded-3xl bg-gradient-to-l from-indigo-700 to-blue-600 text-white p-8 md:p-10 shadow mb-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ __('front.export.markets.heading') }}</h1>
    <p class="text-white/90">{{ __('front.export.markets.subtitle') }}</p>
  </div>

  {{-- نموذج البحث (تجريبي الآن) --}}
  <div class="rounded-2xl bg-white shadow p-5 mb-8">
    <form class="grid md:grid-cols-3 gap-4" x-data="{ hs:'', q:'' }" @submit.prevent>
      <div>
        <label class="block text-sm text-slate-600 mb-1">{{ __('front.export.markets.hs_code_label') }}</label>
        <input x-model="hs" type="text" placeholder="{{ __('front.export.markets.hs_code_placeholder') }}"
               class="w-full rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 px-3 py-2">
      </div>
      <div>
        <label class="block text-sm text-slate-600 mb-1">{{ __('front.export.markets.target_market_label') }}</label>
        <input x-model="q" type="text" placeholder="{{ __('front.export.markets.target_market_placeholder') }}"
               class="w-full rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-0 px-3 py-2">
      </div>
      <div class="flex items-end">
        <button type="submit"
                class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-blue-700 text-white hover:bg-blue-800 transition">
          {{ __('front.export.markets.search') }}
        </button>
      </div>
      <p class="md:col-span-3 text-xs text-slate-500">{{ __('front.export.markets.data_source_note') }}</p>
    </form>
  </div>

  {{-- مؤشرات سريعة --}}
  @php
    $kpis = [
      ['t'=>__('front.export.markets.kpi_top_importers'),'v'=>__('front.export.markets.kpi_top_importers_value')],
      ['t'=>__('front.export.markets.kpi_avg_global_price'),'v'=>'—'],
      ['t'=>__('front.export.markets.kpi_trends_3y'),'v'=>'—'],
      ['t'=>__('front.export.markets.kpi_tariff_exemptions'),'v'=>'—'],
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
      ['country'=>__('front.export.markets.country_saudi'),'demand'=>'high','tariff'=>'0–5%','note'=>__('front.export.markets.note_saudi')],
      ['country'=>__('front.export.markets.country_uae'),'demand'=>'high','tariff'=>'0%','note'=>__('front.export.markets.note_uae')],
      ['country'=>__('front.export.markets.country_germany'),'demand'=>'medium','tariff'=>'2–6%','note'=>__('front.export.markets.note_germany')],
      ['country'=>__('front.export.markets.country_kenya'),'demand'=>'medium','tariff'=>'5–10%','note'=>__('front.export.markets.note_kenya')],
      ['country'=>__('front.export.markets.country_morocco'),'demand'=>'medium','tariff'=>'0–5%','note'=>__('front.export.markets.note_morocco')],
    ];
  @endphp

  <div class="rounded-2xl bg-white shadow overflow-hidden mb-10">
    <div class="px-4 py-3 border-b bg-slate-50 font-semibold">{{ __('front.export.markets.suggested_markets_by_code') }}</div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-start">{{ __('front.export.markets.country') }}</th>
            <th class="px-4 py-3 text-start">{{ __('front.export.markets.demand') }}</th>
            <th class="px-4 py-3 text-start">{{ __('front.export.markets.tariff_preferences') }}</th>
            <th class="px-4 py-3 text-start">{{ __('front.export.markets.notes') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($markets as $m)
          <tr class="border-t">
            <td class="px-4 py-3">{{ $m['country'] }}</td>
            <td class="px-4 py-3">
              <span class="px-2 py-1 rounded-full text-xs
                {{ $m['demand']==='high'?'bg-emerald-100 text-emerald-800':
                   ($m['demand']==='medium'?'bg-amber-100 text-amber-800':'bg-slate-100 text-slate-700') }}">
                {{ $m['demand']==='high' ? __('front.export.markets.demand_high') : __('front.export.markets.demand_medium') }}
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
      <h3 class="text-lg font-semibold mb-2">{{ __('front.export.markets.competitive_matrix_title') }}</h3>
      <p class="text-slate-600 mb-4">{{ __('front.export.markets.competitive_matrix_desc') }}</p>
      <a href="{{ route('front.export.calculator') ?? '#' }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white">
        {{ __('front.export.markets.show_competitive_matrix') }}
      </a>
    </div>
    <div class="rounded-2xl bg-white shadow p-6">
      <h3 class="text-lg font-semibold mb-2">{{ __('front.export.markets.expert_consultation_title') }}</h3>
      <p class="text-slate-600 mb-4">{{ __('front.export.markets.expert_consultation_desc') }}</p>
      <a href="{{ route('front.export.procedures') ?? '#' }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-700 text-white">
        {{ __('front.export.markets.book_consultation') }}
      </a>
    </div>
  </div>

  {{-- خطوات الاستخدام --}}
  @php
    $steps = [
      __('front.export.markets.step_1'),
      __('front.export.markets.step_2'),
      __('front.export.markets.step_3'),
      __('front.export.markets.step_4'),
      __('front.export.markets.step_5'),
    ];
  @endphp
  <div class="rounded-2xl bg-white shadow p-6 mb-10">
    <h3 class="text-lg font-semibold mb-4">{{ __('front.export.markets.usage_steps') }}</h3>
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
      <li>{{ __('front.export.markets.alert_1') }}</li>
      <li>{{ __('front.export.markets.alert_2') }}</li>
      <li>{{ __('front.export.markets.alert_3') }}</li>
    </ul>
  </div>

</div>
@endsection
