@extends('layouts.app')
@section('title', __('front.manufacturing.run_show.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="page-wrapper mx-auto px-3 md:px-6 py-6">
  <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $run->product->name }}</h1>
      <p class="text-gray-600">SKU: {{ $run->product->sku }} | {{ __('front.manufacturing.runs.show.batch_size') }}: {{ number_format($run->batch_size) }} {{ __('front.manufacturing.common.unit') }}</p>
      <p class="text-sm text-gray-500">{{ $run->created_at->format('Y-m-d H:i') }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('mfg.calculator') }}" class="rounded-lg px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold shadow transition text-sm">
        🔙 {{ __('front.manufacturing.common.back') }}
      </a>
      <a href="{{ route('mfg.runs.pdf', $run->id) }}" target="_blank" class="rounded-lg px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold shadow transition text-sm">
        📄 PDF
      </a>
      <a href="{{ route('mfg.runs.excel', $run->id) }}" class="rounded-lg px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition text-sm">
        📊 Excel
      </a>
      <button onclick="document.getElementById('quoteModal').classList.remove('hidden')" class="rounded-lg px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold shadow transition text-sm">
        {{ __('front.manufacturing.common.create_quote') }}
      </button>
    </div>
  </div>

  {{-- ملخص التكاليف --}}
  <div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-emerald-700 mb-1">{{ __('front.manufacturing.sidebar.material_cost') }}</h3>
      <p class="text-2xl font-bold text-emerald-900">{{ number_format($run->bomItems->sum('total_cost'), 2) }}</p>
      <p class="text-xs text-emerald-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-blue-700 mb-1">{{ __('front.manufacturing.sidebar.operations_cost') }}</h3>
      <p class="text-2xl font-bold text-blue-900">{{ number_format($run->ops->sum('total_cost'), 2) }}</p>
      <p class="text-xs text-blue-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-amber-700 mb-1">{{ __('front.manufacturing.sidebar.overhead_cost') }}</h3>
      @php
        $overheadTotal = $run->overheads->sum(function($pool) use ($run) {
          return $pool->calculateCost($run);
        });
      @endphp
      <p class="text-2xl font-bold text-amber-900">{{ number_format($overheadTotal, 2) }}</p>
      <p class="text-xs text-amber-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-purple-700 mb-1">{{ __('front.manufacturing.sidebar.batch_cost') }}</h3>
      <p class="text-2xl font-bold text-purple-900">{{ number_format($run->total_cost, 2) }}</p>
      <p class="text-xs text-purple-600 mt-1">{{ $run->currency }}</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-xs font-semibold text-indigo-700 mb-1">{{ __('front.manufacturing.sidebar.unit_cost') }}</h3>
      <p class="text-2xl font-bold text-indigo-900">{{ number_format($run->unit_cost, 4) }}</p>
      <p class="text-xs text-indigo-600 mt-1">{{ $run->currency }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
    <div class="bg-gradient-to-br from-rose-50 to-rose-100 rounded-xl p-4 shadow-sm">
      <h3 class="text-sm font-semibold text-rose-700 mb-1">{{ __('front.manufacturing.sidebar.suggested_price') }}</h3>
      <p class="text-3xl font-bold text-rose-700">{{ $run->target_price ? number_format($run->target_price, 2) . ' ' . $run->currency : '-' }}</p>
      <p class="text-xs text-rose-600 mt-1">{{ __('front.manufacturing.common.margin_pct') }} {{ number_format($run->margin_pct, 2) }}%</p>
    </div>
    <div class="bg-slate-50 rounded-lg p-4">
      <h3 class="text-sm font-semibold text-slate-900 mb-1">{{ __('front.manufacturing.common.additional_info') }}</h3>
      <div class="text-sm space-y-1">
        <div><span class="text-slate-600">{{ __('front.manufacturing.sidebar.scrap_pct') }}:</span> <span class="font-semibold">{{ number_format($run->scrap_pct, 2) }}%</span></div>
        <div><span class="text-slate-600">{{ __('front.manufacturing.sidebar.fx_rate') }}:</span> <span class="font-semibold">{{ number_format($run->fx_rate, 4) }}</span></div>
        <div><span class="text-slate-600">{{ __('front.manufacturing.runs.index.status') }}:</span> <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full {{ $run->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">{{ $run->status === 'draft' ? __('front.manufacturing.status.draft') : __('front.manufacturing.status.approved') }}</span></div>
      </div>
    </div>
  </div>

  {{-- BOM --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.bom_title') }}</h3>
    </div>
    {{-- dir inherited from parent --}}
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.material') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.qty_per_batch') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.unit_price') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.scrap_pct') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.total_cost') }}</th>
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
          <td colspan="4" class="px-4 py-3 text-right font-bold text-emerald-900">{{ __('front.manufacturing.runs.show.total') }}</td>
          <td class="px-4 py-3 font-bold text-emerald-700">{{ number_format($run->bomItems->sum('total_cost'), 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Operations --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.operations_title') }}</h3>
    </div>
    {{-- dir inherited from parent --}}
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.sequence') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.operation') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.setup_hours') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.run_hours') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.labor_per_hour') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.machine_per_hour') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.total_cost') }}</th>
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
          <td colspan="6" class="px-4 py-3 text-right font-bold text-blue-900">{{ __('front.manufacturing.runs.show.total') }}</td>
          <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($run->ops->sum('total_cost'), 2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Overheads --}}
  <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-4 py-3">
      <h3 class="text-white font-bold text-lg">{{ __('front.manufacturing.runs.show.overhead_title') }}</h3>
    </div>
    {{-- dir inherited from parent --}}
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.item') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.allocation_basis') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.rate') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.show.allocated_cost') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($run->overheads as $pool)
        <tr class="border-t">
          <td class="px-4 py-3 font-semibold">{{ $pool->name }}</td>
          <td class="px-4 py-3">
            @if($pool->basis === 'machine_hour') {{ __('front.manufacturing.runs.show.machine_hours') }}
            @elseif($pool->basis === 'labor_hour') {{ __('front.manufacturing.runs.show.labor_hours') }}
            @else {{ __('front.manufacturing.runs.show.materials_ratio') }}
            @endif
          </td>
          <td class="px-4 py-3">{{ number_format($pool->rate, 4) }}</td>
          <td class="px-4 py-3 font-semibold text-amber-700">{{ number_format($pool->calculateCost($run), 2) }}</td>
        </tr>
        @endforeach
        <tr class="border-t bg-amber-50">
          <td colspan="3" class="px-4 py-3 text-right font-bold text-amber-900">{{ __('front.manufacturing.runs.show.total') }}</td>
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
<div id="quoteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
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
          <input type="number" name="margin_pct" step="0.1" value="{{ $run->margin_pct }}" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
        </div>
        <div class="mb-3">
          <label class="block text-sm font-semibold text-gray-700 mb-1">{{ __('front.manufacturing.common.quantity') }}</label>
          <input type="number" name="qty" step="1" value="{{ $run->batch_size }}" required class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
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
</div>
@push('scripts')
<script>console.info('Run show view loaded');</script>
@endpush
@endsection
