@extends('layouts.app')
@section('title', __('تفاصيل الشحنة'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">{{ __('تفاصيل الشحنة') }} #{{ $shipment->id }}</h1>
    <a href="{{ route('export.shipments.index') }}" class="text-blue-600 hover:underline">{{ __('← العودة') }}</a>
  </div>

  @if(session('status'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('status') }}</div>
  @endif

  <div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <div>
        <p class="text-sm font-medium text-gray-500">{{ __('المرجع') }}</p>
        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $shipment->reference ?? '-' }}</p>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">{{ __('الإنكوترمز') }}</p>
        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $shipment->incoterm }}</p>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">{{ __('العملة') }}</p>
        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $shipment->currency }}</p>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">{{ __('سعر الصرف') }}</p>
        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $shipment->exchange_rate }}</p>
      </div>
    </div>
  </div>

  <div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('البنود') }}</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('الوصف') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('الكمية') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('سعر الوحدة') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('الإجمالي') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @foreach($shipment->items as $item)
          <tr>
            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['description'] ?? '-' }}</td>
            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['qty'] ?? 0 }}</td>
            <td class="px-4 py-3 text-sm text-gray-900">{{ $item['unit_price'] ?? 0 }}</td>
            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ ($item['qty'] ?? 0) * ($item['unit_price'] ?? 0) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('العروض') }}</h2>
    @if($shipment->quotes->count())
      <ul class="space-y-2">
        @foreach($shipment->quotes as $quote)
        <li class="flex justify-between items-center p-3 bg-gray-50 rounded">
          <span>{{ __('عرض') }} #{{ $quote->id }} - {{ $quote->status }}</span>
          <a href="{{ route('export.quotes.show', $quote->id) }}" class="text-blue-600 hover:underline">{{ __('عرض') }}</a>
        </li>
        @endforeach
      </ul>
    @else
      <p class="text-gray-500">{{ __('لا توجد عروض') }}</p>
    @endif

    <form action="{{ route('export.quotes.generate', $shipment->id) }}" method="POST" class="mt-4">
      @csrf
      <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">{{ __('إنشاء عرض جديد') }}</button>
    </form>
  </div>
</div>
@endsection
