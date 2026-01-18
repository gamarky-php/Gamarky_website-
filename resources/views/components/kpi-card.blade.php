<!-- resources/views/components/kpi-card.blade.php -->
@props([
    'title' => '',
    'value' => 0,
    'change' => null,
    'trend' => 'neutral', // up, down, neutral
    'icon' => null,
    'color' => 'blue', // blue, green, red, yellow, purple
])

@php
    $trendColors = [
        'up' => 'text-green-600',
        'down' => 'text-red-600',
        'neutral' => 'text-gray-600',
    ];
    
    $bgColors = [
        'blue' => 'bg-blue-50 dark:bg-blue-900/20',
        'green' => 'bg-green-50 dark:bg-green-900/20',
        'red' => 'bg-red-50 dark:bg-red-900/20',
        'yellow' => 'bg-yellow-50 dark:bg-yellow-900/20',
        'purple' => 'bg-purple-50 dark:bg-purple-900/20',
    ];
    
    $iconColors = [
        'blue' => 'text-blue-600 dark:text-blue-400',
        'green' => 'text-green-600 dark:text-green-400',
        'red' => 'text-red-600 dark:text-red-400',
        'yellow' => 'text-yellow-600 dark:text-yellow-400',
        'purple' => 'text-purple-600 dark:text-purple-400',
    ];
    
    $trendColor = $trendColors[$trend] ?? $trendColors['neutral'];
    $bgColor = $bgColors[$color] ?? $bgColors['blue'];
    $iconColor = $iconColors[$color] ?? $iconColors['blue'];
@endphp

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800']) }}>
    <div class="p-5">
        <div class="flex items-center">
            @if($icon)
                <div class="flex-shrink-0">
                    <div class="flex h-12 w-12 items-center justify-center rounded-md {{ $bgColor }}">
                        <svg class="h-6 w-6 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                        </svg>
                    </div>
                </div>
            @endif
            
            <div class="{{ $icon ? 'mr-5' : '' }} w-0 flex-1">
                <dl>
                    <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ $title }}
                    </dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $value }}
                        </div>
                        
                        @if($change !== null)
                            <div class="mr-2 flex items-baseline text-sm font-semibold {{ $trendColor }}">
                                @if($trend === 'up')
                                    <svg class="ml-1 h-4 w-4 flex-shrink-0 self-center" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @elseif($trend === 'down')
                                    <svg class="ml-1 h-4 w-4 flex-shrink-0 self-center" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                                <span class="sr-only">{{ $trend === 'up' ? 'Increased' : 'Decreased' }} by</span>
                                {{ $change }}
                            </div>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    
    @if($slot->isNotEmpty())
        <div class="bg-gray-50 px-5 py-3 dark:bg-gray-700/50">
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
    @endif
</div>
