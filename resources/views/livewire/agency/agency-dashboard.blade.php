<div dir="rtl" class="space-y-6">
    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="flex border-b border-gray-200">
            <button
                wire:click="switchTab('shipping')"
                class="px-6 py-3 text-sm font-medium transition-colors duration-150 {{ $activeTab === 'shipping' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-600 hover:text-gray-800' }}">
                🚢 وكلاء الشحن
            </button>
            <button
                wire:click="switchTab('brand')"
                class="px-6 py-3 text-sm font-medium transition-colors duration-150 {{ $activeTab === 'brand' ? 'text-purple-600 border-b-2 border-purple-600' : 'text-gray-600 hover:text-gray-800' }}">
                🏷️ وكلاء العلامات التجارية
            </button>
        </div>
    </div>

    {{-- Shipping Agents Tab --}}
    @if($activeTab === 'shipping')
        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي الوكلاء</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($shippingKpis['total_agents']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">نشط هذا الشهر</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($shippingKpis['active_this_month']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي الحجوزات</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ number_format($shippingKpis['total_bookings']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي الإيرادات</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">${{ number_format($shippingKpis['total_revenue'], 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">تصفية النتائج</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">المنطقة</label>
                    <select wire:model.live="shippingRegion" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">جميع المناطق</option>
                        @foreach($regions as $region)
                            <option value="{{ $region }}">{{ $region }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للإيرادات ($)</label>
                    <input type="number" wire:model.live="shippingMinRevenue" min="0" step="1000" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                </div>
            </div>
        </div>

        {{-- Agents List --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <h3 class="text-lg font-bold text-gray-900 mb-4">وكلاء الشحن ({{ $shippingAgents->total() }})</h3>
            
            @if($shippingAgents->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البلد</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القطاع</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عروض نشطة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإيرادات</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التسجيل</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($shippingAgents as $agent)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <span class="text-indigo-600 font-semibold text-sm">{{ mb_substr($agent->name, 0, 2) }}</span>
                                            </div>
                                            <div class="mr-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $agent->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $agent->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">{{ $agent->country }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $agent->business_sector ?? 'غير محدد' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">{{ $agent->active_quotes }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm font-bold text-emerald-600">${{ number_format($agent->total_revenue, 0) }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">{{ \Carbon\Carbon::parse($agent->created_at)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <button wire:click="viewShippingAgent({{ $agent->id }})" class="text-indigo-600 hover:text-indigo-800 font-medium">عرض</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $shippingAgents->links() }}
                </div>
            @else
                <p class="text-center text-gray-500 py-8">لا توجد نتائج</p>
            @endif
        </div>
    @endif

    {{-- Brand Agents Tab --}}
    @if($activeTab === 'brand')
        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($brandKpis['total_requests']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">قيد المراجعة</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($brandKpis['pending_review']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">وكلاء معتمدون</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($brandKpis['accepted_agents']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">متوسط التقييم</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1">{{ $brandKpis['avg_score'] }}/100</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">تصفية النتائج</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">القطاع</label>
                    <select wire:model.live="brandSector" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">جميع القطاعات</option>
                        @foreach($sectors as $sector)
                            <option value="{{ $sector }}">{{ $sector }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">حالة القرار</label>
                    <select wire:model.live="brandDecision" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <option value="">جميع الحالات</option>
                        <option value="pending">قيد المراجعة</option>
                        <option value="accepted">معتمد</option>
                        <option value="conditional">مشروط</option>
                        <option value="rejected">مرفوض</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للتقييم</label>
                    <input type="number" wire:model.live="brandMinScore" min="0" max="100" 
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="0">
                </div>
            </div>
        </div>

        {{-- Requests List --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <h3 class="text-lg font-bold text-gray-900 mb-4">طلبات الوكالة التجارية ({{ $brandAgents->total() }})</h3>
            
            @if($brandAgents->count() > 0)
                <div class="space-y-4">
                    @foreach($brandAgents as $request)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-150">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h4 class="text-base font-semibold text-gray-900">{{ $request->full_name }}</h4>
                                        @if($request->company_name)
                                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded">{{ $request->company_name }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                        <div>
                                            <span class="text-gray-500">القطاع:</span>
                                            <span class="font-medium text-gray-900 mr-1">{{ $request->sector }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">البلد:</span>
                                            <span class="font-medium text-gray-900 mr-1">{{ $request->country }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">الخبرة:</span>
                                            <span class="font-medium text-gray-900 mr-1">{{ $request->experience_years }} سنة</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">التقييم:</span>
                                            <span class="font-bold text-indigo-600 mr-1">{{ $request->score_total }}/100</span>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex items-center gap-3">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full 
                                            {{ $request->decision === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $request->decision === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                            {{ $request->decision === 'conditional' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $request->decision === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ $request->decision === 'accepted' ? '✓ معتمد' : '' }}
                                            {{ $request->decision === 'pending' ? '⏱ قيد المراجعة' : '' }}
                                            {{ $request->decision === 'conditional' ? '⚠️ مشروط' : '' }}
                                            {{ $request->decision === 'rejected' ? '✗ مرفوض' : '' }}
                                        </span>
                                        
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($request->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <button wire:click="viewBrandAgent({{ $request->id }})" class="px-4 py-2 text-sm font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors">
                                    عرض التفاصيل
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $brandAgents->links() }}
                </div>
            @else
                <p class="text-center text-gray-500 py-8">لا توجد نتائج</p>
            @endif
        </div>
    @endif

    {{-- Shipping Agent Details Modal --}}
    @if($showShippingAgentModal && $selectedShippingAgent)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-start justify-between border-b border-gray-200 pb-4">
                            <h3 class="text-xl font-bold text-gray-900">تفاصيل وكيل الشحن</h3>
                            <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">الاسم</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedShippingAgent->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedShippingAgent->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">الهاتف</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedShippingAgent->phone ?? 'غير متوفر' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">البلد</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedShippingAgent->country }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">القطاع</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedShippingAgent->business_sector ?? 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">تاريخ التسجيل</p>
                                    <p class="text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($selectedShippingAgent->created_at)->format('Y-m-d') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button wire:click="closeModals" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Brand Agent Details Modal --}}
    @if($showBrandAgentModal && $selectedBrandAgent)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-start justify-between border-b border-gray-200 pb-4">
                            <h3 class="text-xl font-bold text-gray-900">تفاصيل طلب الوكالة التجارية</h3>
                            <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <div class="mt-6 space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">الاسم الكامل</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">اسم الشركة</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->company_name ?? 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">البلد</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->country }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">المدينة</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->city ?? 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">القطاع</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->sector }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">سنوات الخبرة</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->experience_years }} سنة</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">الهاتف</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $selectedBrandAgent->phone }}</p>
                                </div>
                            </div>

                            @if($selectedBrandAgent->expansion_plan)
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">خطة التوسع</p>
                                    <p class="text-sm text-gray-800 bg-gray-50 rounded-lg p-3">{{ $selectedBrandAgent->expansion_plan }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">التقييم الكلي</p>
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl font-bold text-indigo-600">{{ $selectedBrandAgent->score_total }}</span>
                                        <span class="text-sm text-gray-500">/100</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">حالة القرار</p>
                                    <span class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                                        {{ $selectedBrandAgent->decision === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $selectedBrandAgent->decision === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $selectedBrandAgent->decision === 'conditional' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $selectedBrandAgent->decision === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $selectedBrandAgent->decision === 'accepted' ? '✓ معتمد' : '' }}
                                        {{ $selectedBrandAgent->decision === 'pending' ? '⏱ قيد المراجعة' : '' }}
                                        {{ $selectedBrandAgent->decision === 'conditional' ? '⚠️ مشروط' : '' }}
                                        {{ $selectedBrandAgent->decision === 'rejected' ? '✗ مرفوض' : '' }}
                                    </span>
                                </div>
                            </div>

                            @if($selectedBrandAgent->decision_notes)
                                <div>
                                    <p class="text-sm text-gray-500 mb-2">ملاحظات القرار</p>
                                    <p class="text-sm text-gray-800 bg-amber-50 rounded-lg p-3 border border-amber-200">{{ $selectedBrandAgent->decision_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button wire:click="closeModals" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
