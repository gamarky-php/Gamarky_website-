@extends('layouts.app')
@section('title', __('front.manufacturing.runs.index.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="page-wrapper mx-auto px-3 md:px-6 py-6">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('front.manufacturing.runs.index.heading') }}</h1>
      <p class="text-gray-600">{{ __('front.manufacturing.runs.index.subtitle') }}</p>
    </div>
    <a href="{{ route('mfg.calculator') }}" class="rounded-lg px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
      {{ __('front.manufacturing.runs.index.new_run') }}
    </a>
  </div>

  <div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.run_code') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.product') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.batch_size') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.unit_cost') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.suggested_price') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.status') }}</th>
          <th class="px-4 py-3 text-right font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.date') }}</th>
          <th class="px-4 py-3 text-center font-semibold text-gray-700">{{ __('front.manufacturing.runs.index.actions') }}</th>
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
              {{ $run->status === 'draft' ? __('front.manufacturing.status.draft') : __('front.manufacturing.status.final') }}
            </span>
          </td>
          <td class="px-4 py-3 text-gray-600">{{ $run->created_at->format('Y-m-d') }}</td>
          <td class="px-4 py-3 text-center space-x-2 space-x-reverse">
            <a href="{{ route('mfg.runs.show', $run->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">{{ __('front.manufacturing.common.view') }}</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="px-4 py-8 text-center text-gray-500">
            {{ __('front.manufacturing.runs.index.empty') }}
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
