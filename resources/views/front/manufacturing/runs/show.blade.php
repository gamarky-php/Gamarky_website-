@extends('layouts.app')
@section('title', __('front.manufacturing.runs.show.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="page-wrapper mx-auto px-3 md:px-6 py-6">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $run->product_name }}</h1>
      <p class="text-gray-600">{{ $run->run_code }} | {{ $run->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div class="flex gap-2">
      <a href="{{ route('mfg.runs.index') }}" class="rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition">
        {{ __('front.manufacturing.common.back') }}
      </a>
      <button onclick="document.getElementById('quoteModal').classList.remove('hidden')" class="rounded-lg px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow transition">
        📊 {{ __('front.manufacturing.common.create_quote') }}
      </button>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-blue-900 mb-1">{{ __('front.manufacturing.runs.show.batch_size') }}</h3>
      <p class="text-2xl font-bold text-blue-700">{{ number_format($run->batch_size, 0) }} {{ $run->batch_unit }}</p>
    </div>
    <div class="bg-green-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-green-900 mb-1">{{ __('front.manufacturing.runs.show.batch_cost') }}</h3>
      <p class="text-2xl font-bold text-green-700">{{ number_format($run->total_batch_cost, 2) }} {{ $run->currency }}</p>
    </div>
    <div class="bg-purple-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-purple-900 mb-1">{{ __('front.manufacturing.runs.show.unit_cost') }}</h3>
      <p class="text-2xl font-bold text-purple-700">{{ number_format($run->unit_cost, 2) }} {{ $run->currency }}</p>
    </div>
    <div class="bg-rose-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-rose-900 mb-1">{{ __('front.manufacturing.runs.show.suggested_price') }}</h3>
      <p class="text-2xl font-bold text-rose-700">{{ $run->suggested_price ? number_format($run->suggested_price, 2) . ' ' . $run->currency : '-' }}</p>
    </div>
  </div>

  {{-- BOM --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.bom_title') }}</h3>
    </div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.material') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.qty_unit') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.price') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.total_cost') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.supplier') }}</th>
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
          <td colspan="3" class="px-4 py-3 text-right font-bold text-blue-900">{{ __('front.manufacturing.runs.show.total_materials') }}</td>
          <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($run->total_material_cost, 2) }}</td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Operations --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.operations_title') }}</h3>
    </div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.operation') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.setup_hours') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.cycle_minutes') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.labor_per_hour') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.machine_per_hour') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.total_cost_all') }}</th>
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
          <td colspan="5" class="px-4 py-3 text-right font-bold text-green-900">{{ __('front.manufacturing.runs.show.total_operations') }}</td>
          <td class="px-4 py-3 font-bold text-green-700">{{ number_format($run->total_operation_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Overheads --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.overhead_title') }}</h3>
    </div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.item') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.allocation_method') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.value_ratio') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.total_cost') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->overheads as $oh)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $oh->overhead_name }}</td>
          <td class="px-4 py-3">{{ $oh->allocation_method === 'fixed' ? __('front.manufacturing.runs.show.fixed') : __('front.manufacturing.runs.show.percentage') }}</td>
          <td class="px-4 py-3">{{ $oh->allocation_method === 'fixed' ? number_format($oh->amount, 2) : number_format($oh->rate_pct, 2) . '%' }}</td>
          <td class="px-4 py-3 font-semibold text-purple-700">{{ number_format($oh->total_cost, 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-purple-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-purple-900">{{ __('front.manufacturing.runs.show.total_overhead') }}</td>
          <td class="px-4 py-3 font-bold text-purple-700">{{ number_format($run->total_overhead_cost, 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Quotes --}}
  @if($run->quotes->count() > 0)
  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.related_quotes') }}</h3>
    </div>
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.quotes.index.quote_no') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.quotes.index.client') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.quotes.index.qty') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.quotes.index.margin') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.quotes.index.total') }}</th>
          <th class="px-4 py-3 text-center font-semibold text-gray-700">{{ __('front.manufacturing.quotes.index.actions') }}</th>
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
            <a href="{{ route('mfg.quotes.show', $quote->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">{{ __('front.manufacturing.common.view') }}</a>
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
<div id="quoteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ __('front.manufacturing.common.create_quote') }}</h3>
    <form method="POST" action="{{ route('mfg.quotes.generate', $run->id) }}">
      @csrf
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('front.manufacturing.common.client_name') }}</label>
        <input type="text" name="client_name" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('front.manufacturing.common.email') }}</label>
        <input type="email" name="client_email" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('front.manufacturing.common.margin_pct') }}</label>
        <input type="number" name="margin_pct" step="0.1" value="25" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('front.manufacturing.common.quantity') }}</label>
        <input type="number" name="qty" step="1" value="100" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="mb-3">
        <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('front.manufacturing.common.valid_until') }}</label>
        <input type="date" name="valid_until" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
      </div>
      <div class="flex gap-2">
        <button type="submit" class="flex-1 rounded-lg px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow transition">{{ __('front.manufacturing.common.create') }}</button>
        <button type="button" onclick="document.getElementById('quoteModal').classList.add('hidden')" class="flex-1 rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition">{{ __('front.manufacturing.common.cancel') }}</button>
      </div>
    </form>
  </div>
</div>
@endsection
