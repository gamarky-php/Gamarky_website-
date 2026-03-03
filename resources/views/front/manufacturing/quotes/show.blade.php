@extends('layouts.app')
@section('title', __('front.manufacturing.quotes.show.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="page-wrapper mx-auto px-3 md:px-6 py-6">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('front.manufacturing.quotes.show.quote_title') }}: {{ $quote->quote_number }}</h1>
      <p class="text-gray-600">{{ $quote->costRun->product_name }} | {{ $quote->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('mfg.quotes.index') }}" class="rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition">
        {{ __('front.manufacturing.common.back') }}
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
      <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('front.manufacturing.quotes.show.client_data') }}</h3>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.common.client_name') }}:</span>
          <span class="font-semibold">{{ $quote->client_name ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.common.email') }}:</span>
          <span class="font-semibold">{{ $quote->client_email ?? '-' }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.common.phone') }}:</span>
          <span class="font-semibold">{{ $quote->client_phone ?? '-' }}</span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6">
      <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('front.manufacturing.quotes.show.quote_details') }}</h3>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.runs.show.unit_cost') }}:</span>
          <span class="font-semibold">{{ number_format($quote->unit_cost, 2) }} {{ $quote->currency }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.common.margin_pct') }}:</span>
          <span class="font-semibold">{{ number_format($quote->margin_pct, 2) }}%</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.quotes.show.unit_price') }}:</span>
          <span class="font-semibold text-purple-700">{{ number_format($quote->unit_price, 2) }} {{ $quote->currency }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.common.quantity') }}:</span>
          <span class="font-semibold">{{ number_format($quote->qty, 0) }}</span>
        </div>
        <div class="flex justify-between border-t pt-2">
          <span class="text-gray-900 font-bold">{{ __('front.manufacturing.quotes.index.total') }}:</span>
          <span class="font-bold text-green-700 text-xl">{{ number_format($quote->total_amount, 2) }} {{ $quote->currency }}</span>
        </div>
        <div class="flex justify-between">
          <span class="text-gray-600">{{ __('front.manufacturing.common.valid_until') }}:</span>
          <span class="font-semibold">{{ $quote->valid_until ? $quote->valid_until->format('Y-m-d') : '-' }}</span>
        </div>
      </div>
    </div>
  </div>

  @if($quote->notes)
  <div class="bg-yellow-50 rounded-xl shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('front.manufacturing.common.notes') }}</h3>
    <p class="text-gray-700">{{ $quote->notes }}</p>
  </div>
  @endif

  {{-- تفاصيل التكلفة --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.quotes.show.cost_details_bom') }}</h3>
    </div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.table_bom.material') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.qty_unit') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.price') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.table_bom.cost') }}</th>
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
          <td colspan="3" class="px-4 py-3 text-right font-bold text-blue-900">{{ __('front.manufacturing.runs.show.total_materials') }}</td>
          <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($quote->costRun->total_material_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.operations_title') }}</h3>
    </div>
    {{-- dir inherited from parent --}}
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.table_ops.operation') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.table_ops.setup_hr') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.quotes.show.cycle_min') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.table_ops.cost') }}</th>
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
          <td colspan="3" class="px-4 py-3 text-right font-bold text-green-900">{{ __('front.manufacturing.runs.show.total_operations') }}</td>
          <td class="px-4 py-3 font-bold text-green-700">{{ number_format($quote->costRun->total_operation_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.overhead_title') }}</h3>
    </div>
    {{-- dir inherited from parent --}}
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.item') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.allocation_method') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.table_oh.cost') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quote->costRun->overheads as $oh)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $oh->overhead_name }}</td>
          <td class="px-4 py-3">{{ $oh->allocation_method === 'fixed' ? __('front.manufacturing.runs.show.fixed') : __('front.manufacturing.runs.show.percentage') }}</td>
          <td class="px-4 py-3 font-semibold text-purple-700">{{ number_format($oh->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-purple-50">
          <td colspan="2" class="px-4 py-3 text-right font-bold text-purple-900">{{ __('front.manufacturing.runs.show.total_overhead') }}</td>
          <td class="px-4 py-3 font-bold text-purple-700">{{ number_format($quote->costRun->total_overhead_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
