{{-- Sidebar Form - معلومات المنتج والدفعة --}}
<div class="bg-white rounded-[10px] p-3 md:p-[15px] shadow-[0_4px_15px_rgba(0,0,0,0.08)] mb-4">
  <div class="mb-3 rounded-md bg-gradient-to-l from-[#667eea] to-[#764ba2] text-white px-3 py-2 text-sm font-semibold text-center">
    مدخلات التصنيع
  </div>
  
  <div class="space-y-3">
    <div>
      <label class="block text-xs text-slate-600 mb-1">المنتج</label>
      <select x-model="form.product_id" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        <option value="">-- اختر المنتج --</option>
        <template x-for="p in products" :key="p.id">
          <option :value="p.id" x-text="p.name + ' (' + p.sku + ')'"></option>
        </template>
      </select>
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">حجم الدفعة</label>
      <input type="number" min="1" x-model.number="form.batch" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">هالك %</label>
      <input type="number" step="0.01" x-model.number="form.scrap_pct" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">العملة</label>
      <select x-model="form.currency" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        <option>USD</option>
        <option>EUR</option>
        <option>EGP</option>
      </select>
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">سعر الصرف</label>
      <input type="number" step="0.0001" x-model.number="form.fx_rate" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>

    <div>
      <label class="block text-xs text-slate-600 mb-1">هامش الربح المستهدف %</label>
      <input type="number" step="0.01" x-model.number="form.margin_pct" @change="recalc" class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500" />
    </div>
  </div>

  <div class="mt-4 pt-4 border-t border-gray-200">
    <div class="text-xs text-gray-600 space-y-1">
      <div class="flex justify-between">
        <span>تكلفة المواد:</span>
        <span class="font-semibold" x-text="fmt(total.material)"></span>
      </div>
      <div class="flex justify-between">
        <span>تكلفة العمليات:</span>
        <span class="font-semibold" x-text="fmt(total.operations)"></span>
      </div>
      <div class="flex justify-between">
        <span>تكاليف غير مباشرة:</span>
        <span class="font-semibold" x-text="fmt(total.overhead)"></span>
      </div>
      <div class="flex justify-between font-bold text-blue-700 pt-2 border-t">
        <span>تكلفة الدفعة:</span>
        <span x-text="fmt(total.batch)"></span>
      </div>
      <div class="flex justify-between font-bold text-green-700">
        <span>تكلفة الوحدة:</span>
        <span x-text="fmt(total.unit)"></span>
      </div>
      <div class="flex justify-between font-bold text-purple-700">
        <span>سعر البيع المقترح:</span>
        <span x-text="fmt(total.target_price)"></span>
      </div>
    </div>
  </div>
</div>
