@extends('layouts.app')
@section('title', 'قائمة تشغيلات التكلفة')

@section('content')
<div class="page-wrapper mx-auto px-3 md:px-6 py-6" dir="rtl">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">تشغيلات التكلفة</h1>
      <p class="text-gray-600">جميع تشغيلات حساب التكلفة المحفوظة</p>
    </div>
    <a href="{{ route('mfg.calculator') }}" class="rounded-lg px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
      + تشغيل جديد
    </a>
  </div>

  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="w-full text-sm" dir="rtl">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">رمز التشغيل</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">المنتج</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">حجم الدفعة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">تكلفة الوحدة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">سعر مقترح</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">الحالة</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">التاريخ</th>
          <th class="px-4 py-3 text-center font-semibold text-gray-700">إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($runs as $run)
        <tr class="border-t hover:bg-gray-50">
          <td class="px-4 py-3 font-mono text-blue-700">{{ $run->run_code }}</td>
          <td class="px-4 py-3 font-semibold">{{ $run->product_name }}</td>
          <td class="px-4 py-3">{{ number_format($run->batch_size, 0) }} {{ $run->batch_unit }}</td>
          <td class="px-4 py-3 font-semibold text-green-700">{{ number_format($run->unit_cost, 2) }} {{ $run->currency }}</td>
          <td class="px-4 py-3 font-semibold text-purple-700">{{ $run->suggested_price ? number_format($run->suggested_price, 2) . ' ' . $run->currency : '-' }}</td>
          <td class="px-4 py-3">
            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $run->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
              {{ $run->status === 'draft' ? 'مسودة' : 'نهائي' }}
            </span>
          </td>
          <td class="px-4 py-3 text-gray-600">{{ $run->created_at->format('Y-m-d') }}</td>
          <td class="px-4 py-3 text-center space-x-2 space-x-reverse">
            <a href="{{ route('mfg.runs.show', $run->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">عرض</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="px-4 py-8 text-center text-gray-500">
            لا توجد تشغيلات محفوظة
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">
    {{ $runs->links() }}
  </div>
</div>
@endsection
