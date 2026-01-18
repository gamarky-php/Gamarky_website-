<div class="container mx-auto px-4 py-8" dir="rtl">
    {{-- SEARCH PANEL --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form wire:submit.prevent="searchQuotes" class="space-y-6">
            {{-- Row 1: Ports & Date --}}
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ميناء الشحن</label>
                    <input type="text" wire:model="origin_port" placeholder="مثلاً: Jeddah - KSA"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('origin_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ميناء الوصول</label>
                    <input type="text" wire:model="destination_port" placeholder="مثلاً: Shanghai - CN"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('destination_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الشحن</label>
                    <input type="date" wire:model="loading_date"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('loading_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Row 2: Weight, CBM, Cargo Type --}}
            <div class="grid md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الوزن (كجم)</label>
                    <input type="number" wire:model="weight_kg" placeholder="10000"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                    @error('weight_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الحجم (م³)</label>
                    <input type="number" step="0.01" wire:model="cbm" placeholder="28"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الشحنة</label>
                    <select wire:model="cargo_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        <option value="general">بضائع عامة</option>
                        <option value="hazmat">مواد خطرة</option>
                        <option value="perishable">قابلة للتلف</option>
                        <option value="fragile">هشة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الحاوية</label>
                    <select wire:model="container_type" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-teal-500 text-right">
                        <option value="20GP">20 قدم عادي</option>
                        <option value="40GP">40 قدم عادي</option>
                        <option value="40HC">40 قدم عالي</option>
                        <option value="20RF">20 قدم مبرد</option>
                        <option value="40RF">40 قدم مبرد</option>
                    </select>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-center">
                <button type="submit" wire:loading.attr="disabled"
                    class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-12 py-4 rounded-full font-bold text-lg hover:shadow-xl transition-all disabled:opacity-50">
                    <i class="fas fa-search ml-2"></i>
                    <span wire:loading.remove>ابحث عن أسعار</span>
                    <span wire:loading>جاري البحث...</span>
                </button>
            </div>
        </form>
    </div>

    {{-- RESULTS SECTION --}}
    @if($searchPerformed)
        @if(count($quotes) > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quotes as $quote)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-[#0F2E5D] to-blue-700 text-white p-5">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-bold">{{ $quote['provider_name'] }}</h3>
                                <span class="bg-yellow-400 text-gray-900 px-3 py-1 rounded-full text-xs font-bold">
                                    ⭐ {{ $quote['reputation_score'] }}
                                </span>
                            </div>
                            <p class="text-sm opacity-90">{{ $quote['service_level'] }}</p>
                        </div>

                        {{-- Body --}}
                        <div class="p-5 space-y-3">
                            <div class="flex items-center justify-between border-b pb-3">
                                <span class="text-gray-600">السعر:</span>
                                <span class="text-2xl font-bold text-teal-600">${{ number_format($quote['price_usd']) }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">مدة الشحن:</span>
                                <span class="font-semibold">{{ $quote['transit_days'] }} يوم</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">صالح حتى:</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($quote['valid_until'])->format('Y-m-d') }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">التقييم:</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">
                                    نقاط: {{ $quote['score'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="px-5 pb-5">
                            <a href="{{ route('front.shipping.book') }}" 
                                class="block text-center bg-teal-500 text-white py-3 rounded-lg font-bold hover:bg-teal-600 transition-colors">
                                احجز الآن
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border-r-4 border-yellow-400 p-6 rounded-lg text-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-4xl mb-3"></i>
                <p class="text-gray-700 text-lg">عذراً، لم نجد عروض متاحة بهذه المعايير</p>
            </div>
        @endif
    @endif
</div>
