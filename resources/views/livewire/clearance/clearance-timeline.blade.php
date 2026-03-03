{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    @if($jobId && $job)
        {{-- Single Job Timeline View --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('dashboard.clearance.timeline') }}" 
                           class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('شحنة') }} #{{ $job->shipment_ref }}</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('العميل:') }} {{ $job->client_name }} • {{ __('المستخلص:') }} {{ $job->broker_name ?? __('غير محدد') }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- SLA Badge --}}
                    <div class="px-4 py-2 rounded-lg {{ $slaStatus === 'overdue' ? 'bg-red-100 dark:bg-red-900/30' : ($slaStatus === 'at-risk' ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-green-100 dark:bg-green-900/30') }}">
                        <p class="text-sm font-medium {{ $slaStatus === 'overdue' ? 'text-red-700 dark:text-red-400' : ($slaStatus === 'at-risk' ? 'text-yellow-700 dark:text-yellow-400' : 'text-green-700 dark:text-green-400') }}">
                            @if($slaStatus === 'overdue')
                                {{ __('متأخر عن الموعد') }}
                            @elseif($slaStatus === 'at-risk')
                                {{ __('معرض للتأخير') }}
                            @else
                                {{ __('على المسار الصحيح') }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('التقدم الإجمالي') }}</span>
                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $progressPercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="bg-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Timeline Section --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-8">{{ __('مراحل التخليص') }}</h2>
                        
                        <div class="relative">
                            {{-- Vertical Line --}}
                            <div class="absolute right-8 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                            
                            <div class="space-y-8">
                                @foreach($stages as $index => $stage)
                                    @php
                                        $def = $stageDefinitions[$stage['stage']] ?? [];
                                        $statusColor = match($stage['status']) {
                                            'completed' => 'green',
                                            'in_progress' => $def['color'] ?? 'blue',
                                            default => 'gray'
                                        };
                                    @endphp
                                    
                                    <div class="relative flex gap-6">
                                        {{-- Icon Circle --}}
                                        <div class="relative z-10 flex-shrink-0 w-16 h-16 rounded-full flex items-center justify-center shadow-lg
                                                    {{ $stage['status'] === 'completed' ? 'bg-green-600' : ($stage['status'] === 'in_progress' ? 'bg-' . $statusColor . '-600 animate-pulse' : 'bg-gray-300 dark:bg-gray-600') }}">
                                            @if($stage['status'] === 'completed')
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @elseif($stage['status'] === 'in_progress')
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Stage Content --}}
                                        <div class="flex-1 bg-gray-50 dark:bg-gray-900 rounded-lg p-6 {{ $stage['status'] === 'in_progress' ? 'ring-2 ring-' . $statusColor . '-500' : '' }}">
                                            <div class="flex items-start justify-between mb-3">
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $def['name'] ?? $stage['stage'] }}</h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $def['description'] ?? '' }}</p>
                                                </div>
                                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                                             {{ $stage['status'] === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 
                                                                ($stage['status'] === 'in_progress' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 
                                                                'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400') }}">
                                                    {{ $stage['status'] === 'completed' ? __('مكتمل') : ($stage['status'] === 'in_progress' ? __('جاري') : __('معلق')) }}
                                                </span>
                                            </div>

                                            {{-- Timestamps --}}
                                            <div class="flex gap-4 text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                @if($stage['started_at'])
                                                    <div>
                                                        <span class="font-medium">{{ __('بدأت:') }}</span> {{ \Carbon\Carbon::parse($stage['started_at'])->format('Y-m-d H:i') }}
                                                    </div>
                                                @endif
                                                @if($stage['completed_at'])
                                                    <div>
                                                        <span class="font-medium">{{ __('انتهت:') }}</span> {{ \Carbon\Carbon::parse($stage['completed_at'])->format('Y-m-d H:i') }}
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Notes --}}
                                            @if($stage['notes'])
                                                <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $stage['notes'] }}</p>
                                                </div>
                                            @endif

                                            {{-- Action Button --}}
                                            @if($stage['status'] === 'in_progress')
                                                <button wire:click="completeStage({{ $index }})" 
                                                        class="mt-4 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm">
                                                    {{ __('إكمال هذه المرحلة') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Info --}}
                <div class="space-y-6">
                    {{-- Job Details --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('تفاصيل الشحنة') }}</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('رقم الشحنة:') }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $job->shipment_ref }}</span>
                            </div>
                            @if($job->bl_number)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('رقم بوليصة الشحن:') }}</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $job->bl_number }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('SLA (أيام):') }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $job->sla_days }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('التاريخ المتوقع:') }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $job->expected_clearance_date ?? 'N/A' }}</span>
                            </div>
                            @if($job->actual_clearance_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">{{ __('التاريخ الفعلي:') }}</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">{{ $job->actual_clearance_date }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">{{ __('الحالة:') }}</span>
                                <span class="px-2 py-1 rounded-full text-xs {{ in_array($job->status, ['cleared', 'released']) ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    {{ $job->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Fees Breakdown --}}
                    @if($job->fees_breakdown)
                        @php
                            $fees = json_decode($job->fees_breakdown, true) ?? [];
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('تفاصيل الرسوم') }}</h3>
                            <div class="space-y-2 text-sm">
                                @foreach($fees as $fee => $amount)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">{{ $fee }}:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($amount, 2) }} {{ $job->currency }}</span>
                                    </div>
                                @endforeach
                                <div class="flex justify-between pt-3 border-t border-gray-200 dark:border-gray-700 font-bold">
                                    <span class="text-gray-900 dark:text-white">{{ __('الإجمالي:') }}</span>
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ number_format($job->total_fees, 2) }} {{ $job->currency }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Broker Info --}}
                    @if($job->broker_id)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('المستخلص') }}</h3>
                            <div class="space-y-3">
                                <p class="font-medium text-gray-900 dark:text-white">{{ $job->broker_name }}</p>
                                @if($job->broker_company)
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $job->broker_company }}</p>
                                @endif
                                <a href="{{ route('dashboard.clearance.broker', $job->broker_id) }}" 
                                   class="inline-flex items-center gap-2 text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                                    {{ __('عرض الملف الكامل') }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    @else
        {{-- Jobs List View --}}
        <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('عمليات التخليص الجمركي') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('تتبع حالة جميع الشحنات والعمليات الجمركية') }}</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6 border border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('البحث') }}</label>
                        <input type="text" wire:model.live.debounce.500ms="searchTerm"
                               placeholder="{{ __('رقم الشحنة أو BL...') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('الحالة') }}</label>
                        <select wire:model.live="statusFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="">{{ __('الكل') }}</option>
                            <option value="pending">{{ __('معلق') }}</option>
                            <option value="documents_received">{{ __('المستندات مستلمة') }}</option>
                            <option value="under_review">{{ __('قيد المراجعة') }}</option>
                            <option value="customs_processing">{{ __('معالجة جمركية') }}</option>
                            <option value="payment_pending">{{ __('بانتظار الدفع') }}</option>
                            <option value="cleared">{{ __('تم التخليص') }}</option>
                            <option value="released">{{ __('تم الإفراج') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('حالة SLA') }}</label>
                        <select wire:model.live="slaFilter"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            <option value="all">{{ __('الكل') }}</option>
                            <option value="on-track">{{ __('على المسار') }}</option>
                            <option value="at-risk">{{ __('معرض للتأخير') }}</option>
                            <option value="overdue">{{ __('متأخر') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Jobs Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('رقم الشحنة') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('العميل') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('المستخلص') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('الحالة') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('التاريخ المتوقع') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('SLA') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">{{ __('إجراءات') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($jobs as $job)
                                @php
                                    $expectedDate = \Carbon\Carbon::parse($job->expected_clearance_date);
                                    $daysRemaining = \Carbon\Carbon::today()->diffInDays($expectedDate, false);
                                    $sla = $daysRemaining < 0 ? 'overdue' : ($daysRemaining <= 1 ? 'at-risk' : 'on-track');
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $job->shipment_ref }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $job->client_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $job->broker_name ?? __('غير محدد') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full {{ in_array($job->status, ['cleared', 'released']) ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                            {{ $job->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $job->expected_clearance_date ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $sla === 'overdue' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : ($sla === 'at-risk' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400') }}">
                                            {{ $sla === 'overdue' ? __('متأخر') : ($sla === 'at-risk' ? __('خطر') : __('جيد')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('dashboard.clearance.timeline', $job->id) }}" 
                                           class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm">
                                            {{ __('عرض التفاصيل') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-500">{{ __('لا توجد عمليات تخليص') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        </div>
    @endif

    {{-- Loading Overlay --}}
    <div wire:loading.flex class="fixed inset-0 bg-gray-900/50 items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-4 space-x-reverse">
                <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">{{ __('جاري التحميل...') }}</span>
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
