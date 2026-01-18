<div class="min-h-screen bg-gray-50 dark:bg-gray-900" dir="rtl">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">حاسبة تكاليف الاستيراد</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">احسب تكاليف استيراد شحنتك بدقة</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="resetCalculator" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        إعادة تعيين
                    </button>
                    <button wire:click="$set('showSaveModal', true)" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        حفظ الحساب
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Calculator Section (2 cols) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Input Fields Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">معلومات الشحنة</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Purchase Price --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                سعر الشراء (USD) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="purchase_price" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('purchase_price') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Incoterm --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                شروط التسليم (Incoterm) <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="incoterm"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="EXW">EXW - Ex Works</option>
                                <option value="FOB">FOB - Free on Board</option>
                                <option value="CFR">CFR - Cost and Freight</option>
                                <option value="CIF">CIF - Cost, Insurance, Freight</option>
                                <option value="DAP">DAP - Delivered at Place</option>
                                <option value="DDP">DDP - Delivered Duty Paid</option>
                            </select>
                        </div>

                        {{-- Origin Country --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                بلد المنشأ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="origin_country"
                                   placeholder="مثال: China"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('origin_country') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Origin Port --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ميناء التحميل
                            </label>
                            <input type="text" wire:model="origin_port"
                                   placeholder="مثال: Shanghai Port"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        {{-- Destination Port --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ميناء الوصول <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="destination_port"
                                   placeholder="مثال: Jeddah Port"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('destination_port') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Container Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                نوع الحاوية <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="container_type"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="20ft">20 قدم</option>
                                <option value="40ft">40 قدم</option>
                                <option value="40ft_hc">40 قدم HC (High Cube)</option>
                            </select>
                        </div>

                        {{-- Gross Weight --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                الوزن الإجمالي (كجم) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="gross_weight_kg" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('gross_weight_kg') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- CBM --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                الحجم (CBM) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="cbm" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('cbm') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- HS Code --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                الرمز الجمركي (HS Code)
                            </label>
                            <input type="text" wire:model="hs_code"
                                   placeholder="مثال: 8471.30"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        {{-- Product Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                فئة المنتج
                            </label>
                            <input type="text" wire:model="product_category"
                                   placeholder="مثال: Electronics"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Cost Items Table --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">بنود التكاليف</h2>
                        <button wire:click="addItem" 
                                class="text-sm px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            إضافة بند
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">البند</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">المبلغ (USD)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($items as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <input type="text" wire:model="items.{{ $index }}.name"
                                                   class="w-full border-0 bg-transparent focus:ring-0 text-gray-900 dark:text-white">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" wire:model.live.debounce.500ms="items.{{ $index }}.amount" step="0.01"
                                                   class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item['editable'] ?? false)
                                                <button wire:click="removeItem({{ $index }})" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">المجموع الفرعي:</td>
                                    <td class="px-6 py-4 font-bold text-lg text-blue-600 dark:text-blue-400">
                                        ${{ number_format($subtotal, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">الإجمالي الكلي:</td>
                                    <td class="px-6 py-4 font-bold text-xl text-green-600 dark:text-green-400">
                                        ${{ number_format($grand_total, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Sidebar: KPIs & Actions --}}
            <div class="space-y-6">
                {{-- KPIs --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">مؤشرات الأداء</h3>
                    
                    <div class="space-y-4">
                        {{-- Duty Ratio --}}
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">نسبة الرسوم الجمركية</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $duty_ratio }}%</p>
                        </div>

                        {{-- Logistics Share --}}
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">نسبة تكاليف اللوجستيات</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $logistics_share }}%</p>
                        </div>

                        {{-- Lead Time --}}
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">زمن التسليم المتوقع</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $lead_time_days }} يوم</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإجراءات</h3>
                    
                    <div class="space-y-3">
                        <button wire:click="$set('showSaveModal', true)" 
                                class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            حفظ كسيناريو/فاتورة
                        </button>

                        <button wire:click="exportPdf" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            تصدير PDF
                        </button>

                        <button wire:click="exportExcel" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            تصدير Excel
                        </button>

                        <a href="{{ route('dashboard.import.index') }}" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            العودة للوحة الاستيراد
                        </a>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">ملاحظة</h4>
                            <p class="text-sm text-blue-800 dark:text-blue-400">
                                الحسابات تقديرية وقد تختلف حسب الظروف الفعلية. يُنصح بالتحقق من الأسعار مع شركات الشحن والجمارك.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Save Modal --}}
    @if($showSaveModal)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="$set('showSaveModal', false)">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">حفظ الحساب</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم الحساب</label>
                        <input type="text" wire:model="calculationName"
                               placeholder="مثال: استيراد من الصين - يناير 2025"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('calculationName') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الحساب</label>
                        <select wire:model="calculationType"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="scenario">سيناريو (Scenario)</option>
                            <option value="quote">عرض سعر (Quote)</option>
                            <option value="invoice">فاتورة (Invoice)</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="saveCalculation" 
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        حفظ
                    </button>
                    <button wire:click="$set('showSaveModal', false)" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-gray-900/50 items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-4 space-x-reverse">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">جاري المعالجة...</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listen for alerts
    window.addEventListener('alert', event => {
        const type = event.detail.type || 'info';
        const message = event.detail.message || '';
        
        // You can integrate with your toast notification library here
        console.log(`[${type.toUpperCase()}] ${message}`);
        alert(message);
    });
</script>
@endpush
