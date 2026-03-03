@extends('layouts.app')
@section('title', __('الشحنات'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900">{{ __('الشحنات') }}</h1>
    <a href="{{ route('export.calculator') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">{{ __('+ شحنة جديدة') }}</a>
  </div>

  @if(session('status'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">{{ session('status') }}</div>
  @endif

  <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('المرجع') }}</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('الإنكوترمز') }}</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('العملة') }}</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('الحالة') }}</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('التاريخ') }}</th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('إجراءات') }}</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        @forelse($shipments as $ship)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ship->id }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ship->reference ?? '-' }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ship->incoterm }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ship->currency }}</td>
          <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
              @if($ship->status === 'draft') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
              {{ $ship->status }}
            </span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ship->created_at->format('Y-m-d') }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">
            <a href="{{ route('export.shipments.show', $ship->id) }}" class="text-blue-600 hover:text-blue-900">{{ __('عرض') }}</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="px-6 py-4 text-center text-gray-500">{{ __('لا توجد شحنات') }}</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $shipments->links() }}
  </div>
</div>
@endsection
