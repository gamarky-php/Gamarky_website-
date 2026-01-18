@extends('layouts.app')
@section('title', 'تفاصيل عرض السعر')

@section('content')
<div class="page-wrapper mx-auto px-3 md:px-6 py-6" dir="rtl">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">عرض السعر: {{ $quote->quote_number }}</h1>
      <p class="text-gray-600">{{ $quote->costRun->product_name }} | {{ $quote->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('mfg.quotes.index') }}" class="rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition">
        رجوع
      </a>
      <a href="{{ route('mfg.quotes.pdf', $quote->id) }}" target="_blank" class="rounded-lg px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold shadow transition">
        📄 PDF
      </a>
      <a href="{{ route('mfg.quotes.excel', $quote->id) }}" class="rounded-lg px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition">
        📊 Excel
      </a>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h3>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-gray-600">الاسم:</span>
          <span class="font-semibold">{{ $quote->client_name ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">البريد:</span>
          <span class="font-semibold">{{ $quote->client_email ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">الهاتف:</span>
          <span class="font-semibold">{{ $quote->client_phone ?? '-' }}</span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-4">تفاصيل العرض</h3>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-gray-600">تكلفة الوحدة:</span>
          <span class="font-semibold">{{ number_format($quote->unit_cost, 2) }} {{ $quote->currency }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">هامش الربح:</span>
          <span class="font-semibold">{{ number_format($quote->margin_pct, 2) }}%</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">سعر الوحدة:</span>
          <span class="font-semibold text-purple-700">{{ number_format($quote->unit_price, 2) }} {{ $quote->currency }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">الكمية:</span>
          <span class="font-semibold">{{ number_format($quote->qty, 0) }}</span>
        </div>
        <div class="flex justify-between border-t pt-2">
          <span class="text-gray-900 font-bold">الإجمالي:</span>
          <span class="font-bold text-green-700 text-xl">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">صالح حتى:</span>
          <span class="font-semibold">{{ $quote->valid_until ? $quote->valid_until->format('Y-m-d') : '-' }}</span>
        </div>
      </div>
    </div>
  </div>

  @if($quote->notes)
  <div class="bg-yellow-50 rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-2">ملاحظات</h3>
    <p class="text-gray-700">{{ $quote->notes }}</p>
  </div>
  @endif

  {{-- تفاصيل التكلفة --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">تفاصيل التكلفة - قائمة المواد (BOM)</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المادة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الكمية/وحدة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">السعر</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quote->costRun->bomItems as $item)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $item->material_name }}</td>
          <td class="px-4 py-3">{{ number_format($item->qty_per_unit, 4) }} {{ $item->uom }}</td>
          <td class="px-4 py-3">{{ number_format($item->unit_price, 2) }}</td>
          <td class="px-4 py-3 font-semibold text-blue-700">{{ number_format($item->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-blue-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-blue-900">إجمالي المواد</td>
          <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($quote->costRun->total_material_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

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
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quote->costRun->operations as $op)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $op->operation_name }}</td>
          <td class="px-4 py-3">{{ number_format($op->setup_time_hours, 2) }}</td>
          <td class="px-4 py-3">{{ number_format($op->cycle_time_minutes, 2) }}</td>
          <td class="px-4 py-3 font-semibold text-green-700">{{ number_format($op->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-green-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-green-900">إجمالي العمليات</td>
          <td class="px-4 py-3 font-bold text-green-700">{{ number_format($quote->costRun->total_operation_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">تكاليف غير مباشرة</h3>
    </div>
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">البند</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">طريقة التخصيص</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التكلفة</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quote->costRun->overheads as $oh)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $oh->overhead_name }}</td>
          <td class="px-4 py-3">{{ $oh->allocation_method === 'fixed' ? 'ثابت' : 'نسبة مئوية' }}</td>
          <td class="px-4 py-3 font-semibold text-purple-700">{{ number_format($oh->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-purple-50">
          <td colspan="2" class="px-4 py-3 text-right font-bold text-purple-900">إجمالي غير المباشرة</td>
          <td class="px-4 py-3 font-bold text-purple-700">{{ number_format($quote->costRun->total_overhead_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
