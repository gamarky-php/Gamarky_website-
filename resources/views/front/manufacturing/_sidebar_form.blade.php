{{-- Sidebar Form - معلومات المنتج والدفعة --}}
<div class="bg-white rounded-[10px] p-3 md:p-[15px] shadow-[0_4px_15px_rgba(0,0,0,0.08)] mb-4">
  <div class="mb-3 rounded-md bg-gradient-to-l from-[#667eea] to-[#764ba2] text-white px-3 py-2 text-sm font-semibold text-center">
    {{ __('front.manufacturing.sidebar.title') }}
  </div>
  
  <div class="space-y-3">
    <div>
      <label class="block text-xs text-slate-600 mb-1">{{ __('front.manufacturing.sidebar.product') }}</label>
      <select x-model="form.product_id" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        <option value="">{{ __('front.manufacturing.sidebar.select_product') }}</option>
        <template x-for="p in products" :key="p.id">
          <option :value="p.id" x-text="p.name + ' (' + p.sku + ')'"></option>
        </template>
      </select>
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">{{ __('front.manufacturing.sidebar.batch_size') }}</label>
      <input type="number" min="1" x-model.number="form.batch" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">{{ __('front.manufacturing.sidebar.scrap_pct') }}</label>
      <input type="number" step="0.01" x-model.number="form.scrap_pct" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">{{ __('front.manufacturing.sidebar.currency') }}</label>
      <select x-model="form.currency" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        <option>USD</option>
        <option>EUR</option>
        <option>EGP</option>
      </select>
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">{{ __('front.manufacturing.sidebar.fx_rate') }}</label>
      <input type="number" step="0.0001" x-model.number="form.fx_rate" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">{{ __('front.manufacturing.sidebar.target_margin') }}</label>
      <input type="number" step="0.01" x-model.number="form.margin_pct" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>
  </div>

  <div class="mt-4 pt-4 border-t border-gray-200">
    <div class="text-xs text-gray-600 space-y-1">
      <div class="flex justify-between">
        <span>{{ __('front.manufacturing.sidebar.material_cost') }}</span>
        <span class="font-semibold" x-text="fmt(total.material)"></span>
      </div>
      <div class="flex justify-between">
        <span>{{ __('front.manufacturing.sidebar.operations_cost') }}</span>
        <span class="font-semibold" x-text="fmt(total.operations)"></span>
      </div>
      <div class="flex justify-between">
        <span>{{ __('front.manufacturing.sidebar.overhead_cost') }}</span>
        <span class="font-semibold" x-text="fmt(total.overhead)"></span>
      </div>
      <div class="flex justify-between font-bold text-blue-700 pt-2 border-t">
        <span>{{ __('front.manufacturing.sidebar.batch_cost') }}</span>
        <span x-text="fmt(total.batch)"></span>
      </div>
      <div class="flex justify-between font-bold text-green-700">
        <span>{{ __('front.manufacturing.sidebar.unit_cost') }}</span>
        <span x-text="fmt(total.unit)"></span>
      </div>
      <div class="flex justify-between font-bold text-purple-700">
        <span>{{ __('front.manufacturing.sidebar.suggested_price') }}</span>
        <span x-text="fmt(total.target_price)"></span>
      </div>
    </div>
  </div>
</div>
