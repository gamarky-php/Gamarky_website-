@extends('layouts.app')
@section('title', __('front.export.quotes.show.title', ['quote' => $quote->quote_no]))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ showSendModal: false }">
  {{-- رأس الصفحة --}}
  <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('front.export.quotes.show.heading') }}</h1>
      <p class="text-gray-600">{{ __('front.export.quotes.show.quote_no') }}: <span class="font-semibold text-blue-600">{{ $quote->quote_no }}</span></p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('export.quotes.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition">
        ← {{ __('front.export.quotes.show.back_to_list') }}
      </a>
    </div>
  </div>

  @if(session('status'))
    <div class="mb-6 p-4 bg-green-100 border-r-4 border-green-500 text-green-800 rounded-lg">
      {{ session('status') }}
    </div>
  @endif

  {{-- معلومات العرض الأساسية --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white shadow-md rounded-lg p-4">
      <p class="text-sm text-gray-500 mb-1">{{ __('front.export.quotes.show.incoterm') }}</p>
      <p class="text-2xl font-bold text-blue-600">{{ $quote->incoterm_final }}</p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-4">
      <p class="text-sm text-gray-500 mb-1">{{ __('front.export.quotes.show.total_cost') }}</p>
      <p class="text-2xl font-bold text-green-600">{{ number_format($quote->total_cost, 2) }} {{ $quote->currency }}</p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-4">
      <p class="text-sm text-gray-500 mb-1">{{ __('front.export.quotes.show.margin') }}</p>
      <p class="text-2xl font-bold text-purple-600">{{ $quote->margin_pct ?? 0 }}%</p>
    </div>
    <div class="bg-white shadow-md rounded-lg p-4">
      <p class="text-sm text-gray-500 mb-1">{{ __('front.export.quotes.show.sell_price') }}</p>
      <p class="text-2xl font-bold text-red-600">{{ number_format($quote->sell_price, 2) }} {{ $quote->currency }}</p>
    </div>
  </div>

  {{-- تفاصيل الشحنة --}}
  <div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">{{ __('front.export.quotes.show.shipment_details') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.incoterm') }}</p>
        <p class="font-semibold text-gray-900">{{ $quote->shipment->incoterm }}</p>
      </div>
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.shipping_method') }}</p>
        <p class="font-semibold text-gray-900">
          @if($quote->shipment->method === 'sea') 🚢 {{ __('front.export.quotes.show.sea') }}
          @elseif($quote->shipment->method === 'air') ✈️ {{ __('front.export.quotes.show.air') }}
          @else 🚛 {{ __('front.export.quotes.show.land') }}
          @endif
        </p>
      </div>
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.currency') }}</p>
        <p class="font-semibold text-gray-900">{{ $quote->shipment->currency }}</p>
      </div>
      @if($quote->shipment->origin_country)
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.origin_country') }}</p>
        <p class="font-semibold text-gray-900">{{ $quote->shipment->origin_country }}</p>
      </div>
      @endif
      @if($quote->shipment->pol)
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.port_loading') }}</p>
        <p class="font-semibold text-gray-900">{{ $quote->shipment->pol }}</p>
      </div>
      @endif
      @if($quote->shipment->pod)
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.port_arrival') }}</p>
        <p class="font-semibold text-gray-900">{{ $quote->shipment->pod }}</p>
      </div>
      @endif
      @if($quote->shipment->etd)
      <div>
        <p class="text-sm text-gray-500">{{ __('front.export.quotes.show.etd') }}</p>
        <p class="font-semibold text-gray-900">{{ $quote->shipment->etd->format('Y-m-d') }}</p>
      </div>
      @endif
    </div>
  </div>

  {{-- بنود التكلفة --}}
  <div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">{{ __('front.export.quotes.show.cost_items') }}</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('front.export.table.statement') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('front.export.quotes.show.category') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('front.export.quotes.show.item') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('front.export.quotes.show.amount') }}</th>
            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('front.export.quotes.show.currency') }}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @php
            $costsGrouped = $quote->shipment->costs->groupBy('col_index')->sortKeys();
          @endphp
          @foreach($costsGrouped as $colIndex => $costs)
            <tr class="bg-blue-50">
              <td colspan="6" class="px-4 py-2 font-bold text-blue-900">{{ __('front.export.quotes.show.item') }} {{ $colIndex }}</td>
            </tr>
            @foreach($costs as $index => $cost)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-3 text-sm text-gray-900">{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ $cost->line_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">
                <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $cost->category }}</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-900">{{ $cost->col_index }}</td>
              <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ number_format($cost->amount, 2) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ $cost->currency }}</td>
            </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- الإجراءات --}}
  <div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">{{ __('front.export.quotes.show.actions') }}</h2>
    
    <div class="flex flex-wrap gap-3">
      {{-- تحميل PDF --}}
      <a href="{{ route('export.quotes.pdf', $quote->id) }}" target="_blank"
         class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path>
          <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path>
        </svg>
        {{ __('front.export.quotes.show.download_pdf') }}
      </a>

      {{-- تحميل Excel --}}
      <a href="{{ route('export.quotes.excel', $quote->id) }}"
         class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path>
        </svg>
        {{ __('front.export.quotes.show.download_excel') }}
      </a>

      {{-- إرسال العرض --}}
      @if($quote->status === 'draft')
      <button @click="showSendModal = true"
              class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition flex items-center gap-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
          <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
        </svg>
        {{ __('front.export.quotes.show.send_quote') }}
      </button>
      @endif
    </div>

    {{-- مودال الإرسال --}}
    @if($quote->status === 'draft')
    <div x-show="showSendModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click.self="showSendModal = false">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
          <h3 class="text-xl font-bold mb-4">{{ __('front.export.quotes.show.send_quote') }}</h3>
          <form action="{{ route('export.quotes.send', $quote->id) }}" method="POST">
            @csrf
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('front.export.quotes.show.email') }}</label>
              <input type="email" name="email" required
                     class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('front.export.quotes.show.message_optional') }}</label>
              <textarea name="message" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="flex gap-2">
              <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                {{ __('front.export.quotes.show.send') }}
              </button>
              <button type="button" @click="showSendModal = false"
                      class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">
                {{ __('front.export.quotes.show.cancel') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif
  </div>

    {{-- الحالة --}}
    <div class="mt-6 pt-6 border-t border-gray-200">
      <p class="text-sm text-gray-600">
        {{ __('front.export.quotes.show.status') }}: 
        <span class="px-3 py-1 rounded-full text-sm font-semibold
          @if($quote->status === 'draft') bg-yellow-100 text-yellow-800
          @elseif($quote->status === 'sent') bg-blue-100 text-blue-800
          @elseif($quote->status === 'accepted') bg-green-100 text-green-800
          @else bg-red-100 text-red-800
          @endif">
          @if($quote->status === 'draft') {{ __('front.export.quotes.show.status_draft') }}
          @elseif($quote->status === 'sent') {{ __('front.export.quotes.show.status_sent') }}
          @elseif($quote->status === 'accepted') {{ __('front.export.quotes.show.status_accepted') }}
          @else {{ __('front.export.quotes.show.status_rejected') }}
          @endif
        </span>
      </p>
      <p class="text-xs text-gray-500 mt-2">
        {{ __('front.export.quotes.show.created_at') }}: {{ $quote->created_at->format('Y-m-d H:i') }}
      </p>
    </div>
  </div>
</div>

@push('styles')
<style>
  [x-cloak] { display: none !important; }
</style>
@endpush
@endsection
