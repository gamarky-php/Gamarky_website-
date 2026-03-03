{{-- Overhead Pools Table - التكاليف غير المباشرة --}}
<div>
  <div class="text-sm font-semibold mb-2">{{ __('front.manufacturing.table_oh.title') }}</div>
  <table class="w-full text-sm table-fixed border-separate border-spacing-0">
    <thead>
      <tr class="bg-slate-50">
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_oh.pool') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_oh.basis') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_oh.rate') }}</th>
        <th class="px-3 py-2 text-center">{{ __('front.manufacturing.table_oh.cost') }}</th>
        <th class="px-3 py-2"></th>
      </tr>
    </thead>
    <tbody>
      <template x-for="(oh, i) in ohs" :key="'oh'+i">
        <tr>
          <td class="px-2 py-1">
            <input x-model="oh.name" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" placeholder="{{ __('front.manufacturing.table_oh.pool_placeholder') }}" />
          </td>
          <td class="px-2 py-1">
            <select x-model="oh.basis" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
              <option value="machine_hour">{{ __('front.manufacturing.table_oh.machine_hour') }}</option>
              <option value="labor_hour">{{ __('front.manufacturing.table_oh.labor_hour') }}</option>
              <option value="material_pct">{{ __('front.manufacturing.table_oh.material_pct') }}</option>
            </select>
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.0001" x-model.number="oh.rate" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1 text-center font-semibold text-amber-700" x-text="fmt(ohCost(i))"></td>
          <td class="px-2 py-1 text-center">
            <button @click="removeOh(i)" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">{{ __('front.manufacturing.table_oh.delete') }}</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
