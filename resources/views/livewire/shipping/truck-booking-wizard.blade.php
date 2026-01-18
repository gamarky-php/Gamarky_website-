<div class="container mx-auto px-4 py-8" dir="rtl">
    
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
                        {{ ['المسار', 'الحمولة', 'المستندات', 'الدفع', 'التأكيد'][$i-1] }}
                    </span>
                </div>
            @endfor
        </div>
    </div>

    {{-- WIZARD CONTENT --}}
    <div class="bg-white rounded-xl shadow-lg p-8">
        
        {{-- STEP 1 --}}
        @if($currentStep == 1)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">الخطوة 1: تفاصيل المسار</h2>
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">مدينة الانطلاق *</label>
                        <input type="text" wire:model="origin_city" placeholder="الرياض"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('origin_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">مدينة الوصول *</label>
                        <input type="text" wire:model="destination_city" placeholder="جدة"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('destination_city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الاستلام *</label>
                        <input type="date" wire:model="pickup_date"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('pickup_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ التسليم المتوقع</label>
                        <input type="date" wire:model="delivery_date"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    </div>
                </div>
            </div>
        @endif

        {{-- STEP 2 --}}
        @if($currentStep == 2)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">الخطوة 2: بيانات الحمولة</h2>
            <div class="space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الوزن (كجم) *</label>
                        <input type="number" wire:model="weight_kg" placeholder="5000"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        @error('weight_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الشاحنة</label>
                        <select wire:model="truck_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                            <option value="flatbed">شاحنة مفتوحة</option>
                            <option value="box">شاحنة مغلقة</option>
                            <option value="refrigerated">شاحنة مبردة</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">وصف البضاعة *</label>
                    <textarea wire:model="cargo_description" rows="4" placeholder="صف البضاعة..."
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right"></textarea>
                    @error('cargo_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif

        {{-- STEP 3 --}}
        @if($currentStep == 3)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">الخطوة 3: المستندات</h2>
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الفاتورة *</label>
                    <input type="file" wire:model="invoice_file" accept=".pdf,.jpg,.png"
                        class="w-full px-4 py-3 border-2 border-dashed rounded-lg">
                    @error('invoice_file') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @if($invoice_file)
                        <p class="text-sm text-green-600 mt-2"><i class="fas fa-check-circle"></i> تم الرفع</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">قائمة التعبئة</label>
                    <input type="file" wire:model="packing_list_file" accept=".pdf,.jpg,.png"
                        class="w-full px-4 py-3 border-2 border-dashed rounded-lg">
                    @if($packing_list_file)
                        <p class="text-sm text-green-600 mt-2"><i class="fas fa-check-circle"></i> تم الرفع</p>
                    @endif
                </div>
            </div>
        @endif

        {{-- STEP 4 --}}
        @if($currentStep == 4)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">الخطوة 4: طريقة الدفع</h2>
            <div class="space-y-4">
                <div class="border rounded-lg p-4 cursor-pointer {{ $payment_method == 'bank_transfer' ? 'border-teal-500 bg-teal-50' : '' }}"
                    wire:click="$set('payment_method', 'bank_transfer')">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="bank_transfer" class="ml-3 w-5 h-5">
                        <span class="font-semibold">تحويل بنكي</span>
                    </label>
                </div>
                <div class="border rounded-lg p-4 cursor-pointer {{ $payment_method == 'credit_card' ? 'border-teal-500 bg-teal-50' : '' }}"
                    wire:click="$set('payment_method', 'credit_card')">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="credit_card" class="ml-3 w-5 h-5">
                        <span class="font-semibold">بطاقة ائتمان</span>
                    </label>
                </div>
                <div class="border rounded-lg p-4 cursor-pointer {{ $payment_method == 'cod' ? 'border-teal-500 bg-teal-50' : '' }}"
                    wire:click="$set('payment_method', 'cod')">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" wire:model="payment_method" value="cod" class="ml-3 w-5 h-5">
                        <span class="font-semibold">دفع عند الاستلام</span>
                    </label>
                </div>
            </div>
        @endif

        {{-- STEP 5 --}}
        @if($currentStep == 5)
            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">الخطوة 5: التأكيد</h2>
            <div class="bg-gray-50 rounded-lg p-6 mb-6 space-y-3">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">المسار:</span>
                    <span class="font-semibold">{{ $origin_city }} → {{ $destination_city }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-gray-600">التاريخ:</span>
                    <span class="font-semibold">{{ $pickup_date }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">الوزن:</span>
                    <span class="font-semibold">{{ $weight_kg }} كجم</span>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" wire:model="terms_accepted" class="ml-3 mt-1 w-5 h-5">
                    <span class="font-semibold">أوافق على الشروط والأحكام *</span>
                </label>
                @error('terms_accepted') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
            </div>
        @endif

        {{-- NAVIGATION --}}
        <div class="flex justify-between items-center mt-8 pt-6 border-t">
            @if($currentStep > 1)
                <button type="button" wire:click="previousStep"
                    class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300">
                    <i class="fas fa-arrow-right ml-2"></i> السابق
                </button>
            @else
                <div></div>
            @endif

            @if($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep"
                    class="px-8 py-3 bg-teal-500 text-white rounded-lg font-semibold hover:bg-teal-600">
                    التالي <i class="fas fa-arrow-left mr-2"></i>
                </button>
            @else
                <button type="button" wire:click="submitBooking"
                    class="px-12 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg font-bold hover:shadow-xl">
                    تأكيد الحجز <i class="fas fa-check mr-2"></i>
                </button>
            @endif
        </div>
    </div>
</div>
