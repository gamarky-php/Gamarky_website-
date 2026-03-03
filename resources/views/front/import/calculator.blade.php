@extends('layouts.front')

@section('title', __('front.import.calculator.title'))

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
@endpush

@section('content')
<main class="page-wrapper mx-auto px-3 md:px-6 min-h-screen bg-gray-50 text-slate-800">
  {{-- أي هيدر/بِنَر/CTA علوي يبقى كما هو --}}
    {{-- Header --}}
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-blue-700 text-white rounded-2xl shadow-xl p-8 mb-8">
      <h1 class="text-4xl font-bold mb-2 text-center">{{ __('front.import.calculator.heading') }}</h1>
      <p class="text-white/90 text-lg text-center">{{ __('front.import.calculator.quote') }}</p>
    </div>



  {{-- تخطيط الشبكة الرئيسي: سايدبار 15% + محتوى 85% --}}
  <div class="grid grid-cols-1 md:grid-cols-[15%_85%] md:gap-4">

      {{-- العمود 1: السايدبار (15%) --}}
  <aside class="md:col-start-1 md:sticky md:top-24 md:self-start">
        {{-- كارت 1: مدخلات الشحن --}}
        <div class="sidebar-card reveal">
          <div class="card-title-gradient">{{ __('front.import.calculator.shipping_inputs') }}</div>
          <div class="space-y-3">
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.origin_country') }}</label>
              <select id="country_id" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('front.import.calculator.select_origin_country') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.shipping_mode') }}</label>
              <select id="mode" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('front.import.calculator.select_shipping_mode') }}</option>
                <option value="sea">{{ __('front.import.calculator.sea') }}</option>
                <option value="air">{{ __('front.import.calculator.air') }}</option>
                <option value="land">{{ __('front.import.calculator.land') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.origin_port') }}</label>
              <select id="port_id" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('front.import.calculator.select_origin_port') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.shipping_type_optional') }}</label>
              <select id="shipping_type_id" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('front.import.calculator.select_shipping_type') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.container_type') }}</label>
              <select class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('front.import.calculator.select_container_type') }}</option>
                <option value="20ft">{{ __('front.import.calculator.container_20ft_standard') }}</option>
                <option value="40ft">{{ __('front.import.calculator.container_40ft_standard') }}</option>
                <option value="40ft-hc">{{ __('front.import.calculator.container_40ft_high') }}</option>
                <option value="20ft-ref">{{ __('front.import.calculator.container_20ft_reefer') }}</option>
                <option value="40ft-ref">{{ __('front.import.calculator.container_40ft_reefer') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.destination_port') }}</label>
              <select class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <option value="">{{ __('front.import.calculator.select_destination_port') }}</option>
                <option value="alexandria">{{ __('front.import.calculator.port_alexandria_eg') }}</option>
                <option value="damietta">{{ __('front.import.calculator.port_damietta_eg') }}</option>
                <option value="port_said">{{ __('front.import.calculator.port_port_said_eg') }}</option>
                <option value="sokhna">{{ __('front.import.calculator.port_sokhna_eg') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.cargo_weight_ton') }}</label>
              <input type="number" step="0.01" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="{{ __('front.import.calculator.enter_weight') }}" />
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.cargo_volume_cbm') }}</label>
              <input type="number" step="0.01" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="{{ __('front.import.calculator.enter_volume') }}" />
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.etd') }}</label>
              <input type="date" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
            </div>
          </div>
        </div>

        {{-- كارت 2: اقتراحات الخدمات --}}
        <div class="sidebar-card reveal">
          <div class="card-title-gradient">{{ __('front.import.calculator.service_suggestions') }}</div>
          <div class="space-y-2">
            <a href="#" class="block w-full text-center rounded-lg border border-slate-200 py-2 text-sm hover:bg-slate-50 transition">{{ __('front.import.calculator.shipping_agent') }}</a>
            <a href="#" class="block w-full text-center rounded-lg border border-slate-200 py-2 text-sm hover:bg-slate-50 transition">{{ __('front.import.calculator.customs_broker') }}</a>
            <a href="#" class="block w-full text-center rounded-lg border border-slate-200 py-2 text-sm hover:bg-slate-50 transition">{{ __('front.import.calculator.container_exchange') }}</a>
            <a href="#" class="block w-full text-center rounded-lg border border-slate-200 py-2 text-sm hover:bg-slate-50 transition">{{ __('front.import.calculator.local_transport') }}</a>
          </div>
        </div>

        {{-- كارت 3: الإعلانات --}}
        <div class="sidebar-card order-last md:order-last reveal" id="ads-services-card">
          {{-- داخل div.sidebar-card الخاص بالإعلانات --}}
          @php
            $service = app()->bound(\App\Services\AdsWidgetService::class)
                ? app(\App\Services\AdsWidgetService::class)
                : null;
            $cards = $service ? $service->cards(optional(auth()->user())->specialty) : [];
          @endphp
          @if(!empty($cards))
            @include('components.ads.panel', ['cards'=>$cards])
          @else
            <div class="text-sm text-slate-500 text-center py-4">{{ __('front.import.calculator.no_ads') }}</div>
          @endif
        </div>

      </aside>
      {{-- نهاية السايدبار --}}

      {{-- العمود 2: المحتوى الرئيسي (85%) --}}
  <main class="md:col-start-2 w-full overflow-visible">

        {{-- moved to bottom below invoice table --}}

        {{-- شريط التكاليف الثابت (تم نقله داخل العمود الرئيسي للحفاظ على سلامة الأعمدة) --}}
        <div class="mb-6 reveal">
          <div class="bg-purple-50 rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12M9 7V3h6v4" />
                </svg>
                {{ __('front.import.calculator.fixed_distribution_costs') }}
              </h2>
              <button type="button" id="distributeBtn" class="px-4 py-2 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-all">{{ __('front.import.calculator.distribute_values') }}</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 md:w-2/3">
              <div>
                <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.freight_docs_egp') }}</label>
                <input type="number" id="fixed_shipping" inputmode="decimal" value="0" class="w-full rounded-lg border-slate-200 text-sm px-2 py-1.5" />
              </div>
              <div>
                <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.broker_fees_egp') }}</label>
                <input type="number" id="fixed_clearance" inputmode="decimal" value="0" class="w-full rounded-lg border-slate-200 text-sm px-2 py-1.5" />
              </div>
              <div>
                <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.local_transport_egp') }}</label>
                <input type="number" id="fixed_transport" inputmode="decimal" value="0" class="w-full rounded-lg border-slate-200 text-sm px-2 py-1.5" />
              </div>
              <div>
                <label class="block text-xs text-slate-600 mb-1">{{ __('front.import.calculator.other_expenses_egp') }}</label>
                <input type="number" id="fixed_other" inputmode="decimal" value="0" class="w-full rounded-lg border-slate-200 text-sm px-2 py-1.5" />
              </div>
            </div>
          </div>
        </div>

        {{-- شريط الأدوات --}}
        <div class="flex flex-wrap items-center gap-3 justify-start mb-4 reveal" id="itemsToolbar">
          <button type="button" id="addItemBtn" class="toolbar-btn btn-green">{{ __('front.import.calculator.add_item') }}</button>
          <button type="button" id="removeItemBtn" class="toolbar-btn btn-red">{{ __('front.import.calculator.remove_item') }}</button>
          <button type="button" id="clearAllBtn" class="toolbar-btn btn-blue">{{ __('front.import.calculator.clear_all') }}</button>
          <button type="button" id="calcAllBtn" class="toolbar-btn btn-brand">{{ __('front.import.calculator.calculate_all') }}</button>
          <button type="button" id="pdfBtn" class="toolbar-btn bg-rose-600">PDF</button>
          <button type="button" id="excelBtn" class="toolbar-btn bg-emerald-600">Excel</button>
          <button type="button" id="printBtn" class="toolbar-btn bg-blue-600">{{ __('front.import.calculator.print') }}</button>
        </div>

        {{-- جدول الفاتورة --}}
        <div class="table-card reveal overflow-hidden">
          <div class="md:hidden text-center text-sm text-slate-500 mb-2">
            {{ __('front.import.calculator.drag_table_hint') }}
          </div>
          <div class="overflow-x-auto md:overflow-visible">
            <table id="itemsTable" class="w-full border-separate border-spacing-y-1 min-w-[900px] md:min-w-0">
              <colgroup>
                <col class="w-[220px]" />
                <col />
                <col />
                <col />
                <col />
                <col />
              </colgroup>
              <thead class="table-header">
                <tr>
                  <th class="table-th sticky-col rounded-r-xl px-4">{{ __('front.import.calculator.statement') }}</th>
                  @for ($i = 1; $i <= 5; $i++)
                    <th class="table-th">{{ __('front.import.calculator.item') }} {{ $i }}</th>
                  @endfor
                </tr>
              </thead>
              <tbody id="itemsTbody" class="divide-y divide-slate-100">
                  @php
                    function rowCells($cols = 5, $editable = false, $prefix = '', $placeholder = '0') {
                        for ($i=1; $i<=$cols; $i++) {
                            if ($editable) {
                                echo '<td class="px-4 py-2"><input type="text" id="'.$prefix.'_'.$i.'" class="w-full rounded-lg border-slate-200 text-sm" placeholder="'.$placeholder.'" /></td>';
                            } else {
                                echo '<td class="px-4 py-2 text-center text-slate-800">0.00</td>';
                            }
                        }
                    }
                  @endphp

                  {{-- 1) Tariff code --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.tariff_code') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td"><input type="text" id="tariff_code_{{ $i }}" class="cell-input" placeholder="{{ __('front.import.calculator.tariff_code') }}" /></td>
                    @endfor
                  </tr>

                  {{-- 2) Product name --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.product_name') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td"><input type="text" id="name_{{ $i }}" class="cell-input" placeholder="{{ __('front.import.calculator.product_name') }}" /></td>
                    @endfor
                  </tr>

                  {{-- 3) الكمية --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.quantity') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td"><input type="number" id="qty_{{ $i }}" class="cell-input no-spinners quantity-input" data-item="{{ $i }}" value="0" /></td>
                    @endfor
                  </tr>

                  {{-- 4) السعر --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.price') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td"><input type="number" id="price_{{ $i }}" class="cell-input no-spinners price-input" data-item="{{ $i }}" value="0" /></td>
                    @endfor
                  </tr>

                  {{-- 5) المجموع --}}
                  <tr class="table-row table-row-zebra bg-emerald-50 font-semibold text-emerald-800">
                    <td class="table-td sticky-col px-4 font-semibold">{{ __('front.import.calculator.total') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center font-semibold" id="total_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 6) ضريبة الوارد --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.import_tax') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="customs_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 7) ضريبة القيمة المضافة --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.vat') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="vat_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 8) ا.ت.ص / CIP --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.cip') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="cip_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 9) إجمالي الجمارك --}}
                  <tr class="table-row table-row-zebra bg-emerald-50 font-semibold text-emerald-800">
                    <td class="table-td sticky-col px-4 font-semibold">{{ __('front.import.calculator.total_customs') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center font-semibold" id="total_customs_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 10) تكلفة النولون والتوثيق --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.freight_docs_cost') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="shipping_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 11) تكلفة مصروفات المستخلص --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.broker_cost') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="clearance_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 12) تكلفة النقل --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.transport_cost') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="transport_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 13) مصروفات أخرى --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.other_expenses') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="other_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 14) إجمالي التكلفة --}}
                  <tr class="table-row table-row-zebra bg-emerald-50 font-semibold text-emerald-800">
                    <td class="table-td sticky-col px-4 font-bold">{{ __('front.import.calculator.grand_total') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center font-bold" id="grand_total_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 15) تكلفة الوحدة --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.unit_cost') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="unit_cost_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 16) سعر البيع المتوقع --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.expected_sale_price') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td"><input type="number" id="sale_price_{{ $i }}" class="cell-input no-spinners" data-item="{{ $i }}" value="0" /></td>
                    @endfor
                  </tr>

                  {{-- 17) الربح المتوقع --}}
                  <tr class="table-row table-row-zebra">
                    <td class="table-td sticky-col px-4">{{ __('front.import.calculator.expected_profit') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td class="table-td text-center text-slate-800" id="profit_{{ $i }}">0.00</td>
                    @endfor
                  </tr>

                  {{-- 18) معدل الربحية --}}
                  <tr class="table-row table-row-zebra bg-yellow-50/50">
                    <td class="table-td sticky-col px-4 font-semibold">{{ __('front.import.calculator.profit_rate') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                      <td id="profit_rate_{{ $i }}" class="table-td text-center font-semibold text-slate-800">0.00%</td>
                    @endfor
                  </tr>

                </tbody>
          </table>
          </div>
        </div>

        {{-- Recommended Suppliers Section - positioned after invoice table --}}
        <section id="recommended-suppliers" class="mt-8">
          @if(class_exists(\App\Livewire\RecommendedSuppliers::class) || class_exists(\App\Http\Livewire\RecommendedSuppliers::class))
            @livewire('recommended-suppliers', ['countryCode' => request('country') ?? null, 'limit' => 6])
          @else
            <div class="rounded-xl bg-white shadow-sm p-4 md:p-5">
              <h3 class="text-lg font-semibold mb-3">{{ __('front.import.calculator.recommended_suppliers') }}</h3>
              <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @for($i = 1; $i <= 6; $i++)
                  <div class="border rounded-lg p-3 flex flex-col justify-between h-full">
                    <div>
                      <div class="font-medium text-slate-800">{{ __('front.import.calculator.supplier') }} {{ $i }}</div>
                      <div class="text-sm text-slate-500 mt-1">{{ __('front.import.calculator.default_supplier_country') }}</div>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                      <span class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-1.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 text-sm">
                        {{ __('front.import.calculator.whatsapp') }}
                      </span>
                      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-slate-50 text-slate-700 border border-slate-100 text-sm">
                        {{ __('front.import.calculator.call') }}
                      </span>
                    </div>
                  </div>
                @endfor
              </div>
            </div>
          @endif
        </section>

      </main>
      {{-- نهاية المحتوى الرئيسي --}}

    </div>
    {{-- نهاية layout-grid --}}

    {{-- Footer --}}
    <div class="mt-8 text-center text-gray-600">
        <p>{{ __('front.import.calculator.copyright_2025') }}</p>
    </div>
</main>
@endsection

@push('scripts')
{{-- Load jQuery and Select2 first --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  // ===== Dynamic Selects Integration with Select2 =====
  (function(){
    const API = '{{ url("/api/v1") }}'; // dynamic API base

    // تهيئة select2 لنوع الشحن (قائمة ثابتة)
    $('#mode').select2({
      placeholder: @js(__('front.import.calculator.select_shipping_mode')),
      width: '100%'
    });

    // بلد المنشأ
    $('#country_id').select2({
      placeholder: @js(__('front.import.calculator.select_origin_country')),
      ajax: {
        url: API + '/countries',
        dataType: 'json', delay: 250,
        data: params => ({ q: params.term || '' }),
        processResults: data => data, cache: true
      },
      width: '100%'
    });

    // تحميل الموانئ بناءً على البلد + نوع الشحن
    const loadPorts = () => {
      const countryId = $('#country_id').val();
      const mode = $('#mode').val();
      if(!countryId || !mode){ $('#port_id').empty().trigger('change'); return; }
      $('#port_id').select2({
        placeholder: @js(__('front.import.calculator.select_origin_port')),
        ajax: {
          url: API + '/ports',
          dataType: 'json', delay: 200,
          data: params => ({ country_id: countryId, mode, q: params.term || '' }),
          processResults: data => data, cache: true
        },
        width: '100%'
      });
      $('#port_id').val(null).trigger('change');
    };

    // أنواع الشحن التفصيلية (اختياري)
    const loadTypes = () => {
      const mode = $('#mode').val();
      if(!mode){ $('#shipping_type_id').empty().trigger('change'); return; }
      $('#shipping_type_id').select2({
        placeholder: @js(__('front.import.calculator.select_shipping_type')),
        ajax: {
          url: API + '/shipping-types',
          dataType: 'json', delay: 200,
          data: params => ({ mode, q: params.term || '' }),
          processResults: data => data, cache: true
        },
        width: '100%'
      });
      $('#shipping_type_id').val(null).trigger('change');
    };

    $('#country_id').on('change', loadPorts);
    $('#mode').on('change', () => { loadPorts(); loadTypes(); });
    // يمكنك ضبط قيمة افتراضية: $('#mode').val('sea').trigger('change');
  })();

  // ===== Original Calculator Logic =====
  document.addEventListener('DOMContentLoaded', function() {
    // دالة لحساب المجموع (الكمية × السعر)
    function calculateTotal(itemNumber) {
      const qtyEl = document.getElementById('qty_' + itemNumber);
      const priceEl = document.getElementById('price_' + itemNumber);
      const qty = qtyEl ? parseFloat(qtyEl.value) || 0 : 0;
      const price = priceEl ? parseFloat(priceEl.value) || 0 : 0;
      const total = qty * price;

      const totalEl = document.getElementById('total_' + itemNumber);
      if (totalEl) totalEl.textContent = total.toFixed(2);

      // حساب ضريبة الوارد بعد حساب المجموع
      calculateCustoms(itemNumber);
    }

    // دالة لحساب ضريبة الوارد = المجموع × 10%
    function calculateCustoms(itemNumber) {
      const total = parseFloat(document.getElementById('total_' + itemNumber).textContent) || 0;
      const customs = total * 0.10;

      const customsEl = document.getElementById('customs_' + itemNumber);
      if (customsEl) customsEl.textContent = customs.toFixed(2);

      // حساب ضريبة القيمة المضافة بعد حساب ضريبة الوارد
      calculateVAT(itemNumber);
    }

    // دالة لحساب ضريبة القيمة المضافة = (المجموع + ضريبة الوارد) × 14%
    function calculateVAT(itemNumber) {
      const total = parseFloat(document.getElementById('total_' + itemNumber).textContent) || 0;
      const customs = parseFloat(document.getElementById('customs_' + itemNumber).textContent) || 0;
      const vat = (total + customs) * 0.14;

      const vatEl = document.getElementById('vat_' + itemNumber);
      if (vatEl) vatEl.textContent = vat.toFixed(2);

      // حساب ا.ت.ص بعد حساب ضريبة القيمة المضافة
      calculateCIP(itemNumber);
    }

    // دالة لحساب ا.ت.ص = المجموع × 1%
    function calculateCIP(itemNumber) {
      const total = parseFloat(document.getElementById('total_' + itemNumber).textContent) || 0;
      const cip = total * 0.01;

      const cipEl = document.getElementById('cip_' + itemNumber);
      if (cipEl) cipEl.textContent = cip.toFixed(2);

      // حساب إجمالي الجمارك بعد حساب ا.ت.ص
      calculateTotalCustoms(itemNumber);
    }

    // دالة لحساب إجمالي الجمارك = ضريبة الوارد + ضريبة القيمة المضافة + ا.ت.ص
    function calculateTotalCustoms(itemNumber) {
      const customs = parseFloat(document.getElementById('customs_' + itemNumber).textContent) || 0;
      const vat = parseFloat(document.getElementById('vat_' + itemNumber).textContent) || 0;
      const cip = parseFloat(document.getElementById('cip_' + itemNumber).textContent) || 0;
      const totalCustoms = customs + vat + cip;

      const totalCustomsEl = document.getElementById('total_customs_' + itemNumber);
      if (totalCustomsEl) totalCustomsEl.textContent = totalCustoms.toFixed(2);

      // حساب إجمالي التكلفة بعد حساب إجمالي الجمارك
      calculateGrandTotal(itemNumber);
    }

    // دالة لحساب إجمالي التكلفة = المجموع + إجمالي الجمارك + التكاليف الموزعة
    function calculateGrandTotal(itemNumber) {
      const total = parseFloat(document.getElementById('total_' + itemNumber).textContent) || 0;
      const totalCustoms = parseFloat(document.getElementById('total_customs_' + itemNumber).textContent) || 0;
      const shipping = parseFloat(document.getElementById('shipping_' + itemNumber).textContent) || 0;
      const clearance = parseFloat(document.getElementById('clearance_' + itemNumber).textContent) || 0;
      const transport = parseFloat(document.getElementById('transport_' + itemNumber).textContent) || 0;
      const other = parseFloat(document.getElementById('other_' + itemNumber).textContent) || 0;

      const grandTotal = total + totalCustoms + shipping + clearance + transport + other;

      const grandEl = document.getElementById('grand_total_' + itemNumber);
      if (grandEl) grandEl.textContent = grandTotal.toFixed(2);

      // حساب تكلفة الوحدة بعد حساب إجمالي التكلفة
      calculateUnitCost(itemNumber);
    }

    // دالة لحساب تكلفة الوحدة = إجمالي التكلفة ÷ الكمية
    function calculateUnitCost(itemNumber) {
      const grandTotal = parseFloat(document.getElementById('grand_total_' + itemNumber).textContent) || 0;
      const qty = parseFloat(document.getElementById('qty_' + itemNumber).value) || 0;

      const unitCost = qty > 0 ? grandTotal / qty : 0;

      const unitEl = document.getElementById('unit_cost_' + itemNumber);
      if (unitEl) unitEl.textContent = unitCost.toFixed(2);

      // حساب الربح المتوقع بعد حساب تكلفة الوحدة
      calculateProfit(itemNumber);
    }

    // دالة لحساب الربح المتوقع = سعر البيع المتوقع - تكلفة الوحدة
    function calculateProfit(itemNumber) {
      const salePriceEl = document.getElementById('sale_price_' + itemNumber);
      const salePrice = salePriceEl ? parseFloat(salePriceEl.value) || 0 : 0;
      const unitCost = parseFloat(document.getElementById('unit_cost_' + itemNumber).textContent) || 0;

      const profit = salePrice - unitCost;

      const profitEl = document.getElementById('profit_' + itemNumber);
      if (profitEl) profitEl.textContent = profit.toFixed(2);

      // حساب معدل الربحية بعد حساب الربح
      calculateProfitRate(itemNumber);
    }

    // دالة لحساب معدل الربحية مع الألوان الدلالية
    function calculateProfitRate(itemNumber) {
      const profit = parseFloat(document.getElementById('profit_' + itemNumber).textContent) || 0;
      const unitCost = parseFloat(document.getElementById('unit_cost_' + itemNumber).textContent) || 0;

      const profitRate = unitCost > 0 ? (profit / unitCost) * 100 : 0;

      const profitRateElement = document.getElementById('profit_rate_' + itemNumber);
      if (!profitRateElement) return;

      profitRateElement.textContent = profitRate.toFixed(2) + '%';

      // إعادة تعيين classes الأساسية
      profitRateElement.className = 'px-4 py-2 text-center font-semibold';

      // تطبيق اللون باستخدام inline style
      let bgColor, textColor;

      if (profitRate < 0) {
        bgColor = '#000000';
        textColor = '#ffffff';
      } else if (profitRate >= 1 && profitRate <= 14) {
        bgColor = '#dc2626';
        textColor = '#ffffff';
      } else if (profitRate >= 15 && profitRate <= 19) {
        bgColor = '#f97316';
        textColor = '#ffffff';
      } else if (profitRate >= 20 && profitRate <= 29) {
        bgColor = '#facc15';
        textColor = '#000000';
      } else if (profitRate >= 30 && profitRate <= 39) {
        bgColor = '#bfdbfe';
        textColor = '#000000';
      } else if (profitRate >= 40 && profitRate <= 49) {
        bgColor = '#4ade80';
        textColor = '#000000';
      } else if (profitRate >= 50) {
        bgColor = '#15803d';
        textColor = '#ffffff';
      } else {
        bgColor = '#f1f5f9';
        textColor = '#1e293b';
      }

      profitRateElement.style.backgroundColor = bgColor;
      profitRateElement.style.color = textColor;
    }

    // دالة لتوزيع التكاليف الثابتة على البنود بالنسبة والتناسب
    function distributeFixedCosts() {
      const fixedShipping = parseFloat(document.getElementById('fixed_shipping').value) || 0;
      const fixedClearance = parseFloat(document.getElementById('fixed_clearance').value) || 0;
      const fixedTransport = parseFloat(document.getElementById('fixed_transport').value) || 0;
      const fixedOther = parseFloat(document.getElementById('fixed_other').value) || 0;

      let grandTotal = 0;
      const count = Math.max(1, document.querySelectorAll('#itemsTable thead tr th').length - 1);
      for (let i = 1; i <= count; i++) {
        const total = parseFloat(document.getElementById('total_' + i).textContent) || 0;
        grandTotal += total;
      }

      if (grandTotal === 0) {
        alert(@js(__('front.import.calculator.alert_enter_qty_price_first')));
        return;
      }

      for (let i = 1; i <= count; i++) {
        const total = parseFloat(document.getElementById('total_' + i).textContent) || 0;
        const ratio = total / grandTotal;

        const shippingEl = document.getElementById('shipping_' + i);
        const clearanceEl = document.getElementById('clearance_' + i);
        const transportEl = document.getElementById('transport_' + i);
        const otherEl = document.getElementById('other_' + i);

        if (shippingEl) shippingEl.textContent = (fixedShipping * ratio).toFixed(2);
        if (clearanceEl) clearanceEl.textContent = (fixedClearance * ratio).toFixed(2);
        if (transportEl) transportEl.textContent = (fixedTransport * ratio).toFixed(2);
        if (otherEl) otherEl.textContent = (fixedOther * ratio).toFixed(2);

        calculateGrandTotal(i);
      }
    }

    // مستمعات الأحداث
    const distributeBtn = document.getElementById('distributeBtn');
    if (distributeBtn) {
      distributeBtn.addEventListener('click', distributeFixedCosts);
    }

    // تحديث: تأكد من توافق أسماء الأزرار الجديدة
    const calcAllBtn = document.getElementById('calcAllBtn');
    if (calcAllBtn) {
      calcAllBtn.addEventListener('click', function() {
        const count = Math.max(1, document.querySelectorAll('#itemsTable thead tr th').length - 1);
        for (let i = 1; i <= count; i++) {
          calculateTotal(i);
        }
      });
    }

    const clearAllBtn = document.getElementById('clearAllBtn');
    if (clearAllBtn) {
      clearAllBtn.addEventListener('click', function() {
        if (confirm(@js(__('front.import.calculator.confirm_clear_all')))) {
          const count = Math.max(1, document.querySelectorAll('#itemsTable thead tr th').length - 1);
          for (let i = 1; i <= count; i++) {
            const nameInput = document.getElementById('name_' + i);
            const qtyInput = document.getElementById('qty_' + i);
            const priceInput = document.getElementById('price_' + i);
            const salePriceInput = document.getElementById('sale_price_' + i);

            if (nameInput) nameInput.value = '';
            if (qtyInput) qtyInput.value = '0';
            if (priceInput) priceInput.value = '0';
            if (salePriceInput) salePriceInput.value = '0';

            calculateTotal(i);
          }

          document.getElementById('fixed_shipping').value = '0';
          document.getElementById('fixed_clearance').value = '0';
          document.getElementById('fixed_transport').value = '0';
          document.getElementById('fixed_other').value = '0';
        }
      });
    }

    // إضافة مستمعين للحقول
    function attachInputListenersForCount(count) {
      for (let i = 1; i <= count; i++) {
        const qtyInput = document.getElementById('qty_' + i);
        const priceInput = document.getElementById('price_' + i);
        const salePriceInput = document.getElementById('sale_price_' + i);

        if (qtyInput) {
          qtyInput.addEventListener('input', function() {
            calculateTotal(i);
          });
        }

        if (priceInput) {
          priceInput.addEventListener('input', function() {
            calculateTotal(i);
          });
        }

        if (salePriceInput) {
          salePriceInput.addEventListener('input', function() {
            calculateProfit(i);
          });
        }

        // تطبيق اللون الافتراضي
        calculateProfitRate(i);
      }
    }

    // attach initial listeners for 5 columns
    attachInputListenersForCount(5);

    // ===== Toolbar: add/remove columns, export, print =====
    const addItemBtn = document.getElementById('addItemBtn');
    const removeItemBtn = document.getElementById('removeItemBtn');
    const printBtn = document.getElementById('printBtn');
    const excelBtn = document.getElementById('excelBtn');
    const pdfBtn = document.getElementById('pdfBtn');
    const table = document.getElementById('itemsTable');
    const tbodyEl = document.getElementById('itemsTbody');

    function getItemCount() {
      const ths = table.querySelectorAll('thead tr th');
      return Math.max(0, ths.length - 1);
    }

    function addColumn() {
      const next = getItemCount() + 1;
      // add header
      const theadRow = table.querySelector('thead tr');
      const th = document.createElement('th');
      th.className = 'bill-th px-4 py-3';
      th.textContent = @js(__('front.import.calculator.item')) + ' ' + next;
      theadRow.appendChild(th);

      // Build cells per fixed row index (there are 18 rows in tbody)
      const rows = Array.from(tbodyEl.querySelectorAll('tr'));
      rows.forEach((row, idx) => {
        const td = document.createElement('td');
        td.className = 'px-4 py-2';

        switch (idx) {
          case 0: // tariff code - editable text
            td.innerHTML = `<input type="text" id="tariff_code_${next}" class="w-full rounded-lg border-slate-200 text-sm" placeholder="${@js(__('front.import.calculator.tariff_code'))}" />`;
            break;
          case 1: // product name
            td.innerHTML = `<input type="text" id="name_${next}" class="w-full rounded-lg border-slate-200 text-sm" placeholder="${@js(__('front.import.calculator.product_name'))}" />`;
            break;
          case 2: // quantity (number)
            td.innerHTML = `<input type="number" id="qty_${next}" class="quantity-input w-full rounded-lg border-slate-200 text-sm" data-item="${next}" value="0" min="0" />`;
            break;
          case 3: // price (number)
            td.innerHTML = `<input type="number" id="price_${next}" class="price-input w-full rounded-lg border-slate-200 text-sm" data-item="${next}" value="0" min="0" step="0.01" />`;
            break;
          case 4: // total (computed)
            td.className += ' text-center text-slate-800 font-semibold';
            td.id = 'total_' + next;
            td.textContent = '0.00';
            break;
          case 5: // import tax
            td.className += ' text-center text-slate-800';
            td.id = 'customs_' + next;
            td.textContent = '0.00';
            break;
          case 6: // VAT
            td.className += ' text-center text-slate-800';
            td.id = 'vat_' + next;
            td.textContent = '0.00';
            break;
          case 7: // CIP
            td.className += ' text-center text-slate-800';
            td.id = 'cip_' + next;
            td.textContent = '0.00';
            break;
          case 8: // total customs
            td.className += ' text-center text-slate-800 font-semibold';
            td.id = 'total_customs_' + next;
            td.textContent = '0.00';
            break;
          case 9: // freight + docs cost
            td.className += ' text-center text-slate-800';
            td.id = 'shipping_' + next;
            td.textContent = '0.00';
            break;
          case 10: // broker cost
            td.className += ' text-center text-slate-800';
            td.id = 'clearance_' + next;
            td.textContent = '0.00';
            break;
          case 11: // transport cost
            td.className += ' text-center text-slate-800';
            td.id = 'transport_' + next;
            td.textContent = '0.00';
            break;
          case 12: // other expenses
            td.className += ' text-center text-slate-800';
            td.id = 'other_' + next;
            td.textContent = '0.00';
            break;
          case 13: // grand total
            td.className += ' text-center text-slate-800 font-bold';
            td.id = 'grand_total_' + next;
            td.textContent = '0.00';
            break;
          case 14: // unit cost
            td.className += ' text-center text-slate-800';
            td.id = 'unit_cost_' + next;
            td.textContent = '0.00';
            break;
          case 15: // expected sale price (input number)
            td.innerHTML = `<input type="number" id="sale_price_${next}" class="sale-price-input w-full rounded-lg border-slate-200 text-sm" data-item="${next}" value="0" min="0" step="0.01" />`;
            break;
          case 16: // expected profit
            td.className += ' text-center text-slate-800';
            td.id = 'profit_' + next;
            td.textContent = '0.00';
            break;
          case 17: // profit rate
            td.className += ' px-4 py-2 text-center font-semibold bg-slate-100 text-slate-800';
            td.id = 'profit_rate_' + next;
            td.textContent = '0.00%';
            break;
          default:
            td.className += ' text-center text-slate-800';
            td.textContent = '0.00';
        }

        row.appendChild(td);
      });

      // attach listeners for the new column inputs
      const qtyEl = document.getElementById('qty_' + next);
      if (qtyEl) qtyEl.addEventListener('input', function () { calculateTotal(next); });
      const priceEl = document.getElementById('price_' + next);
      if (priceEl) priceEl.addEventListener('input', function () { calculateTotal(next); });
      const saleEl = document.getElementById('sale_price_' + next);
      if (saleEl) saleEl.addEventListener('input', function () { calculateProfit(next); });
    }

    function removeColumn() {
      const count = getItemCount();
      if (count <= 1) {
        alert(@js(__('front.import.calculator.alert_cannot_delete_last_item')));
        return;
      }
      // remove last header
      const theadRow = table.querySelector('thead tr');
      theadRow.removeChild(theadRow.lastElementChild);
      // remove last td from each row
      tbodyEl.querySelectorAll('tr').forEach(row => {
        row.removeChild(row.lastElementChild);
      });
    }

    function clearAllColumns() {
      const count = getItemCount();
      for (let i = 1; i <= count; i++) {
        const nameInput = document.getElementById('name_' + i);
        const qtyInput = document.getElementById('qty_' + i);
        const priceInput = document.getElementById('price_' + i);
        const salePriceInput = document.getElementById('sale_price_' + i);

        if (nameInput) nameInput.value = '';
        if (qtyInput) qtyInput.value = '0';
        if (priceInput) priceInput.value = '0';
        if (salePriceInput) salePriceInput.value = '0';

        // reset computed cells
        const idsToReset = ['total', 'customs', 'vat', 'cip', 'total_customs', 'shipping', 'clearance', 'transport', 'other', 'grand_total', 'unit_cost', 'profit', 'profit_rate'];
        idsToReset.forEach(prefix => {
          const el = document.getElementById(prefix + '_' + i);
          if (el) {
            if (prefix === 'profit_rate') el.textContent = '0.00%';
            else el.textContent = '0.00';
          }
        });
      }
    }

    function calcAllColumns() {
      const count = getItemCount();
      for (let i = 1; i <= count; i++) {
        calculateTotal(i);
      }
    }

    function exportToCSV(filename = 'calculator.csv') {
      const rows = table.querySelectorAll('tr');
      const csv = [];
      rows.forEach(row => {
        const cols = row.querySelectorAll('th,td');
        const line = [];
        cols.forEach(cell => {
          let text = cell.innerText.replace(/\r?\n|\r/g, ' ').trim();
          text = '"' + text.replace(/"/g, '""') + '"';
          line.push(text);
        });
        csv.push(line.join(','));
      });
      const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = filename;
      link.click();
      URL.revokeObjectURL(link.href);
    }

    // attach toolbar button listeners
    if (addItemBtn) addItemBtn.addEventListener('click', addColumn);
    if (removeItemBtn) removeItemBtn.addEventListener('click', removeColumn);
    const calcBtnFallback = document.getElementById('calculateAllBtn');
    if (calcBtnFallback) calcBtnFallback.addEventListener('click', calcAllColumns); // keep compatibility
    const calcNew = document.getElementById('calcAllBtn');
    if (calcNew) calcNew.addEventListener('click', calcAllColumns);
    if (printBtn) printBtn.addEventListener('click', function() { window.print(); });
    if (excelBtn) excelBtn.addEventListener('click', function() { exportToCSV('calculator.csv'); });
    if (pdfBtn) pdfBtn.addEventListener('click', function() { window.print(); });

  });

  // Additional IIFE: row-based toolbar (add/remove rows, clear, calc, print, CSV, PDF)
  (function () {
    // Base elements
    const table  = document.getElementById('itemsTable') || document.querySelector('table');
    if (!table) return;
    const tbody  = document.getElementById('itemsTbody') || table.querySelector('tbody');
    const tfoot  = table.querySelector('tfoot'); // optional totals section
    // look for row-specific buttons (avoid colliding with column buttons)
    const addRowBtn = document.getElementById('addRowBtn');
    const removeRowBtn = document.getElementById('removeRowBtn');
    const clearRowsBtn = document.getElementById('clearRowsBtn');
    const calcRowsBtn = document.getElementById('calcRowsBtn');
    const printRowsBtn = document.getElementById('printRowsBtn');
    const excelRowsBtn = document.getElementById('excelRowsBtn');
    const pdfRowsBtn = document.getElementById('pdfRowsBtn');

    // Locate row template (first data row or #rowTemplate)
    let templateRow = document.getElementById('rowTemplate');
    if (!templateRow) {
      // Fallback: use first tbody row as template
      templateRow = tbody ? tbody.querySelector('tr') : null;
    }

    function cloneRow() {
      if (!templateRow) return null;
      const clone = templateRow.cloneNode(true);
      clone.removeAttribute('id');
      clone.classList.remove('hidden');
      // Reset all editable fields in cloned row
      clone.querySelectorAll('input,select,textarea').forEach(el => {
        if (el.type === 'checkbox' || el.type === 'radio') { el.checked = false; return; }
        if (el.tagName === 'SELECT') { el.selectedIndex = 0; return; }
        el.value = (el.type === 'number' || el.inputMode === 'decimal') ? 0 : '';
      });
      // Clear computed TD values
      clone.querySelectorAll('td').forEach(td => {
        if (td.dataset.calc === 'sumcell') td.textContent = '0';
      });
      return clone;
    }

    function addRow() {
      const newRow = cloneRow();
      if (newRow) tbody.appendChild(newRow);
    }

    function removeRow() {
      if (!tbody) return;
      // Keep at least one visible row
      const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.classList.contains('hidden'));
      if (rows.length > 1) rows[rows.length - 1].remove();
    }

    function clearAllRows() {
      if (!tbody) return;
      tbody.querySelectorAll('input,select,textarea').forEach(el => {
        if (el.type === 'checkbox' || el.type === 'radio') { el.checked = false; return; }
        if (el.tagName === 'SELECT') { el.selectedIndex = 0; return; }
        el.value = (el.type === 'number' || el.inputMode === 'decimal') ? 0 : '';
      });
      if (tfoot) tfoot.querySelectorAll('[data-total]').forEach(t => t.textContent = '0');
    }

    function calcAllRows() {
      // simple row-sum example: compute grand by summing number inputs per tbody
      if (!tbody) return;
      let grand = 0;
      tbody.querySelectorAll('input[type="number"], input[inputmode="decimal"]').forEach(inp => {
        const n = parseFloat(String(inp.value).replace(',', '.'));
        if (!isNaN(n)) grand += n;
      });
      const totalCell = tfoot ? tfoot.querySelector('[data-total="grand"]') : null;
      if (totalCell) totalCell.textContent = grand.toFixed(2);
      else console.debug('Grand total (rows):', grand.toFixed(2));
    }

    function printTable() { window.print(); }

    function exportToCSVRows(filename = 'table_rows.csv') {
      const rows = table.querySelectorAll('tr');
      const csv = [];
      rows.forEach(row => {
        const cols = row.querySelectorAll('th,td');
        const line = [];
        cols.forEach(cell => {
          let text = cell.innerText.replace(/\r?\n|\r/g, ' ').trim();
          text = `"${text.replace(/"/g, '""')}"`;
          line.push(text);
        });
        csv.push(line.join(','));
      });
      const blob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      link.href = URL.createObjectURL(blob);
      link.download = filename;
      link.click();
      URL.revokeObjectURL(link.href);
    }

    // bind only if row-specific buttons exist (prevents double-binding)
    if (addRowBtn) addRowBtn.addEventListener('click', addRow);
    if (removeRowBtn) removeRowBtn.addEventListener('click', removeRow);
    if (clearRowsBtn) clearRowsBtn.addEventListener('click', function(){ if(confirm(@js(__('front.import.calculator.confirm_are_you_sure')))) clearAllRows(); });
    if (calcRowsBtn) calcRowsBtn.addEventListener('click', calcAllRows);
    if (printRowsBtn) printRowsBtn.addEventListener('click', printTable);
    if (excelRowsBtn) excelRowsBtn.addEventListener('click', function(){ exportToCSVRows('table_rows.csv'); });
    if (pdfRowsBtn) pdfRowsBtn.addEventListener('click', function(){ window.print(); });

  })();
</script>
@endpush
