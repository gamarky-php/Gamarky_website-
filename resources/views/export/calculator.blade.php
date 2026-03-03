@extends('layouts.app')
@section('title', __('حاسبة تكاليف التصدير'))

@section('content')
<div x-data="exportCalculator()" class="min-h-screen bg-gray-50 p-4">
  <div class="max-w-[98rem] mx-auto">
    <div class="flex flex-col lg:flex-row gap-6">
      {{-- جدول الشحنة (يسار - 85%) --}}
      <div class="lg:w-[85%] bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-600 to-purple-600">
          <h1 class="text-3xl font-bold text-white text-center">{{ __('حاسبة تكاليف التصدير') }}</h1>
          <p class="text-white/90 text-center mt-2">{{ __('احسب تكاليف الشحن الدولي حسب الإنكوترمز') }}</p>
        </div>

        <div class="p-6 overflow-x-auto">
          <form @submit.prevent="submitForm" method="POST" action="{{ route('export.calculator.store') }}">
            @csrf
            <input type="hidden" name="incoterm" :value="incoterm">
            <input type="hidden" name="currency" :value="currency">
            <input type="hidden" name="exchange_rate" :value="exchangeRate">
            <input type="hidden" name="items" :value="JSON.stringify(items)">

            {{-- جدول البنود --}}
            <div class="overflow-x-auto border border-gray-200 rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-indigo-500 to-blue-500">
                  <tr>
                    <th class="sticky right-0 z-20 bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-3 text-center text-sm font-bold text-white">#</th>
                    <th class="sticky right-[4rem] z-20 bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-3 text-center text-sm font-bold text-white min-w-[200px]">{{ __('البيان') }}</th>
                    <template x-for="(item, idx) in items" :key="idx">
                      <th class="px-4 py-3 text-center text-sm font-semibold text-white whitespace-nowrap">
                        <span x-text="`بند ${idx + 1}`"></span>
                        <button type="button" @click="removeItem(idx)" class="mr-2 text-red-200 hover:text-white" x-show="items.length > 1">×</button>
                      </th>
                    </template>
                    <th class="px-4 py-3 text-center">
                        <button type="button" @click="addItem" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg">{{ __('+ بند') }}</button>
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                  {{-- الوصف --}}
                  <tr class="hover:bg-gray-50">
                    <td class="sticky right-0 bg-white px-4 py-3 text-sm text-center font-medium text-gray-900">1</td>
                    <td class="sticky right-[4rem] bg-white px-6 py-3 text-sm font-semibold text-gray-800">{{ __('الوصف') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3">
                        <input type="text" x-model="item.description" :name="`items[${idx}][description]`" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="{{ __('وصف البند') }}">
                      </td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- الكمية --}}
                  <tr class="hover:bg-gray-50">
                    <td class="sticky right-0 bg-white px-4 py-3 text-sm text-center font-medium text-gray-900">2</td>
                    <td class="sticky right-[4rem] bg-white px-6 py-3 text-sm font-semibold text-gray-800">{{ __('الكمية') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3">
                        <input type="number" step="0.01" x-model.number="item.qty" :name="`items[${idx}][qty]`" @input="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                      </td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- سعر الوحدة --}}
                  <tr class="hover:bg-gray-50">
                    <td class="sticky right-0 bg-white px-4 py-3 text-sm text-center font-medium text-gray-900">3</td>
                    <td class="sticky right-[4rem] bg-white px-6 py-3 text-sm font-semibold text-gray-800">{{ __('سعر الوحدة') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3">
                        <input type="number" step="0.01" x-model.number="item.unit_price" :name="`items[${idx}][unit_price]`" @input="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                      </td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- تكلفة EXW --}}
                  <tr class="bg-yellow-50 font-semibold">
                    <td class="sticky right-0 bg-yellow-50 px-4 py-3 text-sm text-center text-gray-900">4</td>
                    <td class="sticky right-[4rem] bg-yellow-50 px-6 py-3 text-sm font-bold text-gray-900">{{ __('تكلفة EXW') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3 text-center text-blue-700" x-text="(item.qty * item.unit_price).toFixed(2)"></td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- شحن داخلي (FOB+) --}}
                  <tr class="hover:bg-gray-50" x-show="['FOB','CFR','CIF'].includes(incoterm)">
                    <td class="sticky right-0 bg-white px-4 py-3 text-sm text-center font-medium text-gray-900">5</td>
                    <td class="sticky right-[4rem] bg-white px-6 py-3 text-sm font-semibold text-gray-800">{{ __('شحن داخلي') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3">
                        <input type="number" step="0.01" x-model.number="item.local_freight" @input="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                      </td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- تكلفة FOB --}}
                  <tr class="bg-green-50 font-semibold" x-show="['FOB','CFR','CIF'].includes(incoterm)">
                    <td class="sticky right-0 bg-green-50 px-4 py-3 text-sm text-center text-gray-900">6</td>
                    <td class="sticky right-[4rem] bg-green-50 px-6 py-3 text-sm font-bold text-gray-900">{{ __('تكلفة FOB') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3 text-center text-green-700" x-text="((item.qty * item.unit_price) + (item.local_freight||0)).toFixed(2)"></td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- شحن دولي (CFR+) --}}
                  <tr class="hover:bg-gray-50" x-show="['CFR','CIF'].includes(incoterm)">
                    <td class="sticky right-0 bg-white px-4 py-3 text-sm text-center font-medium text-gray-900">7</td>
                    <td class="sticky right-[4rem] bg-white px-6 py-3 text-sm font-semibold text-gray-800">{{ __('شحن دولي') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3">
                        <input type="number" step="0.01" x-model.number="item.intl_freight" @input="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                      </td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- تكلفة CFR --}}
                  <tr class="bg-purple-50 font-semibold" x-show="['CFR','CIF'].includes(incoterm)">
                    <td class="sticky right-0 bg-purple-50 px-4 py-3 text-sm text-center text-gray-900">8</td>
                    <td class="sticky right-[4rem] bg-purple-50 px-6 py-3 text-sm font-bold text-gray-900">{{ __('تكلفة CFR') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3 text-center text-purple-700" x-text="((item.qty * item.unit_price) + (item.local_freight||0) + (item.intl_freight||0)).toFixed(2)"></td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- التأمين (CIF) --}}
                  <tr class="hover:bg-gray-50" x-show="incoterm==='CIF'">
                    <td class="sticky right-0 bg-white px-4 py-3 text-sm text-center font-medium text-gray-900">9</td>
                    <td class="sticky right-[4rem] bg-white px-6 py-3 text-sm font-semibold text-gray-800">{{ __('التأمين') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3">
                        <input type="number" step="0.01" x-model.number="item.insurance" @input="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                      </td>
                    </template>
                    <td></td>
                  </tr>

                  {{-- تكلفة CIF --}}
                  <tr class="bg-red-50 font-semibold" x-show="incoterm==='CIF'">
                    <td class="sticky right-0 bg-red-50 px-4 py-3 text-sm text-center text-gray-900">10</td>
                    <td class="sticky right-[4rem] bg-red-50 px-6 py-3 text-sm font-bold text-gray-900">{{ __('تكلفة CIF') }}</td>
                    <template x-for="(item, idx) in items" :key="idx">
                      <td class="px-4 py-3 text-center text-red-700" x-text="((item.qty * item.unit_price) + (item.local_freight||0) + (item.intl_freight||0) + (item.insurance||0)).toFixed(2)"></td>
                    </template>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-6 flex justify-center gap-4">
              <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-lg">
                {{ __('حفظ الشحنة') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- سايدبار (يمين - 15%) Sticky --}}
      <div class="lg:w-[15%] lg:sticky lg:top-4 lg:self-start bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('مدخلات الشحنة') }}</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('الإنكوترمز') }}</label>
            <select x-model="incoterm" @change="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
              <option value="EXW">EXW</option>
              <option value="FOB">FOB</option>
              <option value="CFR">CFR</option>
              <option value="CIF">CIF</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('العملة') }}</label>
            <select x-model="currency" @change="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="SAR">SAR</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('سعر الصرف') }}</label>
            <input type="number" step="0.0001" x-model.number="exchangeRate" @input="recalc" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('مرجع الشحنة') }}</label>
            <input type="text" name="reference" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="{{ __('اختياري') }}">
          </div>

          <div class="pt-4 border-t border-gray-200">
            <h3 class="text-sm font-bold text-gray-700 mb-2">{{ __('الإجمالي') }}</h3>
            <div class="text-2xl font-bold text-blue-600" x-text="totalCost().toFixed(2)"></div>
            <div class="text-xs text-gray-500 mt-1" x-text="currency"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function exportCalculator() {
  return {
    incoterm: 'FOB',
    currency: 'USD',
    exchangeRate: 1,
    items: [
      { description: '', qty: 0, unit_price: 0, local_freight: 0, intl_freight: 0, insurance: 0 }
    ],
    addItem() {
      this.items.push({ description: '', qty: 0, unit_price: 0, local_freight: 0, intl_freight: 0, insurance: 0 });
    },
    removeItem(idx) {
      if (this.items.length > 1) this.items.splice(idx, 1);
      this.recalc();
    },
    recalc() {
      // Trigger reactive update
    },
    totalCost() {
      let sum = 0;
      this.items.forEach(item => {
        let cost = (item.qty || 0) * (item.unit_price || 0);
        if (['FOB','CFR','CIF'].includes(this.incoterm)) cost += (item.local_freight || 0);
        if (['CFR','CIF'].includes(this.incoterm)) cost += (item.intl_freight || 0);
        if (this.incoterm === 'CIF') cost += (item.insurance || 0);
        sum += cost;
      });
      return sum * (this.exchangeRate || 1);
    },
    submitForm(e) {
      e.target.submit();
    }
  }
}
</script>
@endpush
@endsection
