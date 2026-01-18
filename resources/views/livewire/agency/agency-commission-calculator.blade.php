<div dir="rtl" class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold">📊 حاسبة عمولات الوكالة</h2>
        <p class="text-indigo-100 mt-2">احسب رسوم التأسيس والعمولات ومواد التسويق لوكلاء الشحن والعلامات التجارية</p>
    </div>

    {{-- Agency Type Selector --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">نوع الوكالة</h3>
        <div class="flex gap-4">
            <button
                wire:click="$set('agencyType', 'shipping')"
                class="flex-1 px-6 py-4 rounded-lg border-2 transition-all duration-150 {{ $agencyType === 'shipping' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="font-semibold text-lg">وكيل شحن</span>
                </div>
            </button>

            <button
                wire:click="$set('agencyType', 'brand')"
                class="flex-1 px-6 py-4 rounded-lg border-2 transition-all duration-150 {{ $agencyType === 'brand' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="font-semibold text-lg">وكيل علامة تجارية</span>
                </div>
            </button>
        </div>
    </div>

    {{-- Shipping Agent Calculator --}}
    @if($agencyType === 'shipping')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Inputs --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    البيانات الأساسية
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">قيمة الشحنة ($)</label>
                        <input type="number" wire:model.live="shipment_value" min="0" step="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نسبة العمولة (%)</label>
                        <input type="number" wire:model.live="commission_percentage" min="0" max="100" step="0.5"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رسوم التأسيس ($)</label>
                        <input type="number" wire:model.live="setup_fee" min="0" step="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للعمولة ($)</label>
                        <input type="number" wire:model.live="minimum_commission" min="0" step="50"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">عدد الشحنات</label>
                        <input type="number" wire:model.live="number_of_shipments" min="1" step="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="marketing_materials"
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">مواد تسويقية</span>
                        </label>
                        
                        @if($marketing_materials)
                            <div class="mt-3 mr-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2">تكلفة المواد التسويقية ($)</label>
                                <input type="number" wire:model.live="marketing_cost" min="0" step="50"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        @endif
                    </div>
                </div>

                <button wire:click="resetCalculator" class="mt-6 w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">
                    إعادة تعيين
                </button>
            </div>

            {{-- Results --}}
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm font-medium text-indigo-100">إجمالي التكلفة</p>
                    <p class="text-4xl font-bold mt-2">${{ number_format($results['grand_total'] ?? 0, 2) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-base font-bold text-gray-900 mb-4">تفاصيل الحساب</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">رسوم التأسيس</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($results['setup_fee'] ?? 0, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">عمولة أساسية للشحنة</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($results['base_commission'] ?? 0, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">عمولة بعد الحد الأدنى</span>
                            <span class="text-sm font-semibold text-indigo-600">${{ number_format($results['commission_per_shipment'] ?? 0, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">إجمالي العمولات ({{ $number_of_shipments }} شحنة)</span>
                            <span class="text-sm font-semibold text-indigo-600">${{ number_format($results['total_commission'] ?? 0, 2) }}</span>
                        </div>

                        @if($marketing_materials)
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">مواد تسويقية</span>
                                <span class="text-sm font-semibold text-gray-900">${{ number_format($results['marketing_cost'] ?? 0, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">ملاحظة</p>
                            <p>يتم تطبيق الحد الأدنى للعمولة تلقائياً إذا كانت العمولة المحسوبة أقل من الحد المحدد.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Brand Agent Calculator --}}
    @if($agencyType === 'brand')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Inputs --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    البيانات الأساسية
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المبيعات السنوية المتوقعة ($)</label>
                        <input type="number" wire:model.live="brand_annual_sales" min="0" step="1000"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">نسبة العمولة (%)</label>
                        <input type="number" wire:model.live="brand_commission_tier" min="0" max="100" step="0.5"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رسوم التأسيس ($)</label>
                        <input type="number" wire:model.live="brand_setup_fee" min="0" step="100"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى السنوي ($)</label>
                        <input type="number" wire:model.live="brand_minimum_annual" min="0" step="500"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">باقة التسويق</label>
                        <select wire:model.live="brand_marketing_package" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            @foreach($marketingPackages as $key => $package)
                                <option value="{{ $key }}">{{ $package['name'] }} - ${{ number_format($package['cost']) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="territory_exclusivity"
                                   class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="text-sm font-medium text-gray-700">حصرية إقليمية</span>
                        </label>
                        
                        @if($territory_exclusivity)
                            <div class="mt-3 mr-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2">رسوم الحصرية ($)</label>
                                <input type="number" wire:model.live="exclusivity_fee" min="0" step="500"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        @endif
                    </div>
                </div>

                <button wire:click="resetCalculator" class="mt-6 w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">
                    إعادة تعيين
                </button>
            </div>

            {{-- Results --}}
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <p class="text-sm font-medium text-purple-100">إجمالي التكلفة السنوية</p>
                    <p class="text-4xl font-bold mt-2">${{ number_format($results['grand_total'] ?? 0, 2) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-base font-bold text-gray-900 mb-4">تفاصيل الحساب</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">رسوم التأسيس</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($results['setup_fee'] ?? 0, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">عمولة أساسية</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($results['base_commission'] ?? 0, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">العمولة النهائية (بعد الحد الأدنى)</span>
                            <span class="text-sm font-semibold text-purple-600">${{ number_format($results['final_commission'] ?? 0, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                            <span class="text-sm text-gray-600">باقة التسويق ({{ $results['marketing_package'] ?? '' }})</span>
                            <span class="text-sm font-semibold text-gray-900">${{ number_format($results['marketing_cost'] ?? 0, 2) }}</span>
                        </div>

                        @if($territory_exclusivity)
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">رسوم الحصرية الإقليمية</span>
                                <span class="text-sm font-semibold text-gray-900">${{ number_format($results['exclusivity_fee'] ?? 0, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg border border-purple-200 p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-purple-800">
                            <p class="font-semibold mb-1">ملاحظة</p>
                            <p>يتم تطبيق الحد الأدنى للعمولة السنوية تلقائياً. الحصرية الإقليمية توفر حقوق حصرية للوكيل في المنطقة المحددة.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
