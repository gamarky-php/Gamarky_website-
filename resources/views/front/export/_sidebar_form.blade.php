{{-- السايدبار - مدخلات الشحنة --}}
<div class="bg-white rounded-[10px] p-3 md:p-[15px] shadow-[0_4px_15px_rgba(0,0,0,0.08)] mb-4">
  {{-- عنوان --}}
  <div class="mb-3 rounded-md bg-gradient-to-l from-[#667eea] to-[#764ba2] text-white px-3 py-2 text-sm font-semibold text-center">
    {{ __('front.export.sidebar.title') }}
  </div>

  {{-- حقول النموذج --}}
  <div class="space-y-3">
    {{-- Incoterm --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">Incoterm</label>
      <select x-model="form.incoterm" @change="applyIncoterm()" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="EXW">EXW</option>
        <option value="FOB" selected>FOB</option>
        <option value="CFR">CFR</option>
        <option value="CIF">CIF</option>
      </select>
    </div>

    {{-- العملة --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.currency') }}</label>
      <select x-model="form.currency" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="USD">USD - {{ __('front.export.sidebar.currency_usd') }}</option>
        <option value="EUR">EUR - {{ __('front.export.sidebar.currency_eur') }}</option>
        <option value="SAR">SAR - {{ __('front.export.sidebar.currency_sar') }}</option>
        <option value="AED">AED - {{ __('front.export.sidebar.currency_aed') }}</option>
        <option value="EGP">EGP - {{ __('front.export.sidebar.currency_egp') }}</option>
      </select>
    </div>

    {{-- سعر الصرف --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.fx_rate') }}</label>
      <input type="number" step="0.0001" x-model.number="form.fx_rate" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="1.0000" />
    </div>

    {{-- طريقة الشحن --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.shipping_method') }}</label>
      <select x-model="form.method" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="sea">🚢 {{ __('front.export.sidebar.sea') }}</option>
        <option value="air">✈️ {{ __('front.export.sidebar.air') }}</option>
        <option value="land">🚛 {{ __('front.export.sidebar.land') }}</option>
      </select>
    </div>

    {{-- تاريخ الشحن المتوقع --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.etd') }}</label>
      <input type="date" x-model="form.etd" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
    </div>

    {{-- بلد المنشأ --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.origin_country') }}</label>
      <input type="text" x-model="form.origin_country" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="{{ __('front.export.sidebar.example_china') }}" />
    </div>

    {{-- ميناء التحميل --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.pol') }}</label>
      <input type="text" x-model="form.pol" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="{{ __('front.export.sidebar.example_shanghai') }}" />
    </div>

    {{-- ميناء الوصول --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.pod') }}</label>
      <input type="text" x-model="form.pod" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="{{ __('front.export.sidebar.example_jeddah') }}" />
    </div>

    {{-- نوع الحاوية --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">{{ __('front.export.sidebar.container_type') }}</label>
      <select x-model="form.container_type" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">{{ __('front.export.sidebar.select_option') }}</option>
        <option value="20GP">20' GP</option>
        <option value="40GP">40' GP</option>
        <option value="40HC">40' HC</option>
        <option value="20RF">20' Reefer</option>
        <option value="40RF">40' Reefer</option>
      </select>
    </div>
  </div>

  {{-- معلومات إضافية --}}
  <div class="mt-4 pt-3 border-t border-slate-200">
    <p class="text-xs text-slate-500 text-center">
      {{ __('front.export.sidebar.incoterm_hint') }}
    </p>
  </div>
</div>
