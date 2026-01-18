<div class="min-h-screen bg-gray-50 dark:bg-gray-900" dir="rtl">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">بورصة عروض الحاويات</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">تصفح أفضل الأسعار من مختلف شركات الشحن</p>
                </div>
                <button wire:click="$set('showRequestModal', true)" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                    طلب عرض جديد
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي العروض</p>
                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">{{ $stats['total_quotes'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">متوسط السعر (USD)</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">${{ number_format($stats['avg_price']) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">عدد الناقلين</p>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $stats['carriers'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">تنتهي اليوم</p>
                <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">{{ $stats['expiring_today'] }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ميناء المغادرة</label>
                    <input type="text" wire:model.live.debounce.500ms="originFilter"
                           placeholder="مثال: Jeddah"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ميناء الوصول</label>
                    <input type="text" wire:model.live.debounce.500ms="destinationFilter"
                           placeholder="مثال: Shanghai"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الحاوية</label>
                    <select wire:model.live="containerTypeFilter"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">الكل</option>
                        @foreach($containerTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الناقل</label>
                    <input type="text" wire:model.live.debounce.500ms="carrierFilter"
                           placeholder="اسم الشركة..."
                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        السعر الأقصى (${{ number_format($maxPrice) }})
                    </label>
                    <input type="range" wire:model.live="maxPrice" min="500" max="20000" step="100"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                </div>
            </div>

            <div class="flex items-center gap-4 mt-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model.live="showExpired"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="text-sm text-gray-700 dark:text-gray-300">عرض العروض المنتهية</span>
                </label>
            </div>
        </div>

        {{-- Quotes Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @forelse($quotes as $quote)
                @php
                    $isExpiring = \Carbon\Carbon::parse($quote->valid_until)->diffInHours(now()) < 24;
                    $isExpired = \Carbon\Carbon::parse($quote->valid_until)->isPast();
                    $breakdown = json_decode($quote->breakdown ?? '{}', true);
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow {{ $isExpiring && !$isExpired ? 'ring-2 ring-orange-500' : '' }}">
                    <div class="p-6">
                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($quote->carrier)
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $quote->carrier }}</h3>
                                    @else
                                        <h3 class="text-lg font-bold text-gray-500 dark:text-gray-400">ناقل غير محدد</h3>
                                    @endif
                                    @if($isExpiring && !$isExpired)
                                        <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-xs text-orange-700 dark:text-orange-400 rounded-full">
                                            تنتهي قريباً
                                        </span>
                                    @endif
                                    @if($isExpired)
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-xs text-red-700 dark:text-red-400 rounded-full">
                                            منتهي
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">رقم المرجع: {{ $quote->request_ref }}</p>
                            </div>
                            <div class="text-left">
                                <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($quote->price) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">{{ $quote->currency }}</p>
                            </div>
                        </div>

                        {{-- Route --}}
                        <div class="flex items-center gap-3 mb-4 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-gray-500 mb-1">من</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $quote->origin_port }}</p>
                            </div>
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <div class="flex-1 text-left">
                                <p class="text-xs text-gray-500 dark:text-gray-500 mb-1">إلى</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $quote->destination_port }}</p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="grid grid-cols-3 gap-4 mb-4 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-500">نوع الحاوية</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $containerTypes[$quote->container_type] ?? $quote->container_type }}</p>
                            </div>
                            @if($quote->transit_days)
                                <div>
                                    <p class="text-gray-500 dark:text-gray-500">مدة النقل</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $quote->transit_days }} يوم</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-gray-500 dark:text-gray-500">صالح حتى</p>
                                <p class="font-medium {{ $isExpiring ? 'text-orange-600 dark:text-orange-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ \Carbon\Carbon::parse($quote->valid_until)->format('Y-m-d') }}
                                </p>
                            </div>
                        </div>

                        {{-- Breakdown --}}
                        @if(!empty($breakdown))
                            <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-xs font-medium text-blue-900 dark:text-blue-300 mb-2">تفصيل التكاليف:</p>
                                <div class="space-y-1 text-xs text-blue-800 dark:text-blue-400">
                                    @foreach($breakdown as $item => $amount)
                                        <div class="flex justify-between">
                                            <span>{{ $item }}</span>
                                            <span>${{ number_format($amount) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-3">
                            <button wire:click="viewQuoteDetails({{ $quote->id }})" 
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg text-sm">
                                التفاصيل
                            </button>
                            @if(!$isExpired && $quote->status === 'active')
                                <button wire:click="acceptQuote({{ $quote->id }})" 
                                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm">
                                    قبول وحجز
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center border border-gray-200 dark:border-gray-700">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">لا توجد عروض متاحة</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">جرب تعديل معايير البحث أو طلب عرض جديد</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $quotes->links() }}
        </div>
    </div>

    {{-- Request Quote Modal --}}
    @if($showRequestModal)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="$set('showRequestModal', false)">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full mx-4 p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">طلب عرض سعر جديد</h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ميناء المغادرة *</label>
                            <input type="text" wire:model="request_origin"
                                   placeholder="مثال: Jeddah, Saudi Arabia"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('request_origin') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ميناء الوصول *</label>
                            <input type="text" wire:model="request_destination"
                                   placeholder="مثال: Shanghai, China"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('request_destination') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الحاوية *</label>
                        <select wire:model="request_container_type"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($containerTypes as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('request_container_type') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ملاحظات إضافية</label>
                        <textarea wire:model="request_notes" rows="3"
                                  placeholder="أي متطلبات خاصة أو معلومات إضافية..."
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('request_notes') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="requestQuote" 
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                        إرسال الطلب
                    </button>
                    <button wire:click="$set('showRequestModal', false)" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Quote Details Modal --}}
    @if($showQuoteDetails && $selectedQuote)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="$set('showQuoteDetails', false)">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-3xl w-full mx-4 p-6">
                <div class="flex justify-between items-start mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">تفاصيل العرض</h3>
                    <button wire:click="$set('showQuoteDetails', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-500">رقم المرجع</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedQuote->request_ref }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-500">الناقل</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $selectedQuote->carrier ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-500">السعر الإجمالي</p>
                            <p class="font-medium text-indigo-600 dark:text-indigo-400">${{ number_format($selectedQuote->price) }} {{ $selectedQuote->currency }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-500">صالح حتى</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedQuote->valid_until)->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    @if($selectedQuote->inclusions)
                        @php $inclusions = json_decode($selectedQuote->inclusions, true) ?? []; @endphp
                        @if(!empty($inclusions))
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">المشمول في السعر:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($inclusions as $item)
                                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs">
                                            {{ $item }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($selectedQuote->exclusions)
                        @php $exclusions = json_decode($selectedQuote->exclusions, true) ?? []; @endphp
                        @if(!empty($exclusions))
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">غير مشمول:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($exclusions as $item)
                                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full text-xs">
                                            {{ $item }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($selectedQuote->notes)
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ملاحظات:</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">{{ $selectedQuote->notes }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="acceptQuote({{ $selectedQuote->id }})" 
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                        قبول وحجز
                    </button>
                    <button wire:click="declineQuote({{ $selectedQuote->id }})" 
                            class="px-4 py-2 border border-red-300 dark:border-red-600 text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                        رفض
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
        alert(event.detail.message);
    });
</script>
@endpush
