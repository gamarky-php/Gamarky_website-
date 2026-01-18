{{-- Routing Operations Table - عمليات التشغيل --}}
<div class="mb-4">
  <div class="text-sm font-semibold mb-2">العمليات التشغيلية</div>
  <table class="w-full text-sm table-fixed border-separate border-spacing-0">
    <thead>
      <tr class="bg-slate-50">
        <th class="px-3 py-2 text-center">#</th>
        <th class="px-3 py-2 text-center">العملية</th>
        <th class="px-3 py-2 text-center">إعداد (س)</th>
        <th class="px-3 py-2 text-center">تشغيل (س)</th>
        <th class="px-3 py-2 text-center">تعريفة عمل/س</th>
        <th class="px-3 py-2 text-center">تعريفة ماكينة/س</th>
        <th class="px-3 py-2 text-center">التكلفة</th>
        <th class="px-3 py-2"></th>
      </tr>
    </thead>
    <tbody>
      <template x-for="(op, i) in ops" :key="'op'+i">
        <tr>
          <td class="px-2 py-1 text-center" x-text="i+1"></td>
          <td class="px-2 py-1">
            <input x-model="op.operation" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" placeholder="اسم العملية" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.001" x-model.number="op.setup" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.001" x-model.number="op.run" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.01" x-model.number="op.labor" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1">
            <input type="number" step="0.01" x-model.number="op.machine" @change="recalc" class="w-full rounded-md border border-slate-200 px-2 py-1 text-sm" />
          </td>
          <td class="px-2 py-1 text-center font-semibold text-blue-700" x-text="fmt(opCost(i))"></td>
          <td class="px-2 py-1 text-center">
            <button @click="removeOp(i)" class="text-rose-600 hover:text-rose-800 text-xs font-semibold">حذف</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
