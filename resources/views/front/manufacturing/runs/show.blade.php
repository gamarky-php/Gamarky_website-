@extends('layouts.app')
@section('title', 'تفاصيل التشغيل')

@section('content')
<div class="page-wrapper mx-auto px-3 md:px-6 py-6" dir="rtl">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $run->product_name }}</h1>
      <p class="text-gray-600">{{ $run->run_code }} | {{ $run->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('mfg.runs.index') }}" class="rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition">
        رجوع
      </a>
      <button onclick="document.getElementById('quoteModal').classList.remove('hidden')" class="rounded-lg px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow transition">
        📊 إنشاء عرض سعر
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-blue-900 mb-1">حجم الدفعة</h3>
      <p class="text-2xl font-bold text-blue-700">{{ number_format($run->batch_size, 0) }} {{ $run->batch_unit }}</p>
    </div>
    <div class="bg-green-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-green-900 mb-1">تكلفة الدفعة</h3>
      <p class="text-2xl font-bold text-green-700">{{ number_format($run->total_batch_cost, 2) }} {{ $run->currency }}</p>
    </div>
    <div class="bg-purple-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-purple-900 mb-1">تكلفة الوحدة</h3>
      <p class="text-2xl font-bold text-purple-700">{{ number_format($run->unit_cost, 2) }} {{ $run->currency }}</p>
    </div>
    <div class="bg-rose-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-rose-900 mb-1">سعر بيع مقترح</h3>
      <p class="text-2xl font-bold text-rose-700">{{ $run->suggested_price ? number_format($run->suggested_price, 2) . ' ' . $run->currency : '-' }}</p>
    </div>
  </div>

  {{-- BOM --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">قائمة المواد (BOM)</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المادة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الكمية/وحدة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">السعر</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة الكلية</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المورد</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->bomItems as $item)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $item->material_name }} @if($item->material_code)<span class="text-gray-500 text-xs">({{ $item->material_code }})</span>@endif</td>
          <td class="px-4 py-3">{{ number_format($item->qty_per_unit, 4) }} {{ $item->uom }}</td>
          <td class="px-4 py-3">{{ number_format($item->unit_price, 2) }}</td>
          <td class="px-4 py-3 font-semibold text-blue-700">{{ number_format($item->total_cost, 2) }}</td>
          <td class="px-4 py-3 text-gray-600">{{ $item->supplier ?? '-' }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-blue-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-blue-900">إجمالي المواد</td>
          <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($run->total_material_cost, 2) }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Operations --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">عمليات التشغيل</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">العملية</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">إعداد (س)</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">دورة (د)</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">عمالة/س</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">آلة/س</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة الكلية</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->operations as $op)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $op->operation_name }} @if($op->operation_code)<span class="text-gray-500 text-xs">({{ $op->operation_code }})</span>@endif</td>
          <td class="px-4 py-3">{{ number_format($op->setup_time_hours, 2) }}</td>
          <td class="px-4 py-3">{{ number_format($op->cycle_time_minutes, 2) }}</td>
          <td class="px-4 py-3">{{ number_format($op->labor_rate_per_hour, 2) }}</td>
          <td class="px-4 py-3">{{ number_format($op->machine_rate_per_hour, 2) }}</td>
          <td class="px-4 py-3 font-semibold text-green-700">{{ number_format($op->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-green-50">
          <td colspan="5" class="px-4 py-3 text-right font-bold text-green-900">إجمالي العمليات</td>
          <td class="px-4 py-3 font-bold text-green-700">{{ number_format($run->total_operation_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Overheads --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">تكاليف غير مباشرة</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">البند</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">طريقة التخصيص</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">القيمة/النسبة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة الكلية</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->overheads as $oh)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $oh->overhead_name }}</td>
          <td class="px-4 py-3">{{ $oh->allocation_method === 'fixed' ? 'ثابت' : 'نسبة مئوية' }}</td>
          <td class="px-4 py-3">{{ $oh->allocation_method === 'fixed' ? number_format($oh->amount, 2) : number_format($oh->rate_pct, 2) . '%' }}</td>
          <td class="px-4 py-3 font-semibold text-purple-700">{{ number_format($oh->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-purple-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-purple-900">إجمالي غير المباشرة</td>
          <td class="px-4 py-3 font-bold text-purple-700">{{ number_format($run->total_overhead_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Quotes --}}
  @if($run->quotes->count() > 0)
  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3">
      <h3 class="text-white font-bold text-lg">عروض الأسعار المرتبطة</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">رقم العرض</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">العميل</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الكمية</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الهامش</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الإجمالي</th>
          <th class="px-4 py-3 text-center font-semibold text-gray-700">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->quotes as $quote)
        <tr class="border-t">
          <td class="px-4 py-3 font-mono text-indigo-700">{{ $quote->quote_number }}</td>
          <td class="px-4 py-3">{{ $quote->client_name ?? '-' }}</td>
          <td class="px-4 py-3">{{ number_format($quote->qty, 0) }}</td>
          <td class="px-4 py-3">{{ number_format($quote->margin_pct, 2) }}%</td>
          <td class="px-4 py-3 font-semibold text-green-700">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</td>
          <td class="px-4 py-3 text-center space-x-2 space-x-reverse">
            <a href="{{ route('mfg.quotes.show', $quote->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">عرض</a>
            <a href="{{ route('mfg.quotes.pdf', $quote->id) }}" target="_blank" class="text-red-600 hover:text-red-800 font-semibold text-sm">PDF</a>
            <a href="{{ route('mfg.quotes.excel', $quote->id) }}" class="text-green-600 hover:text-green-800 font-semibold text-sm">Excel</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

{{-- Quote Modal --}}
<div id="quoteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" dir="rtl">
  <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
    <h3 class="text-xl font-bold text-gray-900 mb-4">إنشاء عرض سعر</h3>
    <form method="POST" action="{{ route('mfg.quotes.generate', $run->id) }}">
      @csrf
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">اسم العميل</label>
        <input type="text" name="client_name" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">البريد الإلكتروني</label>
        <input type="email" name="client_email" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">هامش الربح %</label>
        <input type="number" name="margin_pct" step="0.1" value="25" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">الكمية</label>
        <input type="number" name="qty" step="1" value="100" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">صالح حتى</label>
        <input type="date" name="valid_until" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="flex gap-2">
        <button type="submit" class="flex-1 rounded-lg px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow transition">إنشاء</button>
        <button type="button" onclick="document.getElementById('quoteModal').classList.add('hidden')" class="flex-1 rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition">إلغاء</button>
      </div>
    </form>
  </div>
</div>
@endsection
