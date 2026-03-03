<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white" style="font-family: 'Cairo', 'Tajawal', sans-serif;">
    
    {{-- Hero Section with Search Panel --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold mb-3">{{ __('front.shipping.shipping_quote.heading') }}</h1>
                <p class="text-xl text-blue-100">{{ __('front.shipping.shipping_quote.subtitle') }}</p>
            </div>

            {{-- Search Panel --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8 text-gray-800">
                <form wire:submit.prevent="searchQuotes">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        {{-- Origin Port --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-ship ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.origin_port') }}
                            </label>
                            <input 
                                type="text" 
                                wire:model.defer="origin_port"
                                placeholder="{{ __('front.shipping.shipping_quote.placeholders.port') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                                id="origin-port-input"
                            >
                            @error('origin_port')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Destination Port --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-anchor ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.destination_port') }}
                            </label>
                            <input 
                                type="text" 
                                wire:model.defer="destination_port"
                                placeholder="{{ __('front.shipping.shipping_quote.placeholders.port') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                                id="destination-port-input"
                            >
                            @error('destination_port')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Loading Date --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-calendar ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.loading_date') }}
                            </label>
                            <input 
                                type="date" 
                                wire:model.defer="loading_date"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                            >
                            @error('loading_date')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Weight --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-weight ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.weight_kg') }}
                            </label>
                            <input 
                                type="number" 
                                wire:model.defer="weight_kg"
                                placeholder="{{ __('front.shipping.shipping_quote.placeholders.weight') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                            >
                            @error('weight_kg')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- CBM --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-cube ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.volume_cbm') }}
                            </label>
                            <input 
                                type="number" 
                                step="0.01"
                                wire:model.defer="cbm"
                                placeholder="{{ __('front.shipping.shipping_quote.placeholders.cbm') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                            >
                            @error('cbm')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Cargo Type --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-box ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.cargo_type') }}
                            </label>
                            <select 
                                wire:model.defer="cargo_type"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                            >
                                <option value="normal">{{ __('front.shipping.shipping_quote.options.cargo_normal') }}</option>
                                <option value="dangerous">{{ __('front.shipping.shipping_quote.options.cargo_dangerous') }}</option>
                            </select>
                        </div>

                        {{-- Service Type --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-truck-loading ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.service_type') }}
                            </label>
                            <select 
                                wire:model.defer="service_type"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                            >
                                <option value="FCL">{{ __('front.shipping.shipping_quote.options.service_fcl') }}</option>
                                <option value="LCL">{{ __('front.shipping.shipping_quote.options.service_lcl') }}</option>
                            </select>
                        </div>

                        {{-- Container Type --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-[#0F2E5D]">
                                <i class="fas fa-container-storage ml-1"></i>
                                {{ __('front.shipping.shipping_quote.labels.container_type') }}
                            </label>
                            <select 
                                wire:model.defer="container_type"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition"
                            >
                                <option value="20GP">{{ __('front.shipping.shipping_quote.options.container_20gp') }}</option>
                                <option value="40GP">{{ __('front.shipping.shipping_quote.options.container_40gp') }}</option>
                                <option value="40HQ">{{ __('front.shipping.shipping_quote.options.container_40hq') }}</option>
                                <option value="Reefer">{{ __('front.shipping.shipping_quote.options.container_reefer') }}</option>
                            </select>
                        </div>

                        {{-- Submit Button --}}
                        <div class="lg:col-span-1 flex items-end">
                            <button 
                                type="submit"
                                class="w-full bg-gradient-to-l from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-200"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove wire:target="searchQuotes">
                                    <i class="fas fa-search ml-2"></i>
                                    {{ __('front.shipping.shipping_quote.actions.show_offers') }}
                                </span>
                                <span wire:loading wire:target="searchQuotes">
                                    <i class="fas fa-spinner fa-spin ml-2"></i>
                                    {{ __('front.shipping.shipping_quote.actions.searching') }}
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Optional Dimensions --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button 
                            type="button" 
                            class="text-[#0F2E5D] font-semibold hover:text-teal-600 transition"
                            x-data="{ open: false }"
                            @click="open = !open"
                        >
                            <i class="fas fa-ruler-combined ml-1"></i>
                            {{ __('front.shipping.shipping_quote.labels.dimensions_optional') }}
                            <i class="fas fa-chevron-down mr-2 text-xs" x-show="!open"></i>
                            <i class="fas fa-chevron-up mr-2 text-xs" x-show="open" style="display: none;"></i>
                        </button>
                        
                        <div x-show="open" x-collapse style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                     <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.shipping_quote.labels.length_cm') }}</label>
                                     <input type="number" wire:model.defer="length" placeholder="{{ __('front.shipping.shipping_quote.placeholders.length') }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                     <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.shipping_quote.labels.width_cm') }}</label>
                                     <input type="number" wire:model.defer="width" placeholder="{{ __('front.shipping.shipping_quote.placeholders.width') }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                     <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.shipping_quote.labels.height_cm') }}</label>
                                     <input type="number" wire:model.defer="height" placeholder="{{ __('front.shipping.shipping_quote.placeholders.height') }}" 
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                </div>
                            </div>
                            <button 
                                type="button" 
                                wire:click="calculateCBM"
                                class="mt-3 text-sm text-teal-600 font-semibold hover:text-teal-700"
                            >
                                {{ __('front.shipping.shipping_quote.actions.auto_calculate_cbm') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Results Section --}}
    @if($searchPerformed)
    <div class="max-w-7xl mx-auto px-4 py-12">
        
        {{-- Flash Messages --}}
        @if(session()->has('success'))
            <div class="bg-green-50 border-r-4 border-green-500 text-green-800 p-4 mb-6 rounded-lg">
                <i class="fas fa-check-circle ml-2"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session()->has('warning'))
            <div class="bg-yellow-50 border-r-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded-lg">
                <i class="fas fa-exclamation-triangle ml-2"></i>
                {{ session('warning') }}
            </div>
        @endif

        {{-- Results Header --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-[#0F2E5D]">{{ __('front.shipping.shipping_quote.results_title') }}</h2>
                <p class="text-gray-600 mt-2">{!! __('front.shipping.shipping_quote.results_found', ['count' => count($quotes)]) !!}</p>
            </div>

            {{-- Sort Options --}}
            <div class="flex gap-3">
                <button 
                    wire:click="updateSort('best_value')"
                    class="px-4 py-2 rounded-lg font-semibold transition {{ $sortBy === 'best_value' ? 'bg-[#0F2E5D] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                >
                    <i class="fas fa-star ml-1"></i>
                    {{ __('front.shipping.shipping_quote.sort.best_value') }}
                </button>
                <button 
                    wire:click="updateSort('price')"
                    class="px-4 py-2 rounded-lg font-semibold transition {{ $sortBy === 'price' ? 'bg-[#0F2E5D] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                >
                    <i class="fas fa-dollar-sign ml-1"></i>
                    {{ __('front.shipping.shipping_quote.sort.lowest_price') }}
                </button>
                <button 
                    wire:click="updateSort('transit_time')"
                    class="px-4 py-2 rounded-lg font-semibold transition {{ $sortBy === 'transit_time' ? 'bg-[#0F2E5D] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
                >
                    <i class="fas fa-clock ml-1"></i>
                    {{ __('front.shipping.shipping_quote.sort.fastest') }}
                </button>
            </div>
        </div>

        {{-- Comparison Bar --}}
        @if(count($selectedForComparison) > 0)
        <div class="bg-teal-50 border-2 border-teal-500 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <span class="font-bold text-teal-800">
                        <i class="fas fa-balance-scale ml-2"></i>
                        {{ __('front.shipping.shipping_quote.comparison.selected_count', ['count' => count($selectedForComparison)]) }}
                    </span>
                </div>
                <button 
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg font-semibold transition"
                    x-data
                    @click="$dispatch('open-comparison-modal')"
                >
                    <i class="fas fa-eye ml-2"></i>
                    {{ __('front.shipping.shipping_quote.comparison.show') }}
                </button>
            </div>
        </div>
        @endif

        {{-- Quotes Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($quotes as $quote)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 border-2 {{ in_array($quote['id'], $selectedForComparison) ? 'border-teal-500' : 'border-gray-200' }}">
                
                {{-- Card Header --}}
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            <img src="{{ $quote['logo'] }}" alt="{{ $quote['company'] }}" class="w-28 h-14 object-contain">
                            <div>
                                <h3 class="text-xl font-bold text-[#0F2E5D]">{{ $quote['company'] }}</h3>
                                <div class="flex items-center mt-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star text-yellow-400 text-sm {{ $i < floor($quote['rating']) ? '' : 'opacity-30' }}"></i>
                                    @endfor
                                    <span class="text-sm text-gray-600 mr-2">({{ $quote['rating'] }})</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Badges --}}
                        <div class="flex flex-col gap-2">
                            @if($quote['is_door_to_door'])
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ __('front.shipping.shipping_quote.cards.badge_door_to_door') }}
                                </span>
                            @endif
                            @if($quote['has_cargox'])
                                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ __('front.shipping.shipping_quote.cards.badge_cargox') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6">
                    {{-- Price & Transit --}}
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <div class="text-4xl font-bold text-[#0F2E5D]">
                                ${{ number_format($quote['total_price'], 2) }}
                                <span class="text-lg text-gray-500">{{ $quote['currency'] }}</span>
                            </div>
                            <div class="text-sm text-gray-600 mt-1">{{ __('front.shipping.shipping_quote.cards.total_price') }}</div>
                        </div>
                        <div class="text-left">
                            <div class="text-2xl font-bold text-teal-600">
                                {{ $quote['transit_days'] }} {{ __('front.shipping.shipping_quote.units.day') }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">{{ __('front.shipping.shipping_quote.cards.transit_time') }}</div>
                        </div>
                    </div>

                    {{-- Validity Badge --}}
                    <div class="bg-orange-50 border border-orange-300 rounded-lg p-3 mb-4 flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 ml-2"></i>
                        <span class="text-orange-800 font-semibold">{{ __('front.shipping.shipping_quote.cards.validity', ['hours' => $quote['validity_hours']]) }}</span>
                    </div>

                    {{-- Breakdown --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-bold text-[#0F2E5D] mb-3 flex items-center">
                            <i class="fas fa-receipt ml-2"></i>
                            {{ __('front.shipping.shipping_quote.cards.cost_breakdown') }}
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('front.shipping.shipping_quote.breakdown.shipping') }}</span>
                                <span class="font-semibold">${{ number_format($quote['breakdown']['shipping'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('front.shipping.shipping_quote.breakdown.port_fees') }}</span>
                                <span class="font-semibold">${{ number_format($quote['breakdown']['port_fees'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('front.shipping.shipping_quote.breakdown.documentation') }}</span>
                                <span class="font-semibold">${{ number_format($quote['breakdown']['documentation'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('front.shipping.shipping_quote.breakdown.insurance') }}</span>
                                <span class="font-semibold">${{ number_format($quote['breakdown']['insurance'], 2) }}</span>
                            </div>
                            <div class="border-t border-gray-300 pt-2 flex justify-between font-bold">
                                <span>{{ __('front.shipping.shipping_quote.breakdown.total') }}</span>
                                <span class="text-[#0F2E5D]">${{ number_format($quote['total_price'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button 
                            wire:click="selectQuote('{{ $quote['id'] }}')"
                            class="flex-1 bg-gradient-to-l from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-200"
                        >
                            <i class="fas fa-check-circle ml-2"></i>
                            {{ __('front.shipping.shipping_quote.actions.select_offer') }}
                        </button>
                        
                        <button 
                            wire:click="toggleComparison('{{ $quote['id'] }}')"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition {{ in_array($quote['id'], $selectedForComparison) ? 'bg-teal-100 text-teal-800 border-2 border-teal-500' : '' }}"
                            title="{{ __('front.shipping.shipping_quote.actions.add_to_comparison') }}"
                        >
                            <i class="fas fa-balance-scale"></i>
                        </button>
                        
                        <button 
                            wire:click="saveQuote('{{ $quote['id'] }}')"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-lg transition {{ in_array($quote['id'], $savedQuotes) ? 'bg-blue-100 text-blue-800' : '' }}"
                            title="{{ __('front.shipping.shipping_quote.actions.save_offer') }}"
                        >
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Comparison Modal --}}
    <div 
        x-data="{ open: false }"
        @open-comparison-modal.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="open = false"></div>
            
            <div class="relative bg-white rounded-2xl shadow-2xl max-w-6xl w-full p-8 z-10">
                <button 
                    @click="open = false"
                    class="absolute top-4 left-4 text-gray-500 hover:text-gray-800 text-2xl"
                >
                    <i class="fas fa-times"></i>
                </button>
                
                <h2 class="text-3xl font-bold text-[#0F2E5D] mb-6 text-center">
                    <i class="fas fa-balance-scale ml-2"></i>
                    {{ __('front.shipping.shipping_quote.comparison.title') }}
                </h2>

                @if(count($this->selectedQuotes) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-300">
                                <th class="text-right py-4 px-4 font-bold text-[#0F2E5D]">{{ __('front.shipping.shipping_quote.comparison.item') }}</th>
                                @foreach($this->selectedQuotes as $quote)
                                <th class="text-center py-4 px-4">
                                    <div class="font-bold text-gray-800">{{ $quote['company'] }}</div>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr class="border-b">
                                <td class="py-3 px-4 font-semibold">{{ __('front.shipping.shipping_quote.cards.total_price') }}</td>
                                @foreach($this->selectedQuotes as $quote)
                                <td class="text-center py-3 px-4 font-bold text-[#0F2E5D]">
                                    ${{ number_format($quote['total_price'], 2) }}
                                </td>
                                @endforeach
                            </tr>
                            <tr class="border-b bg-gray-50">
                                <td class="py-3 px-4 font-semibold">{{ __('front.shipping.shipping_quote.cards.transit_time') }}</td>
                                @foreach($this->selectedQuotes as $quote)
                                <td class="text-center py-3 px-4">{{ $quote['transit_days'] }} {{ __('front.shipping.shipping_quote.units.day') }}</td>
                                @endforeach
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 font-semibold">{{ __('front.shipping.shipping_quote.comparison.offer_validity') }}</td>
                                @foreach($this->selectedQuotes as $quote)
                                <td class="text-center py-3 px-4">{{ $quote['validity_hours'] }} {{ __('front.shipping.shipping_quote.units.hour') }}</td>
                                @endforeach
                            </tr>
                            <tr class="border-b bg-gray-50">
                                <td class="py-3 px-4 font-semibold">{{ __('front.shipping.shipping_quote.comparison.rating') }}</td>
                                @foreach($this->selectedQuotes as $quote)
                                <td class="text-center py-3 px-4">
                                    <div class="flex justify-center items-center gap-1">
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star text-yellow-400 text-xs {{ $i < floor($quote['rating']) ? '' : 'opacity-30' }}"></i>
                                        @endfor
                                        <span class="mr-1">{{ $quote['rating'] }}</span>
                                    </div>
                                </td>
                                @endforeach
                            </tr>
                            <tr class="border-b">
                                <td class="py-3 px-4 font-semibold">{{ __('front.shipping.shipping_quote.comparison.door_to_door') }}</td>
                                @foreach($this->selectedQuotes as $quote)
                                <td class="text-center py-3 px-4">
                                    @if($quote['is_door_to_door'])
                                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                    @else
                                        <i class="fas fa-times-circle text-red-400 text-xl"></i>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            <tr class="border-b bg-gray-50">
                                <td class="py-3 px-4 font-semibold">{{ __('front.shipping.shipping_quote.comparison.cargox') }}</td>
                                @foreach($this->selectedQuotes as $quote)
                                <td class="text-center py-3 px-4">
                                    @if($quote['has_cargox'])
                                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                    @else
                                        <i class="fas fa-times-circle text-red-400 text-xl"></i>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // Autocomplete for ports
    const ports = @json(trans('front.shipping.shipping_quote.ports'));

    function setupAutocomplete(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;

        let resultsDiv = document.createElement('div');
        resultsDiv.className = 'absolute z-50 w-full bg-white border-2 border-gray-300 rounded-lg shadow-xl mt-1 max-h-60 overflow-y-auto';
        resultsDiv.style.display = 'none';
        input.parentElement.style.position = 'relative';
        input.parentElement.appendChild(resultsDiv);

        input.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            if (value.length < 2) {
                resultsDiv.style.display = 'none';
                return;
            }

            const matches = ports.filter(port => 
                port.name.includes(value) || 
                port.code.toLowerCase().includes(value) ||
                port.country.includes(value)
            );

            if (matches.length === 0) {
                resultsDiv.style.display = 'none';
                return;
            }

            resultsDiv.innerHTML = matches.map(port => `
                <div class="px-4 py-3 hover:bg-teal-50 cursor-pointer border-b border-gray-100 transition" 
                     onclick="selectPort('${inputId}', '${port.code} - ${port.name}, ${port.country}')">
                    <div class="font-bold text-[#0F2E5D]">${port.name}</div>
                    <div class="text-sm text-gray-600">${port.code} - ${port.country}</div>
                </div>
            `).join('');

            resultsDiv.style.display = 'block';
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!input.parentElement.contains(e.target)) {
                resultsDiv.style.display = 'none';
            }
        });
    }

    window.selectPort = function(inputId, value) {
        const input = document.getElementById(inputId);
        input.value = value;
        input.dispatchEvent(new Event('input'));
        input.parentElement.querySelector('div[class*="absolute"]').style.display = 'none';
    };

    // Initialize autocomplete
    setupAutocomplete('origin-port-input');
    setupAutocomplete('destination-port-input');
});
</script>
@endpush
