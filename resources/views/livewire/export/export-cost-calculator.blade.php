{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('حاسبة تكاليف التصدير') }}</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('احسب سعر البيع المثالي مع هامش الربح') }}</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="resetCalculator" 
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{ __('إعادة تعيين') }}
                    </button>
                    <button wire:click="$set('showSaveModal', true)" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        {{ __('حفظ الحساب') }}
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
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">{{ __('معلومات المنتج والشحنة') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Production Cost --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('تكلفة الإنتاج للوحدة (USD)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="production_cost" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('production_cost') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Quantity --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('الكمية (وحدات)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="quantity" step="1" min="1"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('quantity') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Incoterm --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('شروط التسليم (Incoterm)') }} <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="incoterm"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="EXW">EXW - Ex Works</option>
                                <option value="FCA">FCA - Free Carrier</option>
                                <option value="FAS">FAS - Free Alongside Ship</option>
                                <option value="FOB">FOB - Free on Board</option>
                                <option value="CFR">CFR - Cost and Freight</option>
                                <option value="CIF">CIF - Cost, Insurance, Freight</option>
                                <option value="CPT">CPT - Carriage Paid To</option>
                                <option value="CIP">CIP - Carriage, Insurance Paid</option>
                                <option value="DAP">DAP - Delivered at Place</option>
                                <option value="DPU">DPU - Delivered at Place Unloaded</option>
                                <option value="DDP">DDP - Delivered Duty Paid</option>
                            </select>
                        </div>

                        {{-- Destination Country --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('البلد المستهدف') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="destination_country"
                                   placeholder="{{ __('مثال: UAE') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('destination_country') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Destination Port --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('ميناء الوصول') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model.blur="destination_port"
                                   placeholder="{{ __('مثال: Dubai Port') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('destination_port') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Origin Port --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('ميناء التحميل') }}
                            </label>
                            <input type="text" wire:model="origin_port"
                                   placeholder="{{ __('مثال: Jeddah Port') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        {{-- Container Type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('نوع الحاوية') }} <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="container_type"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="20ft">{{ __('20 قدم') }}</option>
                                <option value="40ft">{{ __('40 قدم') }}</option>
                                <option value="40ft_hc">{{ __('40 قدم HC (High Cube)') }}</option>
                            </select>
                        </div>

                        {{-- Gross Weight --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('الوزن الإجمالي (كجم)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="gross_weight_kg" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('gross_weight_kg') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- CBM --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('الحجم (CBM)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="cbm" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('cbm') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Target Margin % --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('نسبة الهامش المستهدف (%)') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model.live.debounce.500ms="target_margin_percent" step="0.1" min="0" max="100"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            @error('target_margin_percent') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- HS Code --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('الرمز الجمركي (HS Code)') }}
                            </label>
                            <input type="text" wire:model="hs_code"
                                   placeholder="{{ __('مثال: 8471.30') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        {{-- Product Description --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('وصف المنتج') }}
                            </label>
                            <input type="text" wire:model="product_description"
                                   placeholder="{{ __('مثال: Electronic Components') }}"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>
                </div>

                {{-- Cost Items Table --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('بنود التكاليف') }}</h2>
                        <button wire:click="addItem" 
                                class="text-sm px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('إضافة بند') }}
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('البند') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('المبلغ (USD)') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('إجراءات') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($items as $index => $item)
                                    <tr class="{{ $item['auto'] ?? false ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <input type="text" wire:model="items.{{ $index }}.name"
                                                       class="flex-1 border-0 bg-transparent focus:ring-0 text-gray-900 dark:text-white">
                                                @if($item['auto'] ?? false)
                                                    <span class="text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-1 rounded">{{ __('آلي') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" wire:model.live.debounce.500ms="items.{{ $index }}.amount" step="0.01"
                                                   @if(!($item['editable'] ?? true)) readonly @endif
                                                   class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500 @if(!($item['editable'] ?? true)) bg-gray-50 dark:bg-gray-900 @endif">
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
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">{{ __('إجمالي التكلفة:') }}</td>
                                    <td class="px-6 py-4 font-bold text-lg text-blue-600 dark:text-blue-400">
                                        ${{ number_format($total_cost, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-right font-semibold text-gray-900 dark:text-white">
                                        {{ __('الهامش') }} ({{ $target_margin_percent }}%):
                                    </td>
                                    <td class="px-6 py-4 font-bold text-lg text-purple-600 dark:text-purple-400">
                                        ${{ number_format($margin_amount, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="border-t-2 border-green-500">
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">{{ __('سعر البيع المقترح:') }}</td>
                                    <td class="px-6 py-4 font-bold text-2xl text-green-600 dark:text-green-400">
                                        ${{ number_format($selling_price, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-right text-sm text-gray-600 dark:text-gray-400">{{ __('سعر الوحدة:') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                        ${{ number_format($unit_selling_price, 2) }} / {{ __('وحدة') }}
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
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('مؤشرات الأداء') }}</h3>
                    
                    <div class="space-y-4">
                        {{-- Margin Ratio --}}
                        <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('نسبة الهامش الفعلية') }}</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $margin_ratio }}%</p>
                        </div>

                        {{-- Profit per Unit --}}
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('ربح الوحدة') }}</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($profit_per_unit, 2) }}</p>
                        </div>

                        {{-- Export Readiness Score --}}
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">{{ __('درجة جاهزية التصدير') }}</p>
                            <div class="flex items-center gap-3">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $export_readiness_score }}</p>
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 dark:bg-blue-400 h-2 rounded-full transition-all" 
                                         style="width: {{ $export_readiness_score }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('الإجراءات') }}</h3>
                    
                    <div class="space-y-3">
                        <button wire:click="$set('showSaveModal', true)" 
                                class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            {{ __('حفظ كسيناريو/عرض سعر') }}
                        </button>

                        <button wire:click="exportPdf" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            {{ __('تصدير PDF') }}
                        </button>

                        <button wire:click="exportExcel" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('تصدير Excel') }}
                        </button>

                        <a href="{{ route('dashboard.export.index') }}" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __('العودة للوحة التصدير') }}
                        </a>
                    </div>
                </div>

                {{-- Pricing Breakdown --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
                    <h4 class="font-semibold text-green-900 dark:text-green-300 mb-3">{{ __('تفصيل الأسعار') }}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-300">{{ __('إجمالي التكلفة:') }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($total_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-700 dark:text-gray-300">{{ __('الهامش') }} ({{ $target_margin_percent }}%):</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">+${{ number_format($margin_amount, 2) }}</span>
                        </div>
                        <div class="border-t border-green-300 dark:border-green-600 pt-2 mt-2 flex justify-between">
                            <span class="font-bold text-gray-900 dark:text-white">{{ __('سعر البيع:') }}</span>
                            <span class="font-bold text-green-600 dark:text-green-400">${{ number_format($selling_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('للوحدة') }} ({{ $quantity }} {{ __('وحدة') }}):</span>
                            <span class="text-gray-900 dark:text-white">${{ number_format($unit_selling_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Info Card --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">{{ __('نصيحة') }}</h4>
                            <p class="text-sm text-blue-800 dark:text-blue-400">
                                {{ __('تأكد من مراجعة أسعار السوق المستهدف وحساب التكاليف الإضافية مثل الرسوم الجمركية في بلد الوجهة.') }}
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
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('حفظ الحساب') }}</h3>
                
                <div class="space-y-4">
                    <div>
                           <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('اسم الحساب') }}</label>
                        <input type="text" wire:model="calculationName"
                               placeholder="{{ __('مثال: تصدير إلى الإمارات - يناير 2025') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('calculationName') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('نوع الحساب') }}</label>
                        <select wire:model="calculationType"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="scenario">{{ __('سيناريو (Scenario)') }}</option>
                            <option value="quote">{{ __('عرض سعر (Quote)') }}</option>
                            <option value="invoice">{{ __('فاتورة (Invoice)') }}</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="saveCalculation" 
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        {{ __('حفظ') }}
                    </button>
                    <button wire:click="$set('showSaveModal', false)" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                        {{ __('إلغاء') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-gray-900/50 items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-4 space-x-reverse">
                <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">{{ __('جاري المعالجة...') }}</span>
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
        
        console.log(`[${type.toUpperCase()}] ${message}`);
        alert(message);
    });
</script>
@endpush
