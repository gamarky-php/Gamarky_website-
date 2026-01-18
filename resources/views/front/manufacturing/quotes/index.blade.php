@extends('layouts.app')
@section('title', 'قائمة عروض الأسعار')

@section('content')
<div class="page-wrapper mx-auto px-3 md:px-6 py-6" dir="rtl">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">عروض الأسعار التصنيعية</h1>
      <p class="text-gray-600">جميع عروض الأسعار المحفوظة</p>
    </div>
    <a href="{{ route('mfg.calculator') }}" class="rounded-lg px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
      + عرض جديد
    </a>
  </div>

  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">رقم العرض</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المنتج</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">العميل</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الكمية</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الهامش</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الإجمالي</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التاريخ</th>
          <th class="px-4 py-3 text-center font-semibold text-gray-700">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($quotes as $quote)
        <tr class="border-t hover:bg-gray-50">
          <td class="px-4 py-3 font-mono text-indigo-700">{{ $quote->quote_number }}</td>
          <td class="px-4 py-3 font-semibold">{{ $quote->costRun->product_name }}</td>
          <td class="px-4 py-3">{{ $quote->client_name ?? '-' }}</td>
          <td class="px-4 py-3">{{ number_format($quote->qty, 0) }}</td>
          <td class="px-4 py-3">{{ number_format($quote->margin_pct, 2) }}%</td>
          <td class="px-4 py-3 font-semibold text-green-700">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</td>
          <td class="px-4 py-3 text-gray-600">{{ $quote->created_at->format('Y-m-d') }}</td>
          <td class="px-4 py-3 text-center space-x-2 space-x-reverse">
            <a href="{{ route('mfg.quotes.show', $quote->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">عرض</a>
            <a href="{{ route('mfg.quotes.pdf', $quote->id) }}" target="_blank" class="text-red-600 hover:text-red-800 font-semibold text-sm">PDF</a>
            <a href="{{ route('mfg.quotes.excel', $quote->id) }}" class="text-green-600 hover:text-green-800 font-semibold text-sm">Excel</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="px-4 py-8 text-center text-gray-500">
            لا توجد عروض أسعار محفوظة
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $quotes->links() }}
  </div>
</div>
@endsection
