<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white pb-12" style="font-family: 'Cairo', 'Tajawal', sans-serif;">
    
    {{-- Page Header --}}
    <div class="bg-gradient-to-l from-[#0F2E5D] to-[#1a4d8f] text-white py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">
                <i class="fas fa-clipboard-check ml-2"></i>
                حجز حاوية
            </h1>
            <p class="text-blue-100 mt-2">أكمل البيانات التالية لإتمام حجزك</p>
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
                                    {{ ['بيانات الشحنة', 'اختيار الحاوية', 'المواعيد', 'المستندات', 'المراجعة', 'التأكيد'][$i-1] }}
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
                                بيانات الشحنة
                            </h2>

                            <div class="space-y-6">
                                {{-- Shipper Information --}}
                                <div class="border-b border-gray-200 pb-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-user-tie ml-2 text-teal-600"></i>
                                        بيانات الشاحن (Shipper)
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">الاسم الكامل *</label>
                                            <input type="text" wire:model.defer="shipper_name" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">اسم الشركة *</label>
                                            <input type="text" wire:model.defer="shipper_company" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">العنوان الكامل *</label>
                                            <textarea wire:model.defer="shipper_address" rows="2"
                                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200"></textarea>
                                            @error('shipper_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">رقم الهاتف *</label>
                                            <input type="tel" wire:model.defer="shipper_phone" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('shipper_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">البريد الإلكتروني *</label>
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
                                        بيانات المرسل إليه (Consignee)
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">الاسم الكامل *</label>
                                            <input type="text" wire:model.defer="consignee_name" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">اسم الشركة *</label>
                                            <input type="text" wire:model.defer="consignee_company" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_company') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">العنوان الكامل *</label>
                                            <textarea wire:model.defer="consignee_address" rows="2"
                                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200"></textarea>
                                            @error('consignee_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">رقم الهاتف *</label>
                                            <input type="tel" wire:model.defer="consignee_phone" 
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            @error('consignee_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">البريد الإلكتروني *</label>
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
                                        معلومات البضاعة
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">وصف البضاعة *</label>
                                            <textarea wire:model.defer="cargo_description" rows="3"
                                                      placeholder="مثال: أجهزة إلكترونية، ملابس، مواد غذائية..."
                                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200"></textarea>
                                            @error('cargo_description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">الرمز الجمركي (HS Code)</label>
                                            <input type="text" wire:model.defer="hs_code" placeholder="مثال: 8517.12.00"
                                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold mb-2 text-gray-700">القيمة (USD) *</label>
                                            <input type="number" wire:model.defer="cargo_value" placeholder="مثال: 50000"
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
                                اختيار الحاوية
                            </h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Container Type --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-3 text-gray-700">نوع الحاوية *</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach(['20GP' => '20 قدم قياسية', '40GP' => '40 قدم قياسية', '40HQ' => '40 قدم عالية', 'Reefer' => 'مبردة'] as $type => $label)
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
                                        <label class="block text-sm font-semibold mb-3 text-gray-700">عدد الحاويات *</label>
                                        <input type="number" wire:model="container_quantity" min="1" max="10"
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 text-2xl font-bold text-center">
                                        <p class="text-xs text-gray-500 mt-2">يمكنك حجز من 1 إلى 10 حاويات</p>
                                        @error('container_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Container Ownership --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">ملكية الحاوية *</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $container_ownership === 'carrier' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                            <input type="radio" wire:model="container_ownership" value="carrier" class="mt-1 ml-3">
                                            <div>
                                                <div class="font-bold text-gray-800">حاوية الناقل</div>
                                                <div class="text-sm text-gray-600">الشركة توفر الحاوية (الخيار الأكثر شيوعاً)</div>
                                            </div>
                                        </label>
                                        <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $container_ownership === 'shipper_owned' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                            <input type="radio" wire:model="container_ownership" value="shipper_owned" class="mt-1 ml-3">
                                            <div>
                                                <div class="font-bold text-gray-800">حاوية خاصة (SOC)</div>
                                                <div class="text-sm text-gray-600">استخدام حاوية الشاحن الخاصة</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Special Requirements --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">متطلبات خاصة (اختياري)</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach(['ventilation' => 'تهوية', 'temperature_controlled' => 'تحكم حرارة', 'shock_resistant' => 'مقاومة صدمات', 'moisture_proof' => 'حماية رطوبة', 'fragile' => 'قابل للكسر', 'stackable' => 'قابل للتكديس'] as $req => $label)
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
                                تحديد المواعيد
                            </h2>

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- Preferred Loading Date --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 text-gray-700">تاريخ التحميل المفضل *</label>
                                        <input type="date" wire:model="preferred_loading_date"
                                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200 text-lg">
                                        @error('preferred_loading_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Time Window --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-2 text-gray-700">النافذة الزمنية المفضلة *</label>
                                        <select wire:model="time_window"
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                            <option value="morning">صباحاً (8 ص - 12 ظ)</option>
                                            <option value="afternoon">ظهراً (12 ظ - 4 م)</option>
                                            <option value="evening">مساءً (4 م - 8 م)</option>
                                        </select>
                                        @error('time_window') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Cutoff Date Warning --}}
                                <div class="bg-orange-50 border-r-4 border-orange-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-orange-600 ml-3 mt-1"></i>
                                        <div>
                                            <h4 class="font-bold text-orange-800 mb-1">موعد القطع (Cut-off Date)</h4>
                                            <p class="text-sm text-orange-700">
                                                يجب تسليم الحاوية قبل <strong>{{ now()->parse($selectedQuote['cutoff_date'] ?? now()->addDays(14))->format('Y-m-d') }}</strong> 
                                                للتأكد من الشحن في الموعد المحدد.
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
                                            <span class="font-bold text-gray-800">أوافق على موعد القطع *</span>
                                            <p class="text-gray-600 mt-1">أتعهد بتسليم الحاوية قبل الموعد النهائي المحدد</p>
                                        </div>
                                    </label>
                                    @error('cutoff_acknowledgement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50
                                        {{ $flexible_dates ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:model="flexible_dates" class="mt-1 ml-3">
                                        <div class="text-sm">
                                            <span class="font-bold text-gray-800">لدي مرونة في التواريخ</span>
                                            <p class="text-gray-600 mt-1">يمكنني تعديل التاريخ بـ ±3 أيام إذا لزم الأمر</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        @endif

                        {{-- Step 4: المستندات والتأمين --}}
                        @if($currentStep === 4)
                            <h2 class="text-2xl font-bold text-[#0F2E5D] mb-6">
                                <i class="fas fa-file-upload ml-2"></i>
                                المستندات والتأمين
                            </h2>

                            <div class="space-y-6">
                                {{-- Document Upload Section --}}
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-folder-open ml-2 text-teal-600"></i>
                                        رفع المستندات المطلوبة
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        {{-- Invoice --}}
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-teal-500 transition">
                                            <label class="block cursor-pointer">
                                                <div class="text-center">
                                                    <i class="fas fa-file-invoice text-4xl text-gray-400 mb-3"></i>
                                                    <h4 class="font-bold text-gray-800 mb-1">فاتورة تجارية (Commercial Invoice)</h4>
                                                    <p class="text-sm text-gray-600 mb-3">اسحب الملف هنا أو اضغط للتحميل — PDF/JPG حتى 20MB</p>
                                                    <input type="file" wire:model="invoice_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                                    @if($uploaded_invoice_path)
                                                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                                                            <i class="fas fa-check-circle ml-1"></i> تم الرفع بنجاح
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                                                            اضغط للاختيار
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="invoice_file" class="text-center mt-3">
                                                <i class="fas fa-spinner fa-spin text-teal-600"></i>
                                                <span class="text-teal-600 mr-2">جاري الرفع...</span>
                                            </div>
                                            @error('invoice_file') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Packing List --}}
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-teal-500 transition">
                                            <label class="block cursor-pointer">
                                                <div class="text-center">
                                                    <i class="fas fa-list-alt text-4xl text-gray-400 mb-3"></i>
                                                    <h4 class="font-bold text-gray-800 mb-1">قائمة التعبئة (Packing List)</h4>
                                                    <p class="text-sm text-gray-600 mb-3">اسحب الملف هنا أو اضغط للتحميل — PDF/JPG حتى 20MB</p>
                                                    <input type="file" wire:model="packing_list_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                                    @if($uploaded_packing_path)
                                                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                                                            <i class="fas fa-check-circle ml-1"></i> تم الرفع بنجاح
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                                                            اضغط للاختيار
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="packing_list_file" class="text-center mt-3">
                                                <i class="fas fa-spinner fa-spin text-teal-600"></i>
                                                <span class="text-teal-600 mr-2">جاري الرفع...</span>
                                            </div>
                                            @error('packing_list_file') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- Certificate of Origin --}}
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-teal-500 transition">
                                            <label class="block cursor-pointer">
                                                <div class="text-center">
                                                    <i class="fas fa-certificate text-4xl text-gray-400 mb-3"></i>
                                                    <h4 class="font-bold text-gray-800 mb-1">شهادة المنشأ (Certificate of Origin)</h4>
                                                    <p class="text-sm text-gray-600 mb-3">اسحب الملف هنا أو اضغط للتحميل — PDF/JPG حتى 20MB</p>
                                                    <input type="file" wire:model="certificate_of_origin_file" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                                    @if($uploaded_coo_path)
                                                        <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-semibold">
                                                            <i class="fas fa-check-circle ml-1"></i> تم الرفع بنجاح
                                                        </span>
                                                    @else
                                                        <span class="inline-block bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold">
                                                            اضغط للاختيار
                                                        </span>
                                                    @endif
                                                </div>
                                            </label>
                                            <div wire:loading wire:target="certificate_of_origin_file" class="text-center mt-3">
                                                <i class="fas fa-spinner fa-spin text-teal-600"></i>
                                                <span class="text-teal-600 mr-2">جاري الرفع...</span>
                                            </div>
                                            @error('certificate_of_origin_file') <span class="text-red-500 text-sm block mt-2">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Insurance Section --}}
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                                        <i class="fas fa-shield-alt ml-2 text-teal-600"></i>
                                        التأمين على الشحنة
                                    </h3>

                                    <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer mb-4
                                        {{ $insurance_required ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                        <input type="checkbox" wire:model="insurance_required" class="mt-1 ml-3">
                                        <div>
                                            <span class="font-bold text-gray-800">أرغب بتأمين الشحنة</span>
                                            <p class="text-sm text-gray-600 mt-1">حماية شحنتك ضد الأضرار والفقد</p>
                                        </div>
                                    </label>

                                    @if($insurance_required)
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-semibold mb-3 text-gray-700">نوع التأمين</label>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                    <label class="flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                                                        {{ $insurance_type === 'basic' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                        <input type="radio" wire:model="insurance_type" value="basic" class="mb-2">
                                                        <span class="font-bold text-gray-800">أساسي</span>
                                                        <span class="text-sm text-gray-600">2% من القيمة</span>
                                                        <span class="text-xs text-gray-500 mt-1">حماية أساسية</span>
                                                    </label>
                                                    <label class="flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                                                        {{ $insurance_type === 'comprehensive' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                        <input type="radio" wire:model="insurance_type" value="comprehensive" class="mb-2">
                                                        <span class="font-bold text-gray-800">شامل</span>
                                                        <span class="text-sm text-gray-600">5% من القيمة</span>
                                                        <span class="text-xs text-gray-500 mt-1">حماية كاملة</span>
                                                    </label>
                                                    <label class="flex flex-col p-4 border-2 rounded-lg cursor-pointer transition
                                                        {{ $insurance_type === 'custom' ? 'border-teal-600 bg-teal-50' : 'border-gray-300 hover:border-teal-400' }}">
                                                        <input type="radio" wire:model="insurance_type" value="custom" class="mb-2">
                                                        <span class="font-bold text-gray-800">مخصص</span>
                                                        <span class="text-sm text-gray-600">8% من القيمة</span>
                                                        <span class="text-xs text-gray-500 mt-1">تأمين مفصل</span>
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
                                مراجعة الحجز والدفع
                            </h2>

                            <div class="space-y-6">
                                {{-- Booking Summary --}}
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-bold text-gray-800 mb-4">ملخص الحجز</h3>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600">الشاحن:</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $shipper_name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">المرسل إليه:</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $consignee_name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">نوع الحاوية:</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $container_type }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">العدد:</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $container_quantity }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">تاريخ التحميل:</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $preferred_loading_date }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600">التأمين:</span>
                                            <span class="font-semibold text-gray-800 mr-2">{{ $insurance_required ? 'نعم' : 'لا' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Payment Method --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">وسيلة الدفع *</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $payment_method === 'bank_transfer' ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="payment_method" value="bank_transfer" class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-university text-2xl text-teal-600 ml-3"></i>
                                                <span class="font-semibold">تحويل بنكي</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $payment_method === 'credit_card' ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="payment_method" value="credit_card" class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-credit-card text-2xl text-teal-600 ml-3"></i>
                                                <span class="font-semibold">بطاقة ائتمان</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition
                                            {{ $payment_method === 'cash' ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                            <input type="radio" wire:model="payment_method" value="cash" class="ml-3">
                                            <div class="flex items-center">
                                                <i class="fas fa-money-bill-wave text-2xl text-teal-600 ml-3"></i>
                                                <span class="font-semibold">نقداً</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Payment Terms --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-3 text-gray-700">شروط الدفع *</label>
                                    <select wire:model="payment_terms"
                                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                        <option value="prepaid">مدفوع مسبقاً (Prepaid)</option>
                                        <option value="collect">الدفع عند الاستلام (Collect)</option>
                                        <option value="third_party">طرف ثالث (Third Party)</option>
                                    </select>
                                </div>

                                {{-- Promotional Code --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-gray-700">كود خصم (اختياري)</label>
                                    <div class="flex gap-2">
                                        <input type="text" wire:model.defer="promotional_code" placeholder="أدخل كود الخصم"
                                               class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-2 focus:ring-teal-200">
                                        <button type="button" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold">
                                            تطبيق
                                        </button>
                                    </div>
                                </div>

                                {{-- Terms Agreement --}}
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer
                                    {{ $agreed_to_terms ? 'border-teal-600 bg-teal-50' : 'border-gray-300' }}">
                                    <input type="checkbox" wire:model="agreed_to_terms" class="mt-1 ml-3">
                                    <div class="text-sm">
                                        <span class="font-bold text-gray-800">أوافق على الشروط والأحكام *</span>
                                        <p class="text-gray-600 mt-1">
                                            قرأت ووافقت على 
                                            <a href="#" class="text-teal-600 hover:underline">الشروط والأحكام</a>
                                            و
                                            <a href="#" class="text-teal-600 hover:underline">سياسة الخصوصية</a>
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
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">جاري إنشاء الحجز...</h3>
                                <p class="text-gray-600">يرجى الانتظار قليلاً</p>
                            </div>
                        @endif

                        {{-- Navigation Buttons --}}
                        @if($currentStep < 6 || ($currentStep === 6 && !$booking_confirmed))
                            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                @if($currentStep > 1)
                                    <button type="button" wire:click="previousStep"
                                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-8 py-3 rounded-lg transition">
                                        <i class="fas fa-arrow-right ml-2"></i>
                                        السابق
                                    </button>
                                @else
                                    <div></div>
                                @endif

                                @if($currentStep < 5)
                                    <button type="button" wire:click="nextStep"
                                            class="bg-gradient-to-l from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition">
                                        التالي
                                        <i class="fas fa-arrow-left mr-2"></i>
                                    </button>
                                @elseif($currentStep === 5)
                                    <button type="button" wire:click="submitBooking"
                                            wire:loading.attr="disabled"
                                            class="bg-gradient-to-l from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition">
                                        <span wire:loading.remove wire:target="submitBooking">
                                            <i class="fas fa-check-circle ml-2"></i>
                                            تأكيد الحجز
                                        </span>
                                        <span wire:loading wire:target="submitBooking">
                                            <i class="fas fa-spinner fa-spin ml-2"></i>
                                            جاري التأكيد...
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
                            <h2 class="text-3xl font-bold text-gray-800 mb-3">تم تأكيد الحجز بنجاح!</h2>
                            <p class="text-xl text-gray-600 mb-2">رقم المرجع الخاص بك:</p>
                            <div class="text-4xl font-bold text-teal-600 mb-6">{{ $booking_reference }}</div>
                            
                            <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-6 mb-6 max-w-2xl mx-auto">
                                <i class="fas fa-info-circle text-blue-600 text-2xl mb-3"></i>
                                <p class="text-blue-800">
                                    تم إرسال تأكيد الحجز إلى بريدك الإلكتروني ورسالة واتساب. 
                                    سيتم التواصل معك قريباً لتأكيد التفاصيل النهائية.
                                </p>
                            </div>

                            <div class="flex justify-center gap-4">
                                <button wire:click="downloadConfirmation"
                                        class="bg-gradient-to-l from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition">
                                    <i class="fas fa-download ml-2"></i>
                                    تحميل تأكيد الحجز
                                </button>
                                <a href="{{ route('front.home') }}"
                                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold px-8 py-3 rounded-lg transition inline-block">
                                    <i class="fas fa-home ml-2"></i>
                                    العودة للرئيسية
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
                        ملخص العرض
                    </h3>

                    @if($selectedQuote)
                        <div class="space-y-4">
                            <div class="text-center pb-4 border-b border-gray-200">
                                <img src="{{ $selectedQuote['logo'] ?? '' }}" alt="Company Logo" class="h-16 mx-auto mb-3">
                                <div class="font-bold text-gray-800">{{ $selectedQuote['company'] ?? 'N/A' }}</div>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">السعر الأساسي:</span>
                                    <span class="font-bold">${{ number_format($selectedQuote['total_price'] ?? 0, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">عدد الحاويات:</span>
                                    <span class="font-bold">× {{ $container_quantity }}</span>
                                </div>
                                @if($insurance_required)
                                    <div class="flex justify-between text-teal-600">
                                        <span>التأمين ({{ $insurance_type }}):</span>
                                        <span class="font-bold">
                                            +${{ number_format(($selectedQuote['total_price'] ?? 0) * match($insurance_type) { 'basic' => 0.02, 'comprehensive' => 0.05, 'custom' => 0.08, default => 0 }, 2) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex justify-between pt-3 border-t-2 border-gray-300 text-lg">
                                    <span class="font-bold text-gray-800">الإجمالي:</span>
                                    <span class="font-bold text-teal-600">${{ number_format($this->totalPrice, 2) }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3 text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">زمن الشحن:</span>
                                    <span class="font-semibold">{{ $selectedQuote['transit_days'] ?? 'N/A' }} يوم</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">موعد القطع:</span>
                                    <span class="font-semibold">{{ $selectedQuote['cutoff_date'] ?? 'N/A' }}</span>
                                </div>
                            </div>

                            @if($selectedQuote['is_door_to_door'] ?? false)
                                <div class="flex items-center justify-center bg-blue-50 text-blue-800 rounded-lg px-3 py-2 text-xs font-semibold">
                                    <i class="fas fa-truck ml-1"></i>
                                    Door-to-Door Service
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="bg-orange-50 rounded-lg p-4 text-xs text-orange-800">
                            <i class="fas fa-shield-alt ml-1"></i>
                            <strong>ضمان الأمان:</strong> جميع معلوماتك محمية ومشفرة
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
