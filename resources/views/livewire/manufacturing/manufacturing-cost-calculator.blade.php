<div class="min-h-screen bg-gray-50 dark:bg-gray-900" dir="rtl">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">حاسبة تكاليف التصنيع</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">احسب تكلفة الإنتاج وسعر البيع المثالي</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="resetCalculator" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        إعادة تعيين
                    </button>
                    <button wire:click="$set('showSaveModal', true)" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
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
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">معلومات المنتج</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Product Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                اسم المنتج <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="product_name"
                                   placeholder="مثال: مكونات إلكترونية"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('product_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Batch Size --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                حجم الدفعة (وحدات) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="batch_size" step="1" min="1"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('batch_size') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Target Margin % --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                نسبة الهامش المستهدف (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="target_margin_percent" step="0.1" min="0" max="100"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('target_margin_percent') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                {{-- Direct Costs Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">التكاليف المباشرة</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">مواد خام، عمالة مباشرة، مواد مساعدة</p>
                        </div>
                        <button wire:click="addItem('direct')" 
                                class="text-sm px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2">
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
                                    @if($item['category'] === 'direct')
                                        <tr class="hover:bg-blue-50/50 dark:hover:bg-blue-900/10">
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
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="bg-blue-50 dark:bg-blue-900/20">
                                <tr>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">إجمالي التكاليف المباشرة:</td>
                                    <td class="px-6 py-4 font-bold text-lg text-blue-600 dark:text-blue-400">
                                        ${{ number_format($total_direct_cost, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Indirect Costs Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">التكاليف غير المباشرة</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">طاقة، صيانة، استهلاك معدات، تكاليف إدارية</p>
                        </div>
                        <button wire:click="addItem('indirect')" 
                                class="text-sm px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center gap-2">
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
                                    @if($item['category'] === 'indirect')
                                        <tr class="hover:bg-purple-50/50 dark:hover:bg-purple-900/10">
                                            <td class="px-6 py-4">
                                                <input type="text" wire:model="items.{{ $index }}.name"
                                                       class="w-full border-0 bg-transparent focus:ring-0 text-gray-900 dark:text-white">
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" wire:model.live.debounce.500ms="items.{{ $index }}.amount" step="0.01"
                                                       class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-purple-500 focus:ring-purple-500">
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
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="bg-purple-50 dark:bg-purple-900/20">
                                <tr>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">إجمالي التكاليف غير المباشرة:</td>
                                    <td class="px-6 py-4 font-bold text-lg text-purple-600 dark:text-purple-400">
                                        ${{ number_format($total_indirect_cost, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Summary Section --}}
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl p-6 border border-indigo-200 dark:border-indigo-700">
                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300 mb-4">ملخص التكاليف</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center pb-3 border-b border-indigo-200 dark:border-indigo-700">
                            <span class="text-gray-700 dark:text-gray-300">التكاليف المباشرة:</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400">${{ number_format($total_direct_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-indigo-200 dark:border-indigo-700">
                            <span class="text-gray-700 dark:text-gray-300">التكاليف غير المباشرة:</span>
                            <span class="font-semibold text-purple-600 dark:text-purple-400">${{ number_format($total_indirect_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b-2 border-indigo-300 dark:border-indigo-600">
                            <span class="font-bold text-gray-900 dark:text-white">إجمالي تكلفة الإنتاج:</span>
                            <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400">${{ number_format($total_production_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 dark:text-gray-300">الهامش ({{ $target_margin_percent }}%):</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">+${{ number_format($margin_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t-2 border-green-500">
                            <span class="font-bold text-lg text-gray-900 dark:text-white">سعر البيع المقترح:</span>
                            <span class="font-bold text-2xl text-green-600 dark:text-green-400">${{ number_format($selling_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm bg-white/50 dark:bg-gray-800/50 p-3 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">تكلفة الوحدة ({{ $batch_size }} وحدة):</span>
                            <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($cost_per_unit, 2) }} / وحدة</span>
                        </div>
                        <div class="flex justify-between items-center text-sm bg-white/50 dark:bg-gray-800/50 p-3 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">سعر الوحدة للبيع:</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">${{ number_format($unit_price, 2) }} / وحدة</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar: KPIs & Actions --}}
            <div class="space-y-6">
                {{-- KPIs --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">مؤشرات الأداء</h3>
                    
                    <div class="space-y-4">
                        {{-- Direct Cost Ratio --}}
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">نسبة التكاليف المباشرة</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $direct_cost_ratio }}%</p>
                        </div>

                        {{-- Indirect Cost Ratio --}}
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">نسبة التكاليف غير المباشرة</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $indirect_cost_ratio }}%</p>
                        </div>

                        {{-- Margin Ratio --}}
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">نسبة الهامش الفعلية</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $margin_ratio }}%</p>
                        </div>

                        {{-- Breakeven Units --}}
                        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">نقطة التعادل (وحدات)</p>
                            <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($breakeven_units) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">الإجراءات</h3>
                    
                    <div class="space-y-3">
                        <button wire:click="$set('showSaveModal', true)" 
                                class="w-full px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            حفظ الحساب
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

                        <a href="{{ route('dashboard.manufacturing.index') }}" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            العودة للوحة التصنيع
                        </a>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl p-6 border border-indigo-200 dark:border-indigo-700">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-indigo-900 dark:text-indigo-300 mb-2">نصيحة</h4>
                            <p class="text-sm text-indigo-800 dark:text-indigo-400">
                                نقطة التعادل تساعدك في تحديد الحد الأدنى من الوحدات التي يجب إنتاجها لتغطية التكاليف الثابتة.
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
                               placeholder="مثال: تكلفة إنتاج منتج X - يناير 2025"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('calculationName') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الحساب</label>
                        <select wire:model="calculationType"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="scenario">سيناريو (Scenario)</option>
                            <option value="quote">عرض سعر (Quote)</option>
                            <option value="invoice">فاتورة (Invoice)</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="saveCalculation" 
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
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
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
    window.addEventListener('alert', event => {
        const type = event.detail.type || 'info';
        const message = event.detail.message || '';
        console.log(`[${type.toUpperCase()}] ${message}`);
        alert(message);
    });
</script>
@endpush
