@extends('layouts.app')
@section('title', 'العروض')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">العروض</h1>
  </div>

  <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الشحنة</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        @forelse($quotes as $quote)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $quote->id }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $quote->shipment_id }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $quote->status }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $quote->created_at->format('Y-m-d') }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2 space-x-reverse">
            <a href="{{ route('export.quotes.show', $quote->id) }}" class="text-blue-600 hover:underline">عرض</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-6 py-4 text-center text-gray-500">لا توجد عروض</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $quotes->links() }}
  </div>
</div>
@endsection
