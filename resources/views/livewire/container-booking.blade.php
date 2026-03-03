<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white pb-12" style="font-family: 'Cairo', 'Tajawal', sans-serif;">
    
    {{-- Page Header --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">
                <i class="fas fa-clipboard-check ml-2"></i>
                {{ __('front.shipping.container_book.heading') }}
            </h1>
            <p class="text-blue-100 mt-2">{{ __('front.shipping.container_book.subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Main Content (2/3) --}}
            <div class="lg:col-span-2">
                
                {{-- Stepper Progress --}}
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-center relative">
                        @for($i = 1; $i <= $totalSteps; $i++)
                            <div class="flex flex-col items-center relative z-10 flex-1">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg mb-2 transition-all duration-300
                                    {{ $currentStep > $i ? 'bg-green-500 text-white' : ($currentStep == $i ? 'bg-teal-600 text-white ring-4 ring-teal-200' : 'bg-gray-200 text-gray-500') }}">
                                    @if($currentStep > $i)
                                        <i class="fas fa-check"></i>
                                    @else
                                        {{ $i }}
                                    @endif
                                </div>
                                <span class="text-xs text-center font-semibold {{ $currentStep == $i ? 'text-teal-600' : 'text-gray-600' }}">
                                    {{ [
                                        __('front.shipping.container_book.steps.cargo_data'),
                                        __('front.shipping.container_book.steps.container_selection'),
                                        __('front.shipping.container_book.steps.schedule'),
                                        __('front.shipping.container_book.steps.documents'),
                                        __('front.shipping.container_book.steps.review'),
                                        __('front.shipping.container_book.steps.confirmation')
                                    ][$i-1] }}
                                </span>
                            </div>
                            
                            @if($i < $totalSteps)
                                <div class="flex-1 h-1 mx-2 {{ $currentStep > $i ? 'bg-green-500' : 'bg-gray-200' }} transition-all duration-300" style="margin-top: -30px;"></div>
                            @endif
                        @endfor
                    </div>
                </div>

                {{-- Flash Messages --}}
                @if(session()->has('success'))
                    <div class="bg-green-50 border-r-4 border-green-500 text-green-800 p-4 mb-6 rounded-lg">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session()->has('error'))
                    <div class="bg-red-50 border-r-4 border-red-500 text-red-800 p-4 mb-6 rounded-lg">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session()->has('file_success'))
                    <div class="bg-blue-50 border-r-4 border-blue-500 text-blue-800 p-4 mb-6 rounded-lg">
                        <i class="fas fa-file-check ml-2"></i>
                        {{ session('file_success') }}
                    </div>
                @endif

                {{-- Step Content --}}
                <div class="bg-white rounded-xl shadow-lg p-8">
                    
                    @if(!$booking_confirmed)
                        {{-- Step 1: بيانات الشحنة --}}
                        @if($currentStep === 1)
                            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">
                                <i class="fas fa-shipping-fast ml-2"></i>
                                {{ __('front.shipping.container_book.steps.cargo_data') }}
                            </h2>

                            <div class="space-y-6">
                                {{-- Shipper Information --}}
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-user-tie ml-2 text-teal-600"></i>
                                        {{ __('front.shipping.container_book.sections.shipper_info') }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.full_name_required') }}</label>
                                            <input type="text" wire:model.defer="shipper_name" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.company_name_required') }}</label>
                                            <input type="text" wire:model.defer="shipper_company" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.full_address_required') }}</label>
                                            <textarea wire:model.defer="shipper_address" rows="2"
                                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200"></textarea>
                                            @error('shipper_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.phone_required') }}</label>
                                            <input type="tel" wire:model.defer="shipper_phone" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.email_required') }}</label>
                                            <input type="email" wire:model.defer="shipper_email" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Consignee Information --}}
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-user-check ml-2 text-teal-600"></i>
                                        {{ __('front.shipping.container_book.sections.consignee_info') }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.full_name_required') }}</label>
                                            <input type="text" wire:model.defer="consignee_name" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.company_name_required') }}</label>
                                            <input type="text" wire:model.defer="consignee_company" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.full_address_required') }}</label>
                                            <textarea wire:model.defer="consignee_address" rows="2"
                                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200"></textarea>
                                            @error('consignee_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.phone_required') }}</label>
                                            <input type="tel" wire:model.defer="consignee_phone" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.email_required') }}</label>
                                            <input type="email" wire:model.defer="consignee_email" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Cargo Information --}}
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-boxes ml-2 text-teal-600"></i>
                                        {{ __('front.shipping.container_book.sections.cargo_info') }}
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.cargo_description_required') }}</label>
                                            <textarea wire:model.defer="cargo_description" rows="3"
                                                      placeholder="{{ __('front.shipping.container_book.placeholders.cargo_description') }}"
                                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200"></textarea>
                                            @error('cargo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                              <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.hs_code') }}</label>
                                              <input type="text" wire:model.defer="hs_code" placeholder="{{ __('front.shipping.container_book.placeholders.hs_code') }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                        </div>
                                        <div>
                                              <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.value_usd_required') }}</label>
                                              <input type="number" wire:model.defer="cargo_value" placeholder="{{ __('front.shipping.container_book.placeholders.cargo_value') }}"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('cargo_value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Step 2: اختيار الحاوية --}}
                        @if($currentStep === 2)
                            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">
                                <i class="fas fa-container-storage ml-2"></i>
                                {{ __('front.shipping.container_book.steps.container_selection') }}
                            </h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Container Type --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.container_type_required') }}</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach([
                                                '20GP' => __('front.shipping.container_book.container_types.20gp'),
                                                '40GP' => __('front.shipping.container_book.container_types.40gp'),
                                                '40HQ' => __('front.shipping.container_book.container_types.40hq'),
                                                'Reefer' => __('front.shipping.container_book.container_types.reefer')
                                            ] as $type => $label)
                                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                                    {{ $container_type === $type ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                    <input type="radio" wire:model="container_type" value="{{ $type }}" class="ml-3">
                                                    <span class="font-semibold">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('container_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Container Quantity --}}
                                    <div>
                                             <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.container_quantity_required') }}</label>
                                        <input type="number" wire:model="container_quantity" min="1" max="10"
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 text-2xl font-bold text-center">
                                             <p class="text-xs text-gray-500 mt-2">{{ __('front.shipping.container_book.hints.container_quantity_range') }}</p>
                                        @error('container_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Container Ownership --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.container_ownership_required') }}</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $container_ownership === 'carrier' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                            <input type="radio" wire:model="container_ownership" value="carrier" class="mt-1 ml-3">
                                            <div>
                                                <div class="font-bold text-gray-800">{{ __('front.shipping.container_book.ownership.carrier_title') }}</div>
                                                <div class="text-sm text-gray-600">{{ __('front.shipping.container_book.ownership.carrier_desc') }}</div>
                                            </div>
                                        </label>
                                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $container_ownership === 'shipper_owned' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                            <input type="radio" wire:model="container_ownership" value="shipper_owned" class="mt-1 ml-3">
                                            <div>
                                                <div class="font-bold text-gray-800">{{ __('front.shipping.container_book.ownership.soc_title') }}</div>
                                                <div class="text-sm text-gray-600">{{ __('front.shipping.container_book.ownership.soc_desc') }}</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Special Requirements --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.special_requirements_optional') }}</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach([
                                            'ventilation' => __('front.shipping.container_book.special_requirements.ventilation'),
                                            'temperature_controlled' => __('front.shipping.container_book.special_requirements.temperature_controlled'),
                                            'shock_resistant' => __('front.shipping.container_book.special_requirements.shock_resistant'),
                                            'moisture_proof' => __('front.shipping.container_book.special_requirements.moisture_proof'),
                                            'fragile' => __('front.shipping.container_book.special_requirements.fragile'),
                                            'stackable' => __('front.shipping.container_book.special_requirements.stackable')
                                        ] as $req => $label)
                                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                                <input type="checkbox" wire:model="special_requirements" value="{{ $req }}" class="ml-2">
                                                <span class="text-sm">{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Step 3: المواعيد --}}
                        @if($currentStep === 3)
                            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">
                                <i class="fas fa-calendar-alt ml-2"></i>
                                {{ __('front.shipping.container_book.steps.schedule') }}
                            </h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Preferred Loading Date --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.preferred_loading_date_required') }}</label>
                                        <input type="date" wire:model="preferred_loading_date"
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 text-lg">
                                        @error('preferred_loading_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Time Window --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.preferred_time_window_required') }}</label>
                                        <select wire:model="time_window"
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            <option value="morning">{{ __('front.shipping.container_book.time_windows.morning') }}</option>
                                            <option value="afternoon">{{ __('front.shipping.container_book.time_windows.afternoon') }}</option>
                                            <option value="evening">{{ __('front.shipping.container_book.time_windows.evening') }}</option>
                                        </select>
                                        @error('time_window') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Cutoff Date Warning --}}
                                <div class="bg-orange-50 border-r-4 border-orange-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-orange-600 ml-3 mt-1"></i>
                                        <div>
                                            <h4 class="font-bold text-orange-800 mb-1">{{ __('front.shipping.container_book.cutoff.title') }}</h4>
                                            <p class="text-sm text-orange-700">
                                                {{ __('front.shipping.container_book.cutoff.description', ['date' => now()->parse($selectedQuote['cutoff_date'] ?? now()->addDays(14))->format('Y-m-d')]) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Acknowledgements --}}
                                <div class="space-y-3">
                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50
                                        {{ $cutoff_acknowledgement ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:model="cutoff_acknowledgement" class="mt-1 ml-3">
                                        <div class="text-sm">
                                            <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.acknowledgements.cutoff_required') }}</span>
                                            <p class="text-gray-600 mt-1">{{ __('front.shipping.container_book.acknowledgements.cutoff_desc') }}</p>
                                        </div>
                                    </label>
                                    @error('cutoff_acknowledgement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50
                                        {{ $flexible_dates ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:model="flexible_dates" class="mt-1 ml-3">
                                        <div class="text-sm">
                                            <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.acknowledgements.flexible_dates') }}</span>
                                            <p class="text-gray-600 mt-1">{{ __('front.shipping.container_book.acknowledgements.flexible_dates_desc') }}</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endif

                        {{-- Step 4: المستندات والتأمين --}}
                        @if($currentStep === 4)
                            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">
                                <i class="fas fa-file-upload ml-2"></i>
                                {{ __('front.shipping.container_book.steps.documents') }}
                            </h2>

                            <div class="space-y-6">
                                {{-- Document Upload Section --}}
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-folder-open ml-2 text-teal-600"></i>
                                        {{ __('front.shipping.container_book.sections.upload_documents') }}
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        {{-- Invoice --}}
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-teal-500 transition">
                                            <label class="block cursor-pointer">
                                                <div class="text-center">
                                                    <i class="fas fa-file-invoice text-4xl text-gray-400 mb-3"></i>
                                                    <h4 class="font-bold text-gray-800 mb-1">{{ __('front.shipping.container_book.documents.invoice_title') }}</h4>
                                                    <p class="text-sm text-gray-600 mb-3">{{ __('front.shipping.container_book.documents.upload_hint') }}</p>
                                                    <input type="file" wire:model="invoice_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                                    @if($uploaded_invoice_path)
                                                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                                                            <i class="fas fa-check-circle ml-1"></i> {{ __('front.shipping.container_book.documents.uploaded_success') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                                                            {{ __('front.shipping.container_book.documents.click_to_select') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="invoice_file" class="text-center mt-3">
                                                <i class="fas fa-spinner fa-spin text-teal-600"></i>
                                                <span class="text-teal-600 mr-2">{{ __('front.shipping.container_book.documents.uploading') }}</span>
                                            </div>
                                            @error('invoice_file') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Packing List --}}
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-teal-500 transition">
                                            <label class="block cursor-pointer">
                                                <div class="text-center">
                                                    <i class="fas fa-list-alt text-4xl text-gray-400 mb-3"></i>
                                                    <h4 class="font-bold text-gray-800 mb-1">{{ __('front.shipping.container_book.documents.packing_list_title') }}</h4>
                                                    <p class="text-sm text-gray-600 mb-3">{{ __('front.shipping.container_book.documents.upload_hint') }}</p>
                                                    <input type="file" wire:model="packing_list_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                                    @if($uploaded_packing_path)
                                                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                                                            <i class="fas fa-check-circle ml-1"></i> {{ __('front.shipping.container_book.documents.uploaded_success') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                                                            {{ __('front.shipping.container_book.documents.click_to_select') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="packing_list_file" class="text-center mt-3">
                                                <i class="fas fa-spinner fa-spin text-teal-600"></i>
                                                <span class="text-teal-600 mr-2">{{ __('front.shipping.container_book.documents.uploading') }}</span>
                                            </div>
                                            @error('packing_list_file') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Certificate of Origin --}}
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-teal-500 transition">
                                            <label class="block cursor-pointer">
                                                <div class="text-center">
                                                    <i class="fas fa-certificate text-4xl text-gray-400 mb-3"></i>
                                                    <h4 class="font-bold text-gray-800 mb-1">{{ __('front.shipping.container_book.documents.certificate_of_origin_title') }}</h4>
                                                    <p class="text-sm text-gray-600 mb-3">{{ __('front.shipping.container_book.documents.upload_hint') }}</p>
                                                    <input type="file" wire:model="certificate_of_origin_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                                    @if($uploaded_coo_path)
                                                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                                                            <i class="fas fa-check-circle ml-1"></i> {{ __('front.shipping.container_book.documents.uploaded_success') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                                                            {{ __('front.shipping.container_book.documents.click_to_select') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="certificate_of_origin_file" class="text-center mt-3">
                                                <i class="fas fa-spinner fa-spin text-teal-600"></i>
                                                <span class="text-teal-600 mr-2">{{ __('front.shipping.container_book.documents.uploading') }}</span>
                                            </div>
                                            @error('certificate_of_origin_file') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Insurance Section --}}
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-shield-alt ml-2 text-teal-600"></i>
                                        {{ __('front.shipping.container_book.sections.insurance') }}
                                    </h3>

                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer mb-4
                                        {{ $insurance_required ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:model="insurance_required" class="mt-1 ml-3">
                                        <div>
                                            <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.insurance.required_label') }}</span>
                                            <p class="text-sm text-gray-600 mt-1">{{ __('front.shipping.container_book.insurance.required_desc') }}</p>
                                        </div>
                                    </label>

                                    @if($insurance_required)
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.insurance_type') }}</label>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <label class="flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                                                        {{ $insurance_type === 'basic' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                        <input type="radio" wire:model="insurance_type" value="basic" class="mb-2">
                                                        <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.insurance_types.basic.name') }}</span>
                                                        <span class="text-sm text-gray-600">{{ __('front.shipping.container_book.insurance_types.basic.rate') }}</span>
                                                        <span class="text-xs text-gray-500 mt-1">{{ __('front.shipping.container_book.insurance_types.basic.desc') }}</span>
                                                    </label>
                                                    <label class="flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                                                        {{ $insurance_type === 'comprehensive' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                        <input type="radio" wire:model="insurance_type" value="comprehensive" class="mb-2">
                                                        <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.insurance_types.comprehensive.name') }}</span>
                                                        <span class="text-sm text-gray-600">{{ __('front.shipping.container_book.insurance_types.comprehensive.rate') }}</span>
                                                        <span class="text-xs text-gray-500 mt-1">{{ __('front.shipping.container_book.insurance_types.comprehensive.desc') }}</span>
                                                    </label>
                                                    <label class="flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                                                        {{ $insurance_type === 'custom' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                        <input type="radio" wire:model="insurance_type" value="custom" class="mb-2">
                                                        <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.insurance_types.custom.name') }}</span>
                                                        <span class="text-sm text-gray-600">{{ __('front.shipping.container_book.insurance_types.custom.rate') }}</span>
                                                        <span class="text-xs text-gray-500 mt-1">{{ __('front.shipping.container_book.insurance_types.custom.desc') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Step 5: المراجعة والدفع --}}
                        @if($currentStep === 5)
                            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">
                                <i class="fas fa-clipboard-check ml-2"></i>
                                {{ __('front.shipping.container_book.sections.review_and_payment') }}
                            </h2>

                            <div class="space-y-6">
                                {{-- Booking Summary --}}
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('front.shipping.container_book.sections.booking_summary') }}</h3>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">{{ __('front.shipping.container_book.summary.shipper') }}</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $shipper_name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">{{ __('front.shipping.container_book.summary.consignee') }}</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $consignee_name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">{{ __('front.shipping.container_book.summary.container_type') }}</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $container_type }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">{{ __('front.shipping.container_book.summary.quantity') }}</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $container_quantity }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">{{ __('front.shipping.container_book.summary.loading_date') }}</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $preferred_loading_date }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">{{ __('front.shipping.container_book.summary.insurance') }}</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $insurance_required ? __('front.shipping.container_book.common.yes') : __('front.shipping.container_book.common.no') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment Method --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.payment_method_required') }}</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $payment_method === 'bank_transfer' ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="payment_method" value="bank_transfer" class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-university text-2xl text-teal-600 ml-3"></i>
                                                <span class="font-semibold">{{ __('front.shipping.container_book.payment_methods.bank_transfer') }}</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $payment_method === 'credit_card' ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="payment_method" value="credit_card" class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-credit-card text-2xl text-teal-600 ml-3"></i>
                                                <span class="font-semibold">{{ __('front.shipping.container_book.payment_methods.credit_card') }}</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $payment_method === 'cash' ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="payment_method" value="cash" class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-money-bill-wave text-2xl text-teal-600 ml-3"></i>
                                                <span class="font-semibold">{{ __('front.shipping.container_book.payment_methods.cash') }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Payment Terms --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">{{ __('front.shipping.container_book.labels.payment_terms_required') }}</label>
                                    <select wire:model="payment_terms"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                        <option value="prepaid">{{ __('front.shipping.container_book.payment_terms.prepaid') }}</option>
                                        <option value="collect">{{ __('front.shipping.container_book.payment_terms.collect') }}</option>
                                        <option value="third_party">{{ __('front.shipping.container_book.payment_terms.third_party') }}</option>
                                    </select>
                                </div>

                                {{-- Promotional Code --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-gray-700">{{ __('front.shipping.container_book.labels.promotional_code_optional') }}</label>
                                    <div class="flex gap-2">
                                        <input type="text" wire:model.defer="promotional_code" placeholder="{{ __('front.shipping.container_book.placeholders.promotional_code') }}"
                                               class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                        <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold">
                                            {{ __('front.shipping.container_book.actions.apply') }}
                                        </button>
                                    </div>
                                </div>

                                {{-- Terms Agreement --}}
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer
                                    {{ $agreed_to_terms ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                    <input type="checkbox" wire:model="agreed_to_terms" class="mt-1 ml-3">
                                    <div class="text-sm">
                                        <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.terms.agree_required') }}</span>
                                        <p class="text-gray-600 mt-1">
                                            {{ __('front.shipping.container_book.terms.read_and_agree') }}
                                            <a href="#" class="text-teal-600 hover:underline">{{ __('front.shipping.container_book.terms.terms_and_conditions') }}</a>
                                            {{ __('front.shipping.container_book.terms.and') }}
                                            <a href="#" class="text-teal-600 hover:underline">{{ __('front.shipping.container_book.terms.privacy_policy') }}</a>
                                        </p>
                                    </div>
                                </label>
                                @error('agreed_to_terms') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        {{-- Step 6: التأكيد --}}
                        @if($currentStep === 6 && !$booking_confirmed)
                            <div class="text-center py-12">
                                <i class="fas fa-spinner fa-spin text-6xl text-teal-600 mb-4"></i>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ __('front.shipping.container_book.processing.creating_booking') }}</h3>
                                <p class="text-gray-600">{{ __('front.shipping.container_book.processing.please_wait') }}</p>
                            </div>
                        @endif

                        {{-- Navigation Buttons --}}
                        @if($currentStep < 6 || ($currentStep === 6 && !$booking_confirmed))
                            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                @if($currentStep > 1)
                                    <button type="button" wire:click="previousStep"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-8 py-3 rounded-lg transition">
                                        <i class="fas fa-arrow-right ml-2"></i>
                                        {{ __('front.shipping.container_book.actions.previous') }}
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                @if($currentStep < 5)
                                    <button type="button" wire:click="nextStep"
                                            class="bg-gradient-to-l from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition">
                                        {{ __('front.shipping.container_book.actions.next') }}
                                        <i class="fas fa-arrow-left mr-2"></i>
                                    </button>
                                @elseif($currentStep === 5)
                                    <button type="button" wire:click="submitBooking"
                                            wire:loading.attr="disabled"
                                            class="bg-gradient-to-l from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition">
                                        <span wire:loading.remove wire:target="submitBooking">
                                            <i class="fas fa-check-circle ml-2"></i>
                                            {{ __('front.shipping.container_book.actions.confirm_booking') }}
                                        </span>
                                        <span wire:loading wire:target="submitBooking">
                                            <i class="fas fa-spinner fa-spin ml-2"></i>
                                            {{ __('front.shipping.container_book.actions.confirming') }}
                                        </span>
                                    </button>
                                @endif
                            </div>
                        @endif
                    @else
                        {{-- Booking Confirmed View --}}
                        <div class="text-center py-12">
                            <div class="inline-block bg-green-100 rounded-full p-6 mb-6">
                                <i class="fas fa-check-circle text-6xl text-green-600"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-800 mb-3">{{ __('front.shipping.container_book.confirmed.success_title') }}</h2>
                            <p class="text-xl text-gray-600 mb-2">{{ __('front.shipping.container_book.confirmed.reference_label') }}</p>
                            <div class="text-4xl font-bold text-teal-600 mb-6">{{ $booking_reference }}</div>
                            
                            <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-6 mb-6 max-w-2xl mx-auto">
                                <i class="fas fa-info-circle text-blue-600 text-2xl mb-3"></i>
                                <p class="text-blue-800">
                                    {{ __('front.shipping.container_book.confirmed.notice') }}
                                </p>
                            </div>

                            <div class="flex justify-center gap-4">
                                <button wire:click="downloadConfirmation"
                                        class="bg-gradient-to-l from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition">
                                    <i class="fas fa-download ml-2"></i>
                                    {{ __('front.shipping.container_book.actions.download_confirmation') }}
                                </button>
                                <a href="{{ route('front.home') }}"
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-8 py-3 rounded-lg transition inline-block">
                                    <i class="fas fa-home ml-2"></i>
                                    {{ __('front.shipping.container_book.actions.back_home') }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar: Quote Summary (1/3) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-[#0F2E5D] mb-4">
                        <i class="fas fa-file-invoice-dollar ml-2"></i>
                        {{ __('front.shipping.container_book.quote_summary.title') }}
                    </h3>

                    @if($selectedQuote)
                        <div class="space-y-4">
                            <div class="text-center pb-4 border-b border-gray-200">
                                <img src="{{ $selectedQuote['logo'] ?? '' }}" alt="Company Logo" class="h-16 mx-auto mb-3">
                                <div class="font-bold text-gray-800">{{ $selectedQuote['company'] ?? __('front.shipping.container_book.common.na') }}</div>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('front.shipping.container_book.quote_summary.base_price') }}</span>
                                    <span class="font-bold">${{ number_format($selectedQuote['total_price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('front.shipping.container_book.quote_summary.container_quantity') }}</span>
                                    <span class="font-bold">x {{ $container_quantity }}</span>
                                </div>
                                @if($insurance_required)
                                    <div class="flex justify-between text-teal-600">
                                        <span>{{ __('front.shipping.container_book.quote_summary.insurance_with_type', ['type' => __('front.shipping.container_book.insurance_types.' . $insurance_type . '.name')]) }}</span>
                                        <span class="font-bold">
                                            +${{ number_format(($selectedQuote['total_price'] ?? 0) * match($insurance_type) { 'basic' => 0.02, 'comprehensive' => 0.05, 'custom' => 0.08, default => 0 }, 2) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between pt-3 border-t-2 border-gray-300 text-lg">
                                    <span class="font-bold text-gray-800">{{ __('front.shipping.container_book.quote_summary.total') }}</span>
                                    <span class="font-bold text-teal-600">${{ number_format($this->totalPrice, 2) }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('front.shipping.container_book.quote_summary.transit_time') }}</span>
                                    <span class="font-semibold">{{ $selectedQuote['transit_days'] ?? __('front.shipping.container_book.common.na') }} {{ __('front.shipping.container_book.common.day') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ __('front.shipping.container_book.quote_summary.cutoff_date') }}</span>
                                    <span class="font-semibold">{{ $selectedQuote['cutoff_date'] ?? __('front.shipping.container_book.common.na') }}</span>
                                </div>
                            </div>

                            @if($selectedQuote['is_door_to_door'] ?? false)
                                <div class="flex items-center justify-center bg-blue-50 text-blue-800 rounded-lg px-3 py-2 text-xs font-semibold">
                                    <i class="fas fa-truck ml-1"></i>
                                    {{ __('front.shipping.container_book.quote_summary.door_to_door_service') }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-orange-50 rounded-lg p-4 text-xs text-orange-800">
                            <i class="fas fa-shield-alt ml-1"></i>
                            <strong>{{ __('front.shipping.container_book.security_title') }}:</strong> {{ __('front.shipping.container_book.security_desc_short') }}
                        </div>
                    </div>
                </div>
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
