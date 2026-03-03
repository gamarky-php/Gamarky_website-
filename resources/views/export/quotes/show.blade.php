@extends('layouts.app')
@section('title', __('تفاصيل العرض'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">{{ __('عرض') }} #{{ $quote->id }}</h1>
    <a href="{{ route('export.quotes.index') }}" class="text-blue-600 hover:underline">{{ __('← العودة') }}</a>
  </div>

  @if(session('status'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('status') }}</div>
  @endif

  <div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <div class="grid grid-cols-2 gap-4 mb-6">
      <div>
        <p class="text-sm font-medium text-gray-500">{{ __('الشحنة') }}</p>
        <p class="mt-1 text-lg font-semibold text-gray-900">
          <a href="{{ route('export.shipments.show', $quote->shipment_id) }}" class="text-blue-600 hover:underline">#{{ $quote->shipment_id }}</a>
        </p>
      </div>
      <div>
        <p class="text-sm font-medium text-gray-500">{{ __('الحالة') }}</p>
        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $quote->status }}</p>
      </div>
    </div>

    <div class="border-t border-gray-200 pt-4">
      <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('البيانات') }}</h2>
      <pre class="bg-gray-50 p-4 rounded text-sm">{{ json_encode($quote->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
  </div>

  <div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('الإجراءات') }}</h2>
    <div class="flex gap-4">
      <a href="{{ route('export.quotes.pdf', $quote->id) }}" target="_blank" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
        {{ __('تحميل PDF') }}
      </a>
      <a href="{{ route('export.quotes.excel', $quote->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
        {{ __('تحميل Excel') }}
      </a>

      @if($quote->status === 'draft')
      <form action="{{ route('export.quotes.send', $quote->id) }}" method="POST">
        @csrf
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          {{ __('إرسال العرض') }}
        </button>
      </form>
      @endif
    </div>
  </div>
</div>
@endsection
