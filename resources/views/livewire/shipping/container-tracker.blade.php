{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-8">
    
    {{-- SEARCH FORM --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form wire:submit.prevent="trackShipment" class="flex gap-4">
            <div class="flex-1">
                <input type="text" wire:model="tracking_number" placeholder="{{ __('shipping.container_tracker.tracking_placeholder') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right text-lg">
                @error('tracking_number') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit" wire:loading.attr="disabled"
                class="px-12 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg font-bold hover:shadow-xl transition-all disabled:opacity-50">
                <i class="fas fa-search ml-2"></i>
                <span wire:loading.remove>{{ __('shipping.actions.track') }}</span>
                <span wire:loading>{{ __('shipping.actions.searching') }}</span>
            </button>
        </form>
    </div>

    {{-- TRACKING RESULTS --}}
    @if($searchPerformed && $trackingData)
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            {{-- Status Card --}}
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold">{{ __('shipping.container_tracker.current_status') }}</h3>
                    <i class="fas fa-ship text-3xl opacity-70"></i>
                </div>
                <p class="text-2xl font-bold mb-2">{{ $trackingData['status'] }}</p>
                <p class="opacity-90">{{ $trackingData['current_location'] }}</p>
            </div>

            {{-- Progress Card --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('shipping.container_tracker.progress') }}</h3>
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-teal-600 bg-teal-200">
                                {{ $trackingData['progress_percentage'] }}%
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-gray-200">
                        <div style="width:{{ $trackingData['progress_percentage'] }}%" 
                            class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-teal-500 to-teal-600 transition-all duration-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ETA Card --}}
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">{{ __('shipping.container_tracker.estimated_arrival') }}</h3>
                    <i class="fas fa-clock text-2xl text-teal-500"></i>
                </div>
                <p class="text-2xl font-bold text-teal-600">{{ $trackingData['estimated_arrival'] }}</p>
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-[#0F2E5D] mb-6">{{ __('shipping.container_tracker.journey_log') }}</h3>
            
            <div class="space-y-6">
                @foreach($trackingData['events'] as $index => $event)
                    <div class="flex items-start gap-4 relative">
                        {{-- Timeline Line --}}
                        @if($index < count($trackingData['events']) - 1)
                            <div class="absolute right-6 top-12 h-full w-0.5 bg-gray-300"></div>
                        @endif
                        
                        {{-- Icon --}}
                        <div class="w-12 h-12 rounded-full flex items-center justify-center z-10
                            {{ $event['date'] ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            <i class="fas {{ $event['icon'] }}"></i>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 bg-gray-50 rounded-lg p-4 {{ $event['date'] ? '' : 'opacity-60' }}">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-800">{{ $event['status'] }}</h4>
                                @if($event['date'])
                                    <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full">{{ $event['date'] }}</span>
                                @else
                                    <span class="text-sm text-gray-500 italic">{{ __('shipping.container_tracker.pending') }}</span>
                                @endif
                            </div>
                            <p class="text-gray-600">{{ $event['location'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
