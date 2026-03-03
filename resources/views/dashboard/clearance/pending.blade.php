<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('الشحنات المعلقة للتخليص') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('متابعة حالة الشحنات في مرحلة التخليص الجمركي') }}</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.clearance.index') }}"
                   class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                    {{ __('رجوع للتخليص') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('الشحنات المعلقة') }}</h3>
                    <div class="text-sm text-gray-500">{{ __('قيد التطوير') }}</div>
                </div>

                {{-- Empty State --}}
                <div class="text-center py-14">
                    <div class="mx-auto w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="mt-4 text-base font-semibold text-gray-900">{{ __('لا توجد شحنات معلقة') }}</h4>
                    <p class="mt-1 text-sm text-gray-500">{{ __('جميع الشحنات تم تخليصها أو قيد المعالجة') }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
