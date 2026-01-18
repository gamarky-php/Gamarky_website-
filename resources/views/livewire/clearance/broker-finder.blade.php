<div class="min-h-screen bg-gray-50 dark:bg-gray-900" dir="rtl">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">بحث المستخلصين الجمركيين</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">ابحث عن أفضل المستخلصين حسب البلد والميناء والخبرة والتقييم</p>
                </div>
                <button wire:click="$toggle('showFilters')" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    {{ $showFilters ? 'إخفاء' : 'إظهار' }} الفلاتر
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي المستخلصين</p>
                        <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ $stats['total_brokers'] }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">متوسط التقييم</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $stats['avg_score'] }}/5</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">دول التغطية</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ count($countries) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">تقييمات جديدة (30 يوم)</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $stats['recent_reviews'] }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters Section --}}
        @if($showFilters)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {{-- Search Term --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البحث بالاسم</label>
                        <input type="text" wire:model.live.debounce.500ms="searchTerm"
                               placeholder="اسم المستخلص أو الشركة..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    {{-- Country Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البلد</label>
                        <select wire:model.live="countryFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">الكل</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Port Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الميناء</label>
                        <select wire:model.live="portFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">الكل</option>
                            @foreach($ports as $port)
                                <option value="{{ $port }}">{{ $port }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Activity Filter --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">النشاط</label>
                        <select wire:model.live="activityFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">الكل</option>
                            @foreach($activities as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Min Experience --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            الحد الأدنى للخبرة ({{ $minExperience }} سنة)
                        </label>
                        <input type="range" wire:model.live="minExperience" min="0" max="30" step="1"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                    </div>

                    {{-- Min Score --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            الحد الأدنى للتقييم ({{ $minScore }}/5)
                        </label>
                        <input type="range" wire:model.live="minScore" min="0" max="5" step="0.5"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                    </div>

                    {{-- Sort By --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الترتيب حسب</label>
                        <select wire:model.live="sortBy"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="score">التقييم</option>
                            <option value="experience_years">الخبرة</option>
                            <option value="name">الاسم</option>
                        </select>
                    </div>

                    {{-- Reset Button --}}
                    <div class="flex items-end">
                        <button wire:click="resetFilters" 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                            إعادة تعيين الفلاتر
                        </button>
                    </div>
                </div>
            </div>
        @endif

        {{-- Results Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @forelse($brokers as $broker)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        {{-- Header with Score --}}
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $broker->name }}</h3>
                                @if($broker->company_name)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $broker->company_name }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900/30 px-3 py-1 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <span class="text-sm font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($broker->score, 2) }}</span>
                            </div>
                        </div>

                        {{-- Location --}}
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $broker->country }}</span>
                        </div>

                        {{-- Experience --}}
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $broker->experience_years }} سنة خبرة</span>
                        </div>

                        {{-- Ports --}}
                        @if($broker->ports)
                            @php
                                $portsArray = json_decode($broker->ports, true) ?? [];
                            @endphp
                            @if(count($portsArray) > 0)
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">الموانئ المغطاة:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($portsArray, 0, 3) as $port)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-blue-100 dark:bg-blue-900/30 text-xs text-blue-700 dark:text-blue-400">
                                                {{ $port }}
                                            </span>
                                        @endforeach
                                        @if(count($portsArray) > 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs text-gray-600 dark:text-gray-400">
                                                +{{ count($portsArray) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Activities --}}
                        @if($broker->activities)
                            @php
                                $activitiesArray = json_decode($broker->activities, true) ?? [];
                            @endphp
                            @if(count($activitiesArray) > 0)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">الأنشطة:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($activitiesArray as $activity)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-xs text-emerald-700 dark:text-emerald-400">
                                                {{ $activities[$activity] ?? $activity }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Certifications --}}
                        @if($broker->certifications)
                            @php
                                $certs = json_decode($broker->certifications, true) ?? [];
                            @endphp
                            @if(count($certs) > 0)
                                <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">الشهادات:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($certs as $cert)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-100 dark:bg-purple-900/30 text-xs text-purple-700 dark:text-purple-400">
                                                {{ $cert }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- Action Button --}}
                        <a href="{{ route('dashboard.clearance.broker', $broker->id) }}" 
                           class="block w-full text-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                            عرض الملف الكامل
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center border border-gray-200 dark:border-gray-700">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">لم يتم العثور على مستخلصين</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">جرب تعديل معايير البحث</p>
                    <button wire:click="resetFilters" 
                            class="mt-4 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                        إعادة تعيين الفلاتر
                    </button>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $brokers->links() }}
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-gray-900/50 items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-4 space-x-reverse">
                <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">جاري البحث...</span>
            </div>
        </div>
    </div>
</div>
