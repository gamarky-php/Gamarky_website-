{{-- Overhead Pools Table - التكاليف غير المباشرة --}}
<div>
  <div class="text-sm font-semibold mb-2">تكاليف غير مباشرة</div>
  <table class="w-full text-sm table-fixed border-separate border-spacing-0">
    <thead>
      <tr class="bg-slate-50">
        <th class="px-3 py-2 text-center">المجمع</th>
        <th class="px-3 py-2 text-center">الأساس</th>
        <th class="px-3 py-2 text-center">المعدل</th>
        <th class="px-3 py-2 text-center">التكلفة</th>
        <th class="px-3 py-2"></th>
      </tr>
    </thead>
    <tbody>
      <template x-for="(oh, i) in ohs" :key="'oh'+i">
        <tr>
          <td class="px-2 py-1">
            <input x-model="oh.name" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" placeholder="اسم المجمع" />
          </td>
          <td class="px-2 py-1">
            <select x-model="oh.basis" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm">
              <option value="machine_hour">ساعة ماكينة</option>
              <option value="labor_hour">ساعة عمل</option>
              <option value="material_pct">٪ من المواد</option>
            </select>
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.0001" x-model.number="oh.rate" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1 text-center font-semibold text-amber-700" x-text="fmt(ohCost(i))"></td>
          <td class="px-2 py-1 text-center">
            <button @click="removeOh(i)" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">حذف</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
