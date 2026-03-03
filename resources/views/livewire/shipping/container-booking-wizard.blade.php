{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-8">
    
    {{-- STEPPER HEADER --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex-1 flex flex-col items-center {{ $i < $totalSteps ? 'border-l border-gray-300' : '' }}">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg mb-2
                        {{ $currentStep >= $i ? 'bg-teal-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                        @if($currentStep > $i)
                            <i class="fas fa-check"></i>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    <span class="text-xs text-center {{ $currentStep >= $i ? 'text-teal-600 font-semibold' : 'text-gray-500' }}">
                        {{ [
                            __('front.shipping.container_book_wizard.steps.shipping_details'),
                            __('front.shipping.container_book_wizard.steps.cargo_details'),
                            __('front.shipping.container_book_wizard.steps.documents'),
                            __('front.shipping.container_book_wizard.steps.insurance_services'),
                            __('front.shipping.container_book_wizard.steps.payment'),
                            __('front.shipping.container_book_wizard.steps.confirmation')
                        ][$i-1] }}
                    </span>
                </div>
            @endfor
        </div>
    </div>

    {{-- WIZARD CONTENT --}}
    <div class="bg-white rounded-xl shadow-lg p-8">
        
        {{-- STEP 1: Shipping Details --}}
        @if($currentStep == 1)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">{{ __('front.shipping.container_book_wizard.headings.step1') }}</h2>
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.origin_port_required') }}</label>
                        <input type="text" wire:model="origin_port" placeholder="{{ __('front.shipping.container_book_wizard.placeholders.origin_port') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('origin_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.destination_port_required') }}</label>
                        <input type="text" wire:model="destination_port" placeholder="{{ __('front.shipping.container_book_wizard.placeholders.destination_port') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('destination_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.loading_date_required') }}</label>
                        <input type="date" wire:model="loading_date"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('loading_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.cargo_type') }}</label>
                        <select wire:model="cargo_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                            <option value="general">{{ __('front.shipping.container_book_wizard.options.cargo_general') }}</option>
                            <option value="hazmat">{{ __('front.shipping.container_book_wizard.options.cargo_hazmat') }}</option>
                            <option value="perishable">{{ __('front.shipping.container_book_wizard.options.cargo_perishable') }}</option>
                            <option value="fragile">{{ __('front.shipping.container_book_wizard.options.cargo_fragile') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        @endif

        {{-- STEP 2: Cargo Details --}}
        @if($currentStep == 2)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">{{ __('front.shipping.container_book_wizard.headings.step2') }}</h2>
            <div class="space-y-4">
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.weight_kg_required') }}</label>
                        <input type="number" wire:model="weight_kg" placeholder="10000"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('weight_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.cbm_required') }}</label>
                        <input type="number" step="0.01" wire:model="cbm" placeholder="28"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('cbm') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.container_type') }}</label>
                        <select wire:model="container_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                            <option value="20GP">{{ __('front.shipping.container_book_wizard.options.ct_20gp') }}</option>
                            <option value="40GP">{{ __('front.shipping.container_book_wizard.options.ct_40gp') }}</option>
                            <option value="40HC">{{ __('front.shipping.container_book_wizard.options.ct_40hc') }}</option>
                            <option value="20RF">{{ __('front.shipping.container_book_wizard.options.ct_20rf') }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.cargo_description_required') }}</label>
                    <textarea wire:model="cargo_description" rows="4" placeholder="{{ __('front.shipping.container_book_wizard.placeholders.cargo_description') }}"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right"></textarea>
                    @error('cargo_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        {{-- STEP 3: Documents Upload --}}
        @if($currentStep == 3)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">{{ __('front.shipping.container_book_wizard.headings.step3') }}</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.invoice_required') }}</label>
                    <input type="file" wire:model="invoice_file" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-4 py-3 border-2 border-dashed rounded-lg focus:ring-2 focus:ring-teal-500">
                    @error('invoice_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @if($invoice_file)
                        <p class="text-sm text-green-600 mt-2"><i class="fas fa-check-circle"></i> {{ __('front.shipping.container_book_wizard.messages.uploaded_prefix') }}: {{ $invoice_file->getClientOriginalName() }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.packing_list_required') }}</label>
                    <input type="file" wire:model="packing_list_file" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-4 py-3 border-2 border-dashed rounded-lg focus:ring-2 focus:ring-teal-500">
                    @error('packing_list_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @if($packing_list_file)
                        <p class="text-sm text-green-600 mt-2"><i class="fas fa-check-circle"></i> {{ __('front.shipping.container_book_wizard.messages.uploaded_prefix') }}: {{ $packing_list_file->getClientOriginalName() }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.coo_optional') }}</label>
                    <input type="file" wire:model="coo_file" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-4 py-3 border-2 border-dashed rounded-lg focus:ring-2 focus:ring-teal-500">
                    @if($coo_file)
                        <p class="text-sm text-green-600 mt-2"><i class="fas fa-check-circle"></i> {{ __('front.shipping.container_book_wizard.messages.uploaded_prefix') }}: {{ $coo_file->getClientOriginalName() }}</p>
                    @endif
                </div>

                <div wire:loading wire:target="invoice_file,packing_list_file,coo_file" class="text-center">
                    <i class="fas fa-spinner fa-spin text-teal-500 text-2xl"></i>
                    <p class="text-gray-600 mt-2">{{ __('front.shipping.container_book_wizard.messages.uploading_file') }}</p>
                </div>
            </div>
        @endif

        {{-- STEP 4: Insurance & Services --}}
        @if($currentStep == 4)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">{{ __('front.shipping.container_book_wizard.headings.step4') }}</h2>
            <div class="space-y-4">
                <div class="border rounded-lg p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="needs_insurance" class="ml-3 w-5 h-5 text-teal-500">
                        <div>
                            <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.services.insurance_title') }}</span>
                            <p class="text-sm text-gray-600">{{ __('front.shipping.container_book_wizard.services.insurance_desc') }}</p>
                        </div>
                    </label>
                    @if($needs_insurance)
                        <div class="mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('front.shipping.container_book_wizard.labels.cargo_value_usd_required') }}</label>
                            <input type="number" wire:model="cargo_value_usd" placeholder="50000"
                                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                            @error('cargo_value_usd') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @endif
                </div>

                <div class="border rounded-lg p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="needs_customs_clearance" class="ml-3 w-5 h-5 text-teal-500">
                        <div>
                            <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.services.customs_title') }}</span>
                            <p class="text-sm text-gray-600">{{ __('front.shipping.container_book_wizard.services.customs_desc') }}</p>
                        </div>
                    </label>
                </div>

                <div class="border rounded-lg p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="needs_door_delivery" class="ml-3 w-5 h-5 text-teal-500">
                        <div>
                            <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.services.door_delivery_title') }}</span>
                            <p class="text-sm text-gray-600">{{ __('front.shipping.container_book_wizard.services.door_delivery_desc') }}</p>
                        </div>
                    </label>
                </div>
            </div>
        @endif

        {{-- STEP 5: Payment Method --}}
        @if($currentStep == 5)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">{{ __('front.shipping.container_book_wizard.headings.step5') }}</h2>
            <div class="space-y-4">
                <div class="border rounded-lg p-4 cursor-pointer {{ $payment_method == 'bank_transfer' ? 'border-teal-500 bg-teal-50' : '' }}"
                    wire:click="$set('payment_method', 'bank_transfer')">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="bank_transfer" class="ml-3 w-5 h-5 text-teal-500">
                        <div>
                            <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.payment.bank_transfer') }}</span>
                            <p class="text-sm text-gray-600">{{ __('front.shipping.container_book_wizard.payment.bank_transfer_desc') }}</p>
                        </div>
                    </label>
                </div>

                <div class="border rounded-lg p-4 cursor-pointer {{ $payment_method == 'credit_card' ? 'border-teal-500 bg-teal-50' : '' }}"
                    wire:click="$set('payment_method', 'credit_card')">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="credit_card" class="ml-3 w-5 h-5 text-teal-500">
                        <div>
                            <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.payment.credit_card') }}</span>
                            <p class="text-sm text-gray-600">{{ __('front.shipping.container_book_wizard.payment.credit_card_desc') }}</p>
                        </div>
                    </label>
                </div>

                <div class="border rounded-lg p-4 cursor-pointer {{ $payment_method == 'cod' ? 'border-teal-500 bg-teal-50' : '' }}"
                    wire:click="$set('payment_method', 'cod')">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="cod" class="ml-3 w-5 h-5 text-teal-500">
                        <div>
                            <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.payment.cod') }}</span>
                            <p class="text-sm text-gray-600">{{ __('front.shipping.container_book_wizard.payment.cod_desc') }}</p>
                        </div>
                    </label>
                </div>
            </div>
        @endif

        {{-- STEP 6: Confirmation --}}
        @if($currentStep == 6)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">{{ __('front.shipping.container_book_wizard.headings.step6') }}</h2>
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6 space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">{{ __('front.shipping.container_book_wizard.summary.route') }}</span>
                    <span class="font-semibold">{{ $origin_port }} → {{ $destination_port }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">{{ __('front.shipping.container_book_wizard.summary.date') }}</span>
                    <span class="font-semibold">{{ $loading_date }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">{{ __('front.shipping.container_book_wizard.summary.weight_volume') }}</span>
                    <span class="font-semibold">{{ $weight_kg }} {{ __('front.shipping.container_book_wizard.units.kg') }} / {{ $cbm }} {{ __('front.shipping.container_book_wizard.units.cbm') }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">{{ __('front.shipping.container_book_wizard.summary.container_type') }}</span>
                    <span class="font-semibold">{{ $container_type }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('front.shipping.container_book_wizard.summary.extra_services') }}</span>
                    <span class="font-semibold">
                        {{ $needs_insurance ? __('front.shipping.container_book_wizard.services.insurance_short') . ' • ' : '' }}
                        {{ $needs_customs_clearance ? __('front.shipping.container_book_wizard.services.customs_short') . ' • ' : '' }}
                        {{ $needs_door_delivery ? __('front.shipping.container_book_wizard.services.delivery_short') : '' }}
                    </span>
                </div>
            </div>

            <div class="border rounded-lg p-4 mb-6">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" wire:model="terms_accepted" class="ml-3 mt-1 w-5 h-5 text-teal-500">
                    <div>
                        <span class="font-semibold text-gray-800">{{ __('front.shipping.container_book_wizard.terms.accept_required') }}</span>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ __('front.shipping.container_book_wizard.terms.accept_desc') }}
                        </p>
                    </div>
                </label>
                @error('terms_accepted') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
            </div>
        @endif

        {{-- NAVIGATION BUTTONS --}}
        <div class="flex justify-between items-center mt-8 pt-6 border-t">
            @if($currentStep > 1)
                <button type="button" wire:click="previousStep"
                    class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i> {{ __('front.shipping.container_book_wizard.actions.previous') }}
                </button>
            @else
                <div></div>
            @endif

            @if($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep"
                    class="px-8 py-3 bg-teal-500 text-white rounded-lg font-semibold hover:bg-teal-600 transition-colors">
                    {{ __('front.shipping.container_book_wizard.actions.next') }} <i class="fas fa-arrow-left mr-2"></i>
                </button>
            @else
                <button type="button" wire:click="submitBooking"
                    class="px-12 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg font-bold hover:shadow-xl transition-all">
                    {{ __('front.shipping.container_book_wizard.actions.confirm_booking') }} <i class="fas fa-check mr-2"></i>
                </button>
            @endif
        </div>
    </div>
</div>
