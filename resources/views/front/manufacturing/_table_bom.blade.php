{{-- BOM Table - قائمة المواد --}}
<div class="mb-4">
  <div class="text-sm font-semibold mb-2">{{ __('front.manufacturing.table_bom.title') }}</div>
  <table class="w-full text-sm table-fixed border-separate border-spacing-0">
    <thead>
      <tr class="bg-slate-50">
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_bom.material') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_bom.uom') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_bom.qty_per_batch') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_bom.price_per_unit') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_bom.scrap_pct') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_bom.cost') }}</th>
        <th class="px-3 py-2"></th>
      </tr>
    </thead>
    <tbody>
      <template x-for="(it, i) in bom" :key="'bom'+i">
        <tr>
          <td class="px-2 py-1">
            <input x-model="it.material" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" placeholder="{{ __('front.manufacturing.table_bom.material_placeholder') }}" />
          </td>
          <td class="px-2 py-1">
            <input x-model="it.uom" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.0001" x-model.number="it.qty" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.0001" x-model.number="it.price" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.01" x-model.number="it.scrap" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1 text-center font-semibold text-emerald-700" x-text="fmt(bomCost(i))"></td>
          <td class="px-2 py-1 text-center">
            <button @click="removeBom(i)" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">{{ __('front.manufacturing.table_bom.delete') }}</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
