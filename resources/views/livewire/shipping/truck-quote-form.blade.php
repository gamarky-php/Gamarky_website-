{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-8">
    
    {{-- SEARCH PANEL --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form wire:submit.prevent="searchQuotes" class="space-y-6">
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('shipping.truck_quote_form.origin_city') }} *</label>
                    <input type="text" wire:model="origin_city" placeholder="{{ __('shipping.truck_quote_form.origin_city_placeholder') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('origin_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('shipping.truck_quote_form.destination_city') }} *</label>
                    <input type="text" wire:model="destination_city" placeholder="{{ __('shipping.truck_quote_form.destination_city_placeholder') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('destination_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('shipping.truck_quote_form.pickup_date') }} *</label>
                    <input type="date" wire:model="pickup_date"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('pickup_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('shipping.truck_quote_form.weight_kg') }} *</label>
                    <input type="number" wire:model="weight_kg" placeholder="5000"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('weight_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('shipping.truck_quote_form.truck_type') }}</label>
                    <select wire:model="truck_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        <option value="flatbed">{{ __('shipping.truck_quote_form.types.flatbed') }}</option>
                        <option value="box">{{ __('shipping.truck_quote_form.types.box') }}</option>
                        <option value="refrigerated">{{ __('shipping.truck_quote_form.types.refrigerated') }}</option>
                        <option value="tanker">{{ __('shipping.truck_quote_form.types.tanker') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" wire:loading.attr="disabled"
                    class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-12 py-4 rounded-full font-bold text-lg hover:shadow-xl transition-all disabled:opacity-50">
                    <i class="fas fa-search ml-2"></i>
                    <span wire:loading.remove>{{ __('shipping.actions.search_offers') }}</span>
                    <span wire:loading>{{ __('shipping.actions.searching') }}</span>
                </button>
            </div>
        </form>
    </div>

    {{-- RESULTS --}}
    @if($searchPerformed && count($quotes) > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quotes as $quote)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all">
                    <div class="bg-gradient-to-r from-[#0F2E5D] to-blue-700 text-white p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold">{{ $quote['provider_name'] }}</h3>
                            <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold">
                                ⭐ {{ $quote['reputation_score'] }}
                            </span>
                        </div>
                        <p class="text-sm opacity-90">{{ $quote['service_level'] }}</p>
                    </div>

                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between border-b pb-3">
                            <span class="text-gray-600">{{ __('shipping.truck_quote_form.price') }}</span>
                            <span class="text-2xl font-bold text-teal-600">${{ number_format($quote['price_usd']) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ __('shipping.truck_quote_form.delivery_time') }}</span>
                            <span class="font-semibold">{{ $quote['transit_days'] }} {{ __('shipping.truck_quote_form.day') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ __('shipping.truck_quote_form.rating') }}</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">
                                {{ __('shipping.truck_quote_form.points') }}: {{ $quote['score'] }}
                            </span>
                        </div>
                    </div>

                    <div class="px-5 pb-5">
                        <a href="{{ route('front.shipping.book-truck') }}" 
                            class="block text-center bg-teal-500 text-white py-3 rounded-lg font-bold hover:bg-teal-600 transition-colors">
                            {{ __('shipping.actions.book_now') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
