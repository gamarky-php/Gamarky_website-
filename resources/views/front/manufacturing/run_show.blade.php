@extends('layouts.app')
@section('title', 'تفاصيل تشغيل التكلفة')

@section('content')
<div class="page-wrapper mx-auto px-3 md:px-6 py-6" dir="rtl">
  <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $run->product->name }}</h1>
      <p class="text-gray-600">SKU: {{ $run->product->sku }} | حجم الدفعة: {{ number_format($run->batch_size) }} وحدة</p>
      <p class="text-sm text-gray-500">{{ $run->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('mfg.calculator') }}" class="rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition text-sm">
        🔙 رجوع
      </a>
      <a href="{{ route('mfg.runs.pdf', $run->id) }}" target="_blank" class="rounded-lg px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold shadow transition text-sm">
        📄 PDF
      </a>
      <a href="{{ route('mfg.runs.excel', $run->id) }}" class="rounded-lg px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-sm">
        📊 Excel
      </a>
      <button onclick="document.getElementById('quoteModal').classList.remove('hidden')" class="rounded-lg px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow transition text-sm">
        � إنشاء عرض سعر
      </button>
    </div>
  </div>

  {{-- ملخص التكاليف --}}
  <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-emerald-700 mb-1">تكلفة المواد</h3>
      <p class="text-2xl font-bold text-emerald-900">{{ number_format($run->bomItems->sum('total_cost'), 2) }}</p>
      <p class="text-xs text-emerald-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-blue-700 mb-1">تكلفة العمليات</h3>
      <p class="text-2xl font-bold text-blue-900">{{ number_format($run->ops->sum('total_cost'), 2) }}</p>
      <p class="text-xs text-blue-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-amber-700 mb-1">غير مباشرة</h3>
      @php
        $overheadTotal = $run->overheads->sum(function($pool) use ($run) {
          return $pool->calculateCost($run);
        });
      @endphp
      <p class="text-2xl font-bold text-amber-900">{{ number_format($overheadTotal, 2) }}</p>
      <p class="text-xs text-amber-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-purple-700 mb-1">تكلفة الدفعة</h3>
      <p class="text-2xl font-bold text-purple-900">{{ number_format($run->total_cost, 2) }}</p>
      <p class="text-xs text-purple-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-indigo-700 mb-1">تكلفة الوحدة</h3>
      <p class="text-2xl font-bold text-indigo-900">{{ number_format($run->unit_cost, 4) }}</p>
      <p class="text-xs text-indigo-600 mt-1">{{ $run->currency }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
    <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-sm font-semibold text-rose-700 mb-1">سعر البيع المقترح</h3>
      <p class="text-3xl font-bold text-rose-700">{{ $run->target_price ? number_format($run->target_price, 2) . ' ' . $run->currency : '-' }}</p>
      <p class="text-xs text-rose-600 mt-1">هامش ربح {{ number_format($run->margin_pct, 2) }}%</p>
    </div>
    <div class="bg-slate-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-slate-900 mb-1">معلومات إضافية</h3>
      <div class="text-sm space-y-1">
        <div><span class="text-slate-600">نسبة الفاقد:</span> <span class="font-semibold">{{ number_format($run->scrap_pct, 2) }}%</span></div>
        <div><span class="text-slate-600">سعر الصرف:</span> <span class="font-semibold">{{ number_format($run->fx_rate, 4) }}</span></div>
        <div><span class="text-slate-600">الحالة:</span> <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $run->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $run->status === 'draft' ? 'مسودة' : 'معتمد' }}</span></div>
      </div>
    </div>
  </div>

  {{-- BOM --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">قائمة المواد (BOM)</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المادة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الكمية/دفعة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">سعر الوحدة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">فاقد %</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->bomItems as $item)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $item->material }}</td>
          <td class="px-4 py-3">{{ number_format($item->qty_per_batch, 4) }} {{ $item->uom }}</td>
          <td class="px-4 py-3">{{ number_format($item->unit_price, 4) }}</td>
          <td class="px-4 py-3">{{ number_format($item->scrap_pct, 2) }}%</td>
          <td class="px-4 py-3 font-semibold text-emerald-700">{{ number_format($item->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-emerald-50">
          <td colspan="4" class="px-4 py-3 text-right font-bold text-emerald-900">الإجمالي</td>
          <td class="px-4 py-3 font-bold text-emerald-700">{{ number_format($run->bomItems->sum('total_cost'), 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Operations --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">عمليات التشغيل</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التسلسل</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">العملية</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">إعداد (س)</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">تشغيل (س)</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">عمالة/س</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">آلة/س</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->ops as $op)
        <tr class="border-t">
          <td class="px-4 py-3">{{ $op->op_seq }}</td>
          <td class="px-4 py-3 font-semibold">{{ $op->operation }}</td>
          <td class="px-4 py-3">{{ number_format($op->setup_time_hr, 3) }}</td>
          <td class="px-4 py-3">{{ number_format($op->run_time_hr, 3) }}</td>
          <td class="px-4 py-3">{{ number_format($op->labor_rate, 2) }}</td>
          <td class="px-4 py-3">{{ number_format($op->machine_rate, 2) }}</td>
          <td class="px-4 py-3 font-semibold text-blue-700">{{ number_format($op->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-blue-50">
          <td colspan="6" class="px-4 py-3 text-right font-bold text-blue-900">الإجمالي</td>
          <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($run->ops->sum('total_cost'), 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Overheads --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">التكاليف غير المباشرة</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">البند</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">أساس التحميل</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المعدل</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة المحملة</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->overheads as $pool)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $pool->name }}</td>
          <td class="px-4 py-3">
            @if($pool->basis === 'machine_hour') ساعات الآلة
            @elseif($pool->basis === 'labor_hour') ساعات العمل
            @else نسبة من المواد
            @endif
          </td>
          <td class="px-4 py-3">{{ number_format($pool->rate, 4) }}</td>
          <td class="px-4 py-3 font-semibold text-amber-700">{{ number_format($pool->calculateCost($run), 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-amber-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-amber-900">الإجمالي</td>
          @php
            $overheadTotalSum = $run->overheads->sum(function($p) use ($run) {
              return $p->calculateCost($run);
            });
          @endphp
          <td class="px-4 py-3 font-bold text-amber-700">{{ number_format($overheadTotalSum, 2) }}</td>
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
<div id="quoteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50" dir="rtl">
  <div class="flex items-center justify-center min-h-screen p-4">
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
          <input type="number" name="margin_pct" step="0.1" value="{{ $run->margin_pct }}" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
        </div>
        <div class="mb-3">
          <label class="block text-sm font-semibold text-gray-700 mb-1">الكمية</label>
          <input type="number" name="qty" step="1" value="{{ $run->batch_size }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
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
</div>
@push('scripts')
<script>console.info('Run show view loaded');</script>
@endpush
@endsection
