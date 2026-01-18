<!-- resources/views/components/empty-state.blade.php -->
@props([
    'type' => 'no_results',
    'icon' => null,
    'action' => null,
    'actionText' => null,
])

@php
    $emptyState = \App\Helpers\UxHelper::emptyState($type);
    
    $defaultIcons = [
        'no_results' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'no_shipments' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4',
        'no_bookings' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'no_documents' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'no_notifications' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
    ];
    
    $iconPath = $icon ?? ($defaultIcons[$type] ?? $defaultIcons['no_results']);
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 text-center']) }}>
    <!-- Icon -->
    <div class="mb-4 rounded-full bg-gray-100 p-4">
        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
        </svg>
    </div>
    
    <!-- Message -->
    <h3 class="mb-2 text-lg font-semibold text-gray-900">
        {{ $emptyState['message'] }}
    </h3>
    
    <!-- Hint -->
    <p class="mb-6 max-w-md text-sm text-gray-500">
        {{ $emptyState['hint'] }}
    </p>
    
    <!-- Action Button -->
    @if($action && $actionText)
        <a href="{{ $action }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
            {{ $actionText }}
        </a>
    @endif
    
    {{ $slot }}
</div>
