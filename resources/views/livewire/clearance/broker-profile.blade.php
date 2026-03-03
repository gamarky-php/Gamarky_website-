{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard.clearance.brokers') }}" 
                       class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $broker->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            @if($broker->company_name)
                                {{ $broker->company_name }} • 
                            @endif
                            {{ $broker->country }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 bg-yellow-100 dark:bg-yellow-900/30 px-4 py-2 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($broker->score, 2) }}</span>
                    </div>
                    <button wire:click="$set('showContactModal', true)" 
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                        {{ __('طلب عرض سعر') }}
                    </button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-6 mt-6 border-b border-gray-200 dark:border-gray-700">
                <button wire:click="$set('activeTab', 'overview')" 
                        class="pb-3 border-b-2 {{ $activeTab === 'overview' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    {{ __('نظرة عامة') }}
                </button>
                <button wire:click="$set('activeTab', 'documents')" 
                        class="pb-3 border-b-2 {{ $activeTab === 'documents' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    {{ __('الوثائق') }} ({{ count($documents, COUNT_RECURSIVE) - count($documents) }})
                </button>
                <button wire:click="$set('activeTab', 'reviews')" 
                        class="pb-3 border-b-2 {{ $activeTab === 'reviews' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    {{ __('التقييمات') }} ({{ count($reviews) }})
                </button>
                <button wire:click="$set('activeTab', 'performance')" 
                        class="pb-3 border-b-2 {{ $activeTab === 'performance' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    {{ __('الأداء') }}
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Overview Tab --}}
        @if($activeTab === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main Info --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('إجمالي الشحنات') }}</p>
                            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ $stats['total_jobs'] }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('معدل الإنجاز في الوقت') }}</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $stats['on_time_rate'] }}%</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('متوسط التخليص (أيام)') }}</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $stats['avg_clearance_days'] }}</p>
                        </div>
                    </div>

                    {{-- Composite Ratings --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">{{ __('التقييمات المركّبة') }}</h3>
                        <div class="space-y-4">
                            {{-- Speed --}}
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('السرعة') }}</span>
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $compositeRatings['speed'] ?? 0 }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ (($compositeRatings['speed'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>

                            {{-- Accuracy --}}
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('الدقة') }}</span>
                                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $compositeRatings['accuracy'] ?? 0 }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ (($compositeRatings['accuracy'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>

                            {{-- Communication --}}
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('التواصل') }}</span>
                                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $compositeRatings['communication'] ?? 0 }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ (($compositeRatings['communication'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>

                            {{-- Cost --}}
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('التكلفة') }}</span>
                                    <span class="text-sm font-bold text-orange-600 dark:text-orange-400">{{ $compositeRatings['cost'] ?? 0 }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-orange-600 h-2 rounded-full" style="width: {{ (($compositeRatings['cost'] ?? 0) / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Reviews --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('آخر التقييمات') }}</h3>
                            <button wire:click="$set('showReviewModal', true)" 
                                    class="text-sm px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                                {{ __('إضافة تقييم') }}
                            </button>
                        </div>
                        <div class="space-y-4">
                            @forelse(array_slice($reviews, 0, 5) as $review)
                                <div class="pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $review->reviewer_name ?? __('مستخدم مجهول') }}</p>
                                            @if($review->reviewer_company)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->reviewer_company }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @endfor
                                            @if($review->is_verified)
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $review->comments }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-center text-gray-500 dark:text-gray-500 py-8">{{ __('لا توجد تقييمات بعد') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Contact Info --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('معلومات الاتصال') }}</h3>
                        <div class="space-y-3">
                            @if($broker->email)
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">{{ $broker->email }}</span>
                                </div>
                            @endif
                            @if($broker->phone)
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white">{{ $broker->phone }}</span>
                                </div>
                            @endif
                            @if($broker->website)
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                    </svg>
                                    <a href="{{ $broker->website }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 hover:underline">{{ __('الموقع الإلكتروني') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Experience --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('الخبرة والشهادات') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $broker->experience_years }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('سنة خبرة') }}</p>
                                </div>
                            </div>

                            @if($broker->certifications)
                                @php
                                    $certs = json_decode($broker->certifications, true) ?? [];
                                @endphp
                                @if(count($certs) > 0)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('الشهادات:') }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($certs as $cert)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-purple-100 dark:bg-purple-900/30 text-sm text-purple-700 dark:text-purple-400">
                                                    {{ $cert }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Activities & Ports --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('الأنشطة والموانئ') }}</h3>
                        <div class="space-y-4">
                            @if($broker->activities)
                                @php
                                    $activities = json_decode($broker->activities, true) ?? [];
                                @endphp
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('الأنشطة:') }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($activities as $activity)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-sm text-blue-700 dark:text-blue-400">
                                                {{ $activity }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($broker->ports)
                                @php
                                    $ports = json_decode($broker->ports, true) ?? [];
                                @endphp
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('الموانئ المغطاة:') }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($ports as $port)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-sm text-emerald-700 dark:text-emerald-400">
                                                {{ $port }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Documents Tab --}}
        @if($activeTab === 'documents')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">{{ __('الوثائق والمستندات') }}</h3>
                @forelse($documents as $type => $docs)
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">{{ ucfirst($type) }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($docs as $doc)
                                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $doc->original_filename ?? 'Document' }}</p>
                                            <p class="text-xs text-gray-500">
                                                <span class="px-2 py-1 rounded-full {{ $doc->status === 'approved' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                                                    {{ $doc->status }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-500 py-12">{{ __('لا توجد مستندات') }}</p>
                @endforelse
            </div>
        @endif

        {{-- Reviews Tab --}}
        @if($activeTab === 'reviews')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('جميع التقييمات') }} ({{ count($reviews) }})</h3>
                    <button wire:click="$set('showReviewModal', true)" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                        {{ __('إضافة تقييم') }}
                    </button>
                </div>
                <div class="space-y-6">
                    @forelse($reviews as $review)
                        <div class="pb-6 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $review->reviewer_name ?? __('مستخدم مجهول') }}</p>
                                    @if($review->reviewer_company)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $review->reviewer_company }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ \Carbon\Carbon::parse($review->created_at)->format('Y-m-d') }}</p>
                                </div>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                    @if($review->is_verified)
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $review->comments }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-500 py-12">{{ __('لا توجد تقييمات بعد') }}</p>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- Performance Tab --}}
        @if($activeTab === 'performance')
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">{{ __('آخر الشحنات') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900">
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('رقم الشحنة') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('العميل') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('الحالة') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('التاريخ المتوقع') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('التاريخ الفعلي') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($recentJobs as $job)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $job->shipment_ref }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $job->client_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full {{ in_array($job->status, ['cleared', 'released']) ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                                {{ $job->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $job->expected_clearance_date ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $job->actual_clearance_date ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-500">{{ __('لا توجد شحنات') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Review Modal --}}
    @if($showReviewModal)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="$set('showReviewModal', false)">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full mx-4 p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('إضافة تقييم') }}</h3>
                
                <div class="space-y-6">
                    {{-- Overall Rating --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('التقييم العام') }}</label>
                        <div class="flex gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <button wire:click="$set('reviewRating', {{ $i }})" type="button">
                                    <svg class="w-8 h-8 {{ $i <= $reviewRating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                    </div>

                    {{-- Criteria Scores --}}
                    <div class="grid grid-cols-2 gap-4">
                        @foreach(['speed' => __('السرعة'), 'accuracy' => __('الدقة'), 'communication' => __('التواصل'), 'cost' => __('التكلفة')] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ $label }}</label>
                                <input type="range" wire:model="criteriaScores.{{ $key }}" min="1" max="5" step="1"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $criteriaScores[$key] }}/5</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Comment --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('التعليق') }}</label>
                        <textarea wire:model="reviewComment" rows="4"
                                  placeholder="{{ __('شارك تجربتك مع هذا المستخلص...') }}"
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                        @error('reviewComment') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="submitReview" 
                            class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                        {{ __('إرسال التقييم') }}
                    </button>
                    <button wire:click="$set('showReviewModal', false)" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                        {{ __('إلغاء') }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Contact Modal --}}
    @if($showContactModal)
        <div class="fixed inset-0 bg-gray-900/50 flex items-center justify-center z-50" wire:click.self="$set('showContactModal', false)">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('طلب عرض سعر') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('يمكنك التواصل مع المستخلص مباشرة:') }}</p>
                
                <div class="space-y-3">
                    @if($broker->email)
                        <a href="mailto:{{ $broker->email }}" 
                           class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-900 dark:text-white">{{ $broker->email }}</span>
                        </a>
                    @endif
                    @if($broker->phone)
                        <a href="tel:{{ $broker->phone }}" 
                           class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-900 dark:text-white">{{ $broker->phone }}</span>
                        </a>
                    @endif
                </div>

                <button wire:click="$set('showContactModal', false)" 
                        class="w-full mt-6 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg">
                    {{ __('إغلاق') }}
                </button>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    window.addEventListener('alert', event => {
        alert(event.detail.message);
    });
</script>
@endpush
