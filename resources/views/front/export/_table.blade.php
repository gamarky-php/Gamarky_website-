{{-- الجدول الرئيسي - Sticky البيان + أعمدة ديناميكية --}}
<div class="relative">
  <table class="w-full table-fixed text-sm border-separate border-spacing-0">
    {{-- تعريف عرض الأعمدة --}}
    <colgroup>
      <col class="w-48" />
      <template x-for="(col, idx) in columns" :key="'col-' + idx">
        <col class="min-w-[120px]" />
      </template>
    </colgroup>

    {{-- رأس الجدول --}}
    <thead>
      <tr>
        {{-- عمود البيان - Sticky --}}
        <th class="sticky right-0 z-20 bg-gradient-to-l from-[#1d4ed8] to-[#7e22ce] text-white text-center px-3 py-3 text-sm font-bold border-b-2 border-white">
          البيان
        </th>
        
        {{-- أعمدة البنود الديناميكية --}}
        <template x-for="(col, idx) in columns" :key="'head-' + idx">
          <th class="relative bg-gradient-to-r from-blue-500 to-purple-500 text-white px-3 py-3 text-center border-b-2 border-white">
            <div class="flex items-center justify-center gap-2">
              <span x-text="col.title" class="font-semibold"></span>
              <button @click="removeColumn(idx)" 
                      x-show="columns.length > 1"
                      class="text-red-200 hover:text-white hover:bg-red-500 rounded-full w-5 h-5 flex items-center justify-center text-xs transition">
                ×
              </button>
            </div>
          </th>
        </template>
      </tr>
    </thead>

    {{-- جسم الجدول --}}
    <tbody class="divide-y divide-slate-100">
      <template x-for="(row, rowIdx) in rows" :key="row.key">
        <tr x-show="!row.incoterm || row.incoterm.includes(form.incoterm)"
            :class="row.band ? 'bg-gradient-to-r from-slate-50 to-slate-100' : 'bg-white hover:bg-blue-50'">
          
          {{-- عمود البيان - Sticky --}}
          <td class="sticky right-0 z-10 px-3 py-3 text-center font-semibold"
              :class="row.band 
                ? 'bg-gradient-to-l from-[#1d4ed8] to-[#7e22ce] text-white' 
                : 'bg-gradient-to-l from-blue-100 to-purple-100 text-gray-800'">
            <span x-text="row.label"></span>
          </td>

          {{-- خلايا القيم --}}
          <template x-for="(col, colIdx) in columns" :key="row.key + '-' + colIdx">
            <td class="px-2 py-2 text-center">
              {{-- حقل إدخال --}}
              <template x-if="row.input">
                <input type="number" 
                       step="0.01" 
                       x-model.number="values[row.key][colIdx]"
                       @input="recalc()"
                       class="w-full rounded-md border border-slate-300 text-sm px-2 py-1.5 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                       placeholder="0.00" />
              </template>

              {{-- قيمة محسوبة --}}
              <template x-if="!row.input">
                <span :class="profitClass(getValue(row.key, colIdx))" 
                      class="font-semibold"
                      x-text="format(getValue(row.key, colIdx))"></span>
              </template>
            </td>
          </template>
        </tr>
      </template>
    </tbody>

    {{-- تذييل الجدول - المجاميع --}}
    <tfoot>
      <tr class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold">
        <td class="sticky right-0 z-10 bg-gradient-to-l from-indigo-600 to-purple-600 px-3 py-3 text-center">
          المجموع الكلي
        </td>
        <template x-for="(col, idx) in columns" :key="'total-' + idx">
          <td class="px-3 py-3 text-center">
            <span x-text="format(getValue('final_price', idx))"></span>
          </td>
        </template>
      </tr>
    </tfoot>
  </table>
</div>

{{-- رسالة توضيحية --}}
<div class="mt-4 p-3 bg-blue-50 border-r-4 border-blue-500 rounded-lg">
  <div class="flex items-start gap-2">
    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
    </svg>
    <div class="text-sm text-blue-800">
      <p class="font-semibold mb-1">ملاحظات:</p>
      <ul class="list-disc list-inside space-y-1 text-xs">
        <li>الصفوف المظللة باللون الرمادي هي إجماليات محسوبة تلقائياً</li>
        <li>تظهر/تختفي البنود حسب الـ Incoterm المختار</li>
        <li>هامش الربح يُطبق كنسبة مئوية على التكلفة الكلية</li>
      </ul>
    </div>
  </div>
</div>
