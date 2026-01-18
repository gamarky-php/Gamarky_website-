@extends('layouts.front')
@section('title', 'حاسبة تكلفة التصنيع')

@section('content')
<main x-data="ManuCalc()" class="page-wrapper mx-auto px-3 md:px-6 min-h-screen bg-gray-50 text-slate-800 relative z-0" dir="rtl">
  <!-- Hero -->
  <section class="bg-gradient-to-r from-purple-600 to-blue-700 text-white rounded-2xl shadow-xl p-8 mb-6 mt-16 md:mt-20">
    <h1 class="text-3xl md:text-4xl font-extrabold text-center">حاسبة تكلفة التصنيع</h1>
    <p class="text-center opacity-90 mt-2">"ما لا يمكن قياسه لا يمكن إدارته" — بيتر دراكر</p>
  </section>

  <!-- أدوات أعلى الجدول -->
  <div class="flex flex-wrap items-center gap-3 mb-4">
    
    

    <span class="mx-2 h-6 w-px bg-slate-300"></span>

    <button @click="addBand" class="rounded-lg px-4 py-2 shadow-sm font-medium text-sm bg-green-600 text-white hover:opacity-90">أضف بندًا +</button>
    <button @click="removeBand" class="rounded-lg px-4 py-2 shadow-sm font-medium text-sm bg-red-600 text-white hover:opacity-90">احذف بندًا −</button>

    <span class="mx-2 h-6 w-px bg-slate-300"></span>

    <button @click="clearAll" class="rounded-lg px-4 py-2 shadow-sm font-medium text-sm bg-blue-500 text-white hover:opacity-90">مسح الكل</button>
    <button @click="calcAll" class="rounded-lg px-4 py-2 shadow-sm font-medium text-sm bg-indigo-600 text-white hover:opacity-90">حساب الكل</button>
    <button @click="doPrint" class="rounded-lg px-4 py-2 shadow-sm font-medium text-sm bg-slate-700 text-white">طباعة</button>
  </div>

  <!-- جدول الفاتورة -->
  <div class="bg-white rounded-2xl shadow overflow-x-auto">
    
<!-- === GAMARKY FINAL 15/85 WRAPPER START === -->
<div dir="rtl" class="bg-white rounded-2xl shadow-xl p-4 w-full">
  <div class="flex flex-row-reverse flex-nowrap items-start gap-2 w-full">
    <div class="basis-[15%] shrink-0">
      <aside class="sticky top-6">
        <div class="rounded-2xl shadow-xl overflow-hidden bg-white border border-slate-200">
          <div class="px-4 py-3 bg-gradient-to-r from-indigo-600 to-sky-600 text-white">
            <h3 class="text-lg font-bold tracking-wide text-center">طلب خدمات التصنيع</h3>
          </div>
          <div class="p-3 space-y-3">
            <button class="w-full rounded-xl px-4 py-3 bg-blue-600 text-white font-medium shadow hover:shadow-lg transition">🧱 مورد مواد خام</button>
            <button class="w-full rounded-xl px-4 py-3 bg-slate-600 text-white font-medium shadow hover:shadow-lg transition">🛠️ مورد معدات</button>
            <button class="w-full rounded-xl px-4 py-3 bg-amber-400 text-black font-medium shadow hover:shadow-lg transition">🔧 خدمة الصيانة</button>
            <button class="w-full rounded-xl px-4 py-3 bg-cyan-600 text-white font-medium shadow hover:shadow-lg transition">🚚 خدمة النقل</button>
            <button class="w-full rounded-xl px-4 py-3 bg-emerald-600 text-white font-medium shadow hover:shadow-lg transition">📦 التعبئة والتغليف</button>
            <button class="w-full rounded-xl px-4 py-3 bg-teal-700 text-white font-medium shadow hover:shadow-lg transition">🚛 النقل الداخلي</button>
            <button class="w-full rounded-xl px-4 py-3 bg-teal-800 text-white font-medium shadow hover:shadow-lg transition">🚛 النقل الداخلي</button>
            <button class="w-full rounded-xl px-4 py-3 bg-green-600 text-white font-medium shadow hover:shadow-lg transition">✅ مراقبة الجودة</button>
          </div>
        </div>
      </aside>
    </div>
    <div class="basis-[85%] grow min-w-0">
      <div class="rounded-xl mb-2 bg-gradient-to-l from-purple-600 to-indigo-600 text-white px-5 py-3 shadow">
        <h4 class="text-base md:text-lg font-semibold">فاتورة التصنيع</h4>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-1" dir="rtl">
      <thead>
        <tr>
          <th class="sticky right-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-3 rounded-l-xl text-center">البيان</th>
          <template x-for="c in cols" :key="'h'+c">
            <th class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-3 text-center" x-text="'بند ' + c"></th>
          </template>
        </tr>
      </thead>

      <tbody>
        <!-- صفوف المواد الخام (ديناميكية): مادة خام 1..N -->
        <template x-for="i in materialsCount" :key="'mat'+i">
          <tr>
            <td class="sticky right-0 bg-slate-50/90 backdrop-blur px-4 py-2 text-center font-medium" x-text="'مادة خام ' + i"></td>
            <template x-for="c in cols" :key="'mc'+i+'-'+c">
              <td class="px-2 py-2">
                <!-- قيمة تكلفة المادة الخام مباشرة (للتبسيط). يمكن لاحقًا فصلها إلى كمية*سعر لكل مادة -->
                <input type="number" step="0.01"
                       class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                       x-model.number="materials[i-1][c-1]" @input="calcAll()">
              </td>
            </template>
          </tr>
        </template>

        <!-- 2) الكمية -->
        <tr>
          <td class="sticky right-0 bg-slate-50/90 backdrop-blur px-4 py-2 text-center font-medium">الكمية</td>
          <template x-for="c in cols" :key="'qty'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="qty[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 3) السعر -->
        <tr>
          <td class="sticky right-0 bg-slate-50/90 backdrop-blur px-4 py-2 text-center font-medium">السعر</td>
          <template x-for="c in cols" :key="'price'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="price[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 4) إجمالي التكلفة الأولية = مجموع المواد -->
        <tr>
          <td class="sticky right-0 bg-slate-100 px-4 py-2 text-center font-medium">إجمالي التكلفة الأولية</td>
          <template x-for="c in cols" :key="'prim'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01" readonly disabled
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 bg-slate-100 text-slate-600"
                     :value="primaryTotal(c-1).toFixed(2)">
            </td>
          </template>
        </tr>

        <!-- 5) العمالة -->
        <tr>
          <td class="sticky right-0 bg-slate-50 px-4 py-2 text-center font-medium">العمالة</td>
          <template x-for="c in cols" :key="'lab'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="labor[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 6) الطاقة -->
        <tr>
          <td class="sticky right-0 bg-slate-50 px-4 py-2 text-center font-medium">الطاقة</td>
          <template x-for="c in cols" :key="'pow'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="power[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 7) صيانة المعدات -->
        <tr>
          <td class="sticky right-0 bg-slate-50 px-4 py-2 text-center font-medium">صيانة المعدات</td>
          <template x-for="c in cols" :key="'mnt'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="maintenance[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 8) إيجار المصنع -->
        <tr>
          <td class="sticky right-0 bg-slate-50 px-4 py-2 text-center font-medium">إيجار المصنع</td>
          <template x-for="c in cols" :key="'rent'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="rent[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 9) إجمالي تكلفة التصنيع -->
        <tr>
          <td class="sticky right-0 bg-slate-100 px-4 py-2 text-center font-medium">إجمالي تكلفة التصنيع</td>
          <template x-for="c in cols" :key="'mfg'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01" readonly disabled
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 bg-slate-100 text-slate-600"
                     :value="mfgTotal(c-1).toFixed(2)">
            </td>
          </template>
        </tr>

        <!-- 10) تكلفة الوحدة = إجمالي التصنيع ÷ الكمية -->
        <tr>
          <td class="sticky right-0 bg-slate-100 px-4 py-2 text-center font-medium">تكلفة الوحدة</td>
          <template x-for="c in cols" :key="'cpu'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01" readonly disabled
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 bg-slate-100 text-slate-600"
                     :value="unitCost(c-1).toFixed(4)">
            </td>
          </template>
        </tr>

        <!-- 11) سعر البيع المتوقع -->
        <tr>
          <td class="sticky right-0 bg-slate-50 px-4 py-2 text-center font-medium">سعر البيع المتوقع</td>
          <template x-for="c in cols" :key="'sp'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01"
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                     x-model.number="sellPrice[c-1]" @input="calcAll()">
            </td>
          </template>
        </tr>

        <!-- 12) الربح المتوقع للوحدة -->
        <tr>
          <td class="sticky right-0 bg-slate-100 px-4 py-2 text-center font-medium">الربح المتوقع للوحدة</td>
          <template x-for="c in cols" :key="'ppu'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01" readonly disabled
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 bg-slate-100 text-slate-600"
                     :value="profitPerUnit(c-1).toFixed(4)">
            </td>
          </template>
        </tr>

        <!-- 13) إجمالي الأرباح = الربح للوحدة × الكمية -->
        <tr>
          <td class="sticky right-0 bg-slate-100 px-4 py-2 text-center font-medium">إجمالي الأرباح</td>
          <template x-for="c in cols" :key="'tp'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01" readonly disabled
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 bg-slate-100 text-slate-600"
                     :value="totalProfit(c-1).toFixed(2)">
            </td>
          </template>
        </tr>

        <!-- 14) تحليل الربحية % = الربح للوحدة ÷ سعر البيع × 100 -->
        <tr>
          <td class="sticky right-0 bg-slate-100 px-4 py-2 text-center font-medium">تحليل الربحية</td>
          <template x-for="c in cols" :key="'mr'+c">
            <td class="px-2 py-2">
              <input type="number" step="0.01" readonly disabled
                     class="w-full rounded-lg border-slate-200 text-sm px-3 py-2 bg-slate-100 text-slate-600"
                     :value="marginRate(c-1).toFixed(2)">
            </td>
          </template>
        </tr>
      </tbody>
    </table>
      </div>
    </div>
  </div>
</div>
<!-- === GAMARKY FINAL 15/85 WRAPPER END === -->

  </div>
</main>
@endsection

@push('scripts')
<script>
function ManuCalc(){
  return {
    // عدد الأعمدة (بند 1..)
    cols: 3,

    // عدد صفوف المواد الديناميكية
    materialsCount: 2,

    // مصفوفات البيانات لكل عمود
    // المواد: مصفوفة [materialsCount][cols]
    materials: Array.from({length:2}, ()=> Array.from({length:3}, ()=> 0)),
    qty:        Array.from({length:3}, ()=> 0),
    price:      Array.from({length:3}, ()=> 0),
    labor:      Array.from({length:3}, ()=> 0),
    power:      Array.from({length:3}, ()=> 0),
    maintenance:Array.from({length:3}, ()=> 0),
    rent:       Array.from({length:3}, ()=> 0),
    sellPrice:  Array.from({length:3}, ()=> 0),

    // أزرار المواد
    addMaterial(){
      this.materialsCount = Math.min(this.materialsCount + 1, 20);
      this.materials.push(Array.from({length:this.cols}, ()=> 0));
    },
    removeMaterial(){
      if(this.materialsCount>1){
        this.materialsCount--;
        this.materials.pop();
        this.calcAll();
      }
    },

    // أزرار الأعمدة
    addBand(){
      this.cols = Math.min(this.cols+1, 8);
      // وسّع كل الأعمدة في المصفوفات
      this.materials.forEach(row=>row.push(0));
      this.qty.push(0); this.price.push(0); this.labor.push(0);
      this.power.push(0); this.maintenance.push(0); this.rent.push(0);
      this.sellPrice.push(0);
    },
    removeBand(){
      if(this.cols>1){
        this.cols--;
        this.materials.forEach(row=>row.pop());
        this.qty.pop(); this.price.pop(); this.labor.pop();
        this.power.pop(); this.maintenance.pop(); this.rent.pop();
        this.sellPrice.pop();
        this.calcAll();
      }
    },

    clearAll(){
      this.materials = Array.from({length:this.materialsCount}, ()=> Array.from({length:this.cols}, ()=> 0));
      this.qty        = Array.from({length:this.cols}, ()=> 0);
      this.price      = Array.from({length:this.cols}, ()=> 0);
      this.labor      = Array.from({length:this.cols}, ()=> 0);
      this.power      = Array.from({length:this.cols}, ()=> 0);
      this.maintenance= Array.from({length:this.cols}, ()=> 0);
      this.rent       = Array.from({length:this.cols}, ()=> 0);
      this.sellPrice  = Array.from({length:this.cols}, ()=> 0);
    },

    // حسابات
    primaryTotal(col){
      // مجموع المواد الخام (كل صفوف المواد) للعمود المحدد
      let sum = 0;
      for(let i=0;i<this.materialsCount;i++){
        sum += Number(this.materials[i][col]||0);
      }
      return sum;
    },
    mfgTotal(col){
      // إجمالي تكلفة التصنيع = إجمالي التكلفة الأولية + العمالة + الطاقة + الصيانة + الإيجار
      return this.primaryTotal(col)
           + Number(this.labor[col]||0)
           + Number(this.power[col]||0)
           + Number(this.maintenance[col]||0)
           + Number(this.rent[col]||0);
    },
    unitCost(col){
      const q = Number(this.qty[col]||0);
      return q>0 ? (this.mfgTotal(col) / q) : 0;
    },
    profitPerUnit(col){
      return Number(this.sellPrice[col]||0) - this.unitCost(col);
    },
    totalProfit(col){
      return this.profitPerUnit(col) * Number(this.qty[col]||0);
    },
    marginRate(col){
      const sp = Number(this.sellPrice[col]||0);
      return sp>0 ? (this.profitPerUnit(col)/sp)*100 : 0;
    },

    calcAll(){ /* تعتمد القيم على model bindings وتُحتسب on the fly */ },

    doPrint(){ window.print(); }
  }
}
</script>
@endpush
