{{-- السايدبار - مدخلات الشحنة --}}
<div class="bg-white rounded-[10px] p-3 md:p-[15px] shadow-[0_4px_15px_rgba(0,0,0,0.08)] mb-4">
  {{-- عنوان --}}
  <div class="mb-3 rounded-md bg-gradient-to-l from-[#667eea] to-[#764ba2] text-white px-3 py-2 text-sm font-semibold text-center">
    مدخلات الشحنة
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
      <label class="block text-xs text-slate-600 mb-1 font-medium">العملة</label>
      <select x-model="form.currency" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="USD">USD - دولار أمريكي</option>
        <option value="EUR">EUR - يورو</option>
        <option value="SAR">SAR - ريال سعودي</option>
        <option value="AED">AED - درهم إماراتي</option>
        <option value="EGP">EGP - جنيه مصري</option>
      </select>
    </div>

    {{-- سعر الصرف --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">سعر الصرف</label>
      <input type="number" step="0.0001" x-model.number="form.fx_rate" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="1.0000" />
    </div>

    {{-- طريقة الشحن --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">طريقة الشحن</label>
      <select x-model="form.method" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="sea">🚢 بحري</option>
        <option value="air">✈️ جوي</option>
        <option value="land">🚛 بري</option>
      </select>
    </div>

    {{-- تاريخ الشحن المتوقع --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">تاريخ الشحن المتوقع (ETD)</label>
      <input type="date" x-model="form.etd" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
    </div>

    {{-- بلد المنشأ --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">بلد المنشأ</label>
      <input type="text" x-model="form.origin_country" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="مثال: الصين" />
    </div>

    {{-- ميناء التحميل --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">ميناء التحميل (POL)</label>
      <input type="text" x-model="form.pol" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="مثال: Shanghai" />
    </div>

    {{-- ميناء الوصول --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">ميناء الوصول (POD)</label>
      <input type="text" x-model="form.pod" 
             class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
             placeholder="مثال: Jeddah" />
    </div>

    {{-- نوع الحاوية --}}
    <div>
      <label class="block text-xs text-slate-600 mb-1 font-medium">نوع الحاوية</label>
      <select x-model="form.container_type" 
              class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">-- اختر --</option>
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
      اختر الـ Incoterm لتفعيل البنود المناسبة
    </p>
  </div>
</div>
