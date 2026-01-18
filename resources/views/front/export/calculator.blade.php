@extends('layouts.front')

@section('title', 'حاسبة تكلفة التصدير')

@push('styles')
<style>
  .line-clamp-1{display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden}
  .no-spinners::-webkit-outer-spin-button,
  .no-spinners::-webkit-inner-spin-button{ -webkit-appearance:none; margin:0 }
  .no-spinners{ -moz-appearance:textfield; appearance:textfield }
  @keyframes fadeSlideUp{from{opacity:0;transform:translateY(6px)}to{opacity:1;transform:none}}
  .reveal{animation:fadeSlideUp .45s ease-out both}
  /* Table & card tokens (مطابقة لصفحة الاستيراد) */
  .table-card{ border-radius: 1rem; background:#fff; box-shadow: 0 10px 35px rgba(2,8,23,.06); border:1px solid #e2e8f0; }
  .table-header{ background-image: linear-gradient(90deg,#6E7CF2,#6A42C1); color:#fff; }
  .table-th{ font-size:.8rem; font-weight:600; padding:.75rem .75rem; }
  .table-td{ font-size:.9rem; color:#334155; padding:.6rem .75rem; border-top:1px solid #eef2f7; }
  .table-row:hover{ background-color: #eef2ff55; }
  .sticky-col{ position: sticky; inset-inline-end: 0; background: linear-gradient(90deg,#6b21a8,#7c3aed); color:#fff; font-weight:600; border:none; }
  .cell-input{ width:100%; border-radius:.6rem; border:1px solid #cbd5e1; padding:.45rem .6rem; font-size:.9rem; background:#fff; }
  .cell-input:focus{ outline:none; border-color:#6366f1; box-shadow:0 0 0 2px #818cf840; }
  .toolbar-btn{ border-radius:.6rem; padding:.5rem 1rem; font-size:.9rem; font-weight:500; color:#fff; box-shadow:0 1px 2px rgba(0,0,0,.06); }
  .btn-brand{ background-image:linear-gradient(90deg,#6E7CF2,#6A42C1); }
  .btn-green{ background:#059669; } .btn-red{ background:#dc2626; } .btn-blue{ background:#2563eb; }
  .card-title-gradient{ background-image:linear-gradient(90deg,#6E7CF2,#6A42C1); color:#fff; }
  .sidebar-card{ border-radius:1rem; overflow:hidden; box-shadow:0 8px 30px rgba(2,8,23,.05); border:1px solid #eef2f7; background:#fff; }
</style>
@endpush

@section('content')
<main x-data="ExportCalc()" class="relative z-0">
  <section class="bg-gradient-to-r from-purple-600 to-blue-700 text-white rounded-2xl shadow-xl p-8 mb-6 mt-16 md:mt-20">
    <h1 class="text-3xl md:text-4xl font-extrabold text-center">حاسبة تكلفة التصدير</h1>
    <p class="text-center opacity-90 mt-2">"ما لا يمكن قياسه لا يمكن إدارته" — بيتر دراكر</p>
  </section>

  {{-- تخطيط 15% / 85% كما طلبت --}}
  <div class="grid grid-cols-1 md:grid-cols-[15%_85%] md:gap-4" dir="rtl">
    {{-- القائمة الجانبية (15%) --}}
    <aside class="md:col-start-1 md:sticky md:top-24 md:self-start space-y-4 order-1 md:order-none min-w-[220px]">
      <div class="sidebar-card reveal">
        <div class="card-title-gradient px-4 py-3 text-sm font-semibold">مدخلات التصدير</div>
        <div class="bg-white p-4 space-y-4">
          <div>
            <label class="block text-xs text-slate-600 mb-1">بلد المقصد</label>
            <input type="text" class="cell-input" placeholder="أدخل بلد المقصد" x-model="inputs.dest_country">
          </div>

          <div>
            <label class="block text-xs text-slate-600 mb-1">نوع الشحن</label>
            <select class="cell-input" x-model="inputs.ship_type">
              <option value="">اختر نوع الشحن</option>
              <option value="sea">بحري</option>
              <option value="air">جوي</option>
              <option value="land">بري</option>
            </select>
          </div>

          <div>
            <label class="block text-xs text-slate-600 mb-1">نوع الحاوية</label>
            <select class="cell-input" x-model="inputs.container_type">
              <option value="">اختر نوع الحاوية</option>
              <option>20 قدم</option>
              <option>40 قدم</option>
              <option>40HC</option>
              <option>LCL</option>
            </select>
          </div>

          <div>
            <label class="block text-xs text-slate-600 mb-1">منفذ التصدير (ميناء/مطار)</label>
            <input type="text" class="cell-input" placeholder="اختر منفذ التصدير" x-model="inputs.port_loading">
          </div>
          <div>
            <label class="block text-xs text-slate-600 mb-1">منفذ الوصول</label>
            <input type="text" class="cell-input" placeholder="اختر منفذ الوصول" x-model="inputs.port_discharge">
          </div>

          <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-xs text-slate-600 mb-1">وزن البضاعة (طن)</label>
              <input type="number" min="0" step="0.01" class="cell-input no-spinners" x-model.number="inputs.weight_ton" placeholder="0.00">
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">حجم البضاعة (م³)</label>
              <input type="number" min="0" step="0.01" class="cell-input no-spinners" x-model.number="inputs.volume_cbm" placeholder="0.00">
            </div>
          </div>

          <div>
            <label class="block text-xs text-slate-600 mb-1">تاريخ الشحن المتوقع</label>
            <input type="date" class="cell-input" x-model="inputs.etd">
          </div>
        </div>
      </div>

      <div class="sidebar-card reveal">
        <div class="card-title-gradient px-4 py-3 text-sm font-semibold">اقتراحات الخدمات</div>
        <div class="bg-white p-3 space-y-3">
          <button class="w-full rounded-xl border px-3 py-2 text-sm hover:bg-slate-50 transition">وكيل شحن</button>
          <button class="w-full rounded-xl border px-3 py-2 text-sm hover:bg-slate-50 transition">مندوب تخليص</button>
          <button class="w-full rounded-xl border px-3 py-2 text-sm hover:bg-slate-50 transition">تأمين شحن</button>
          <button class="w-full rounded-xl border px-3 py-2 text-sm hover:bg-slate-50 transition">نقل محلي</button>
        </div>
      </div>

      @php($cards = $cards ?? [
        ['title' => 'إعلانات الخدمات', 'items' => [
            ['text'=>'خدمة شحن جوي','href'=>'#'],
            ['text'=>'خدمة شحن بحري','href'=>'#'],
        ]],
      ])

      @if(!empty($cards))
        <div class="sidebar-card reveal">
          <div class="card-title-gradient px-4 py-3 text-sm font-semibold">إعلانات الخدمات</div>
          <div class="p-3 space-y-2">
            @foreach($cards as $card)
              @if(!empty($card['items']))
                <div class="mb-2">
                  <div class="text-sm font-bold mb-1">{{ $card['title'] }}</div>
                  @foreach($card['items'] as $it)
                    <a href="{{ $it['href'] ?? '#' }}" class="block px-3 py-2 rounded hover:bg-gray-100 text-sm">
                      {{ $it['text'] ?? '' }}
                    </a>
                  @endforeach
                </div>
              @endif
            @endforeach
          </div>
        </div>
      @endif
    </aside>

    {{-- جدول الفاتورة + أزرار (85%) --}}
    <div class="w-full overflow-visible">
      <section class="table-card reveal mb-6">
        <div class="table-header px-4 py-3 rounded-t-2xl flex items-center gap-3">
          <h2 class="font-semibold">التكاليف الثابتة للتوزيع</h2>
          <button type="button" class="ms-auto text-white/90 hover:text-white" title="تعليمات"><span class="text-xl">ℹ️</span></button>
        </div>

        <div class="p-4 bg-white rounded-b-2xl">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label class="block text-xs text-slate-600 mb-1">نولون وتوثيق (EGP)</label>
              <input type="number" class="cell-input no-spinners" x-model.number="fixed.freight_docs" min="0" value="0">
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">مصروفات محلية (EGP)</label>
              <input type="number" class="cell-input no-spinners" x-model.number="fixed.local_cost" min="0" value="0">
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">مصاريف تأمين (EGP)</label>
              <input type="number" class="cell-input no-spinners" x-model.number="fixed.insurance" min="0" value="0">
            </div>
            <div>
              <label class="block text-xs text-slate-600 mb-1">مصاريف أخرى (EGP)</label>
              <input type="number" class="cell-input no-spinners" x-model.number="fixed.other" min="0" value="0">
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-3 justify-start mt-4">
            <button type="button" class="toolbar-btn btn-green"  @click="addRow()">أضف بندًا +</button>
            <button type="button" class="toolbar-btn btn-red"    @click="removeRow()">احذف بندًا –</button>
            <button type="button" class="toolbar-btn btn-blue"   @click="clearAll()">مسح الكل</button>
            <button type="button" class="toolbar-btn btn-brand"  @click="calcAll()">حساب الكل</button>
            <button type="button" class="toolbar-btn bg-blue-600"    @click="doPrint()">طباعة</button>
            <button type="button" class="toolbar-btn bg-emerald-600" @click="exportCSV()">Excel</button>
            <button type="button" class="toolbar-btn bg-rose-600"    @click="doPrint()">PDF</button>
          </div>
        </div>
      </section>

      <section class="table-card reveal overflow-hidden">
        <table class="w-full border-separate border-spacing-y-1" dir="rtl">
  <thead class="table-header">
    <tr>
      <th class="table-th sticky-col rounded-l-xl px-4">البيان</th>
      <template x-for="c in cols" :key="'h'+c">
        <th class="table-th" x-text="'بند ' + c"></th>
      </template>
    </tr>
  </thead>
  <tbody>
    <template x-for="(label, i) in labels" :key="'r'+i">
      <tr class="table-row">
        <td class="table-td sticky-col px-4 text-center" x-text="label"></td>
        <template x-for="c in cols" :key="'c'+i+'-'+c">
          <td class="table-td">
            <template x-if="i === 0 || i === 1">
              <input type="text" class="cell-input" placeholder="—" x-model="data[i][c-1]">
            </template>
            <template x-if="i !== 0 && i !== 1">
              <input type="number" step="0.01" class="cell-input no-spinners" placeholder="0.00" x-model.number="data[i][c-1]">
            </template>
          </td>
        </template>
      </tr>
    </template>
  </tbody>
</table>
      </section>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
function ExportCalc(){
return {
    // ثوابت قديمة إن احتجتها يمكنك إعادتها
    fixed: {freight_docs:0, local_cost:0, insurance:0, other:0},
    expected_price: 0,
    // أعمدة الجدول الديناميكية (عدد البنود)
    cols: 3,
    // تسميات عمود البيان
    labels: [
      'البند الجمركي','اسم المنتج','الكمية','السعر الأوليّ','التكلفة الأولية',
      'تكلفة التعبئة والتغليف','تكلفة النقل الداخلي','رسوم الميناء والمناولة',
      'النولون','التأمين','التوثيق والمستندات','رسوم بنكية',
      'السعر النهائى للبند','سعر البيع المتوقع','الربح المتوقع','معدل الربحية'
    ],
    // بيانات الخلايا: [16 صفًا][cols أعمدة]
    data: Array.from({length:16}, ()=> Array.from({length:3}, ()=> '')),

    // أزرار التحكم
    addRow(){ // زيادة عمود "بند"
      this.cols = Math.min(this.cols + 1, 8);
      this.data.forEach(r => r.push(''));
    },
    removeRow(){ // حذف آخر عمود
      if(this.cols > 1){ this.cols -= 1; this.data.forEach(r => r.pop()); }
      this.calcAll();
    },
    clearAll(){ // تفريغ كل القيم
      this.data = Array.from({length:16}, ()=> Array.from({length:this.cols}, ()=> ''));
    },
    calcAll(){ // مكان ربط المعادلات حسب ما تحدده لاحقًا
      // مثال: يمكنك ملء الصف 12 (السعر النهائى للبند) = الكمية*i × السعر الأوليّ + ...
      // نتركها لك لتحديد الصيغة المطلوبة.
    },

    // أدوات مساعدة موجودة مسبقًا
    doPrint(){ window.print(); },
    exportCSV(){
      const rows = [];
      // رأس CSV
      rows.push(['البيان', ...Array.from({length:this.cols}, (_,i)=>`بند ${i+1}`)].join(','));
      // محتوى
      this.labels.forEach((lab, i)=>{
        const line = [lab, ...this.data[i].slice(0,this.cols)].map(v=>(''+(v??'')).replace(/"/g,'""'));
        rows.push(line.map(x=>`"${x}"`).join(','));
      });
      const blob = new Blob([rows.join('\n')], {type:'text/csv;charset=utf-8;'});
      const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'export-calculator.csv'; a.click();
      URL.revokeObjectURL(a.href);
    }
  }
}
</script>
@endpush
