@extends('layouts.dashboard')

@section('title', __('ui.dashboard.container_tracking.page_title'))

@section('content')
@php
    // Demo tracking state
    $currentStatus = __('ui.dashboard.container_tracking.current_status_value');
    $lastLocation = __('ui.dashboard.container_tracking.location_singapore_port');
    $eta = '2026-01-20 14:30';
    
    // Journey timeline
    $timeline = [
        ['status' => 'completed', 'title' => __('ui.dashboard.container_tracking.tl_1_title'), 'location' => __('ui.dashboard.container_tracking.location_system'), 'date' => '2026-01-05 10:15', 'description' => __('ui.dashboard.container_tracking.tl_1_desc')],
        ['status' => 'completed', 'title' => __('ui.dashboard.container_tracking.tl_2_title'), 'location' => __('ui.dashboard.container_tracking.location_shanghai_port'), 'date' => '2026-01-08 08:30', 'description' => __('ui.dashboard.container_tracking.tl_2_desc')],
        ['status' => 'completed', 'title' => __('ui.dashboard.container_tracking.tl_3_title'), 'location' => __('ui.dashboard.container_tracking.location_shanghai_port'), 'date' => '2026-01-09 14:00', 'description' => __('ui.dashboard.container_tracking.tl_3_desc')],
        ['status' => 'active', 'title' => __('ui.dashboard.container_tracking.tl_4_title'), 'location' => __('ui.dashboard.container_tracking.location_singapore_port'), 'date' => '2026-01-14 09:00', 'description' => __('ui.dashboard.container_tracking.tl_4_desc')],
        ['status' => 'pending', 'title' => __('ui.dashboard.container_tracking.tl_5_title'), 'location' => __('ui.dashboard.container_tracking.location_jeddah_port'), 'date' => __('ui.dashboard.container_tracking.eta_expected'), 'description' => __('ui.dashboard.container_tracking.tl_5_desc')],
        ['status' => 'pending', 'title' => __('ui.dashboard.container_tracking.tl_6_title'), 'location' => __('ui.dashboard.container_tracking.location_saudi_customs'), 'date' => __('ui.dashboard.container_tracking.soon'), 'description' => __('ui.dashboard.container_tracking.tl_6_desc')],
        ['status' => 'pending', 'title' => __('ui.dashboard.container_tracking.tl_7_title'), 'location' => __('ui.dashboard.container_tracking.location_client_warehouse'), 'date' => __('ui.dashboard.container_tracking.soon'), 'description' => __('ui.dashboard.container_tracking.tl_7_desc')],
    ];
    
    // Latest tracking events
    $trackingEvents = [
        ['datetime' => '2026-01-14 09:00', 'location' => __('ui.dashboard.container_tracking.location_singapore_port'), 'event' => __('ui.dashboard.container_tracking.event_transit_stop'), 'note' => __('ui.dashboard.container_tracking.note_refuel')],
        ['datetime' => '2026-01-13 16:45', 'location' => __('ui.dashboard.container_tracking.location_south_china_sea'), 'event' => __('ui.dashboard.container_tracking.event_location_update'), 'note' => __('ui.dashboard.container_tracking.note_on_track')],
        ['datetime' => '2026-01-12 11:20', 'location' => __('ui.dashboard.container_tracking.location_malacca_strait'), 'event' => __('ui.dashboard.container_tracking.event_location_update'), 'note' => __('ui.dashboard.container_tracking.note_strait_crossing')],
        ['datetime' => '2026-01-11 08:30', 'location' => __('ui.dashboard.container_tracking.location_south_china_sea'), 'event' => __('ui.dashboard.container_tracking.event_location_update'), 'note' => __('ui.dashboard.container_tracking.note_good_weather')],
        ['datetime' => '2026-01-10 14:15', 'location' => __('ui.dashboard.container_tracking.location_hong_kong_port'), 'event' => __('ui.dashboard.container_tracking.event_transit_stop'), 'note' => __('ui.dashboard.container_tracking.note_short_stop')],
        ['datetime' => '2026-01-09 14:00', 'location' => __('ui.dashboard.container_tracking.location_shanghai_port'), 'event' => __('ui.dashboard.container_tracking.event_port_departure'), 'note' => __('ui.dashboard.container_tracking.note_departed_to_jeddah')],
        ['datetime' => '2026-01-08 08:30', 'location' => __('ui.dashboard.container_tracking.location_shanghai_port'), 'event' => __('ui.dashboard.container_tracking.event_container_loaded'), 'note' => __('ui.dashboard.container_tracking.note_loaded_msc')],
        ['datetime' => '2026-01-05 10:15', 'location' => __('ui.dashboard.container_tracking.location_system'), 'event' => __('ui.dashboard.container_tracking.event_booking_created'), 'note' => __('ui.dashboard.container_tracking.note_booking_created')],
    ];
@endphp

{{-- dir inherited from layout --}}
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                {{ __('ui.dashboard.container_tracking.page_title') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('ui.dashboard.container_tracking.page_subtitle') }}</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <label for="tracking-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('ui.dashboard.container_tracking.search_by') }}
                </label>
                <input 
                    type="text" 
                    id="tracking-input"
                    placeholder="{{ __('ui.dashboard.container_tracking.search_placeholder') }}"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    value="MSKU1234567"
                >
            </div>
            <div class="flex gap-3 lg:items-end">
                <button 
                    type="button"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    {{ __('ui.dashboard.container_tracking.search') }}
                </button>
                <button 
                    type="button"
                    class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors flex items-center gap-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ __('ui.dashboard.container_tracking.clear') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Current Status -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ __('ui.dashboard.container_tracking.current_status') }}</h3>
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold mb-1">{{ $currentStatus }}</p>
            <p class="text-blue-100 text-sm">{{ __('ui.dashboard.container_tracking.ship_active') }}</p>
        </div>

        <!-- Last Known Location -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ __('ui.dashboard.container_tracking.last_known_location') }}</h3>
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold mb-1">{{ $lastLocation }}</p>
            <p class="text-emerald-100 text-sm">{{ __('ui.dashboard.container_tracking.updated_today_0900') }}</p>
        </div>

        <!-- ETA -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ __('ui.dashboard.container_tracking.eta_title') }}</h3>
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold mb-1">{{ $eta }}</p>
            <p class="text-amber-100 text-sm">{{ __('ui.dashboard.container_tracking.remaining_days') }}</p>
        </div>
    </div>

    <!-- Timeline & Table Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Timeline -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    {{ __('ui.dashboard.container_tracking.journey_stages') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('ui.dashboard.container_tracking.journey_subtitle') }}</p>
            </div>

            <div class="space-y-4">
                @foreach($timeline as $index => $step)
                    <div class="flex gap-4">
                        <!-- Status Indicator -->
                        <div class="flex flex-col items-center">
                            @if($step['status'] === 'completed')
                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            @elseif($step['status'] === 'active')
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center animate-pulse">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            @if($index < count($timeline) - 1)
                                <div class="w-0.5 h-16 {{ $step['status'] === 'completed' ? 'bg-green-300 dark:bg-green-700' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 pb-8">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                {{ $step['title'] }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $step['location'] }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mb-2">
                                <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $step['date'] }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $step['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Tracking Events Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('ui.dashboard.container_tracking.latest_events') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('ui.dashboard.container_tracking.events_subtitle') }}</p>
            </div>

            <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_tracking.date_time') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_tracking.location') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_tracking.event') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('ui.dashboard.container_tracking.note') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($trackingEvents as $event)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center text-sm text-gray-900 dark:text-white">
                                        <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <div class="font-medium">{{ explode(' ', $event['datetime'])[0] }}</div>
                                            <div class="text-xs text-gray-500">{{ explode(' ', $event['datetime'])[1] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-emerald-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-gray-900 dark:text-white">{{ $event['location'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        {{ $event['event'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $event['note'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
