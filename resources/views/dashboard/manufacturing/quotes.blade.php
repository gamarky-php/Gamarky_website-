@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-indigo-700 border-b border-indigo-800 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">عروض التصنيع</h1>
                <p class="text-indigo-100">إدارة ومتابعة عروض أسعار التصنيع المرتبطة بطلباتك</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('dashboard.manufacturing.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 backdrop-blur text-white border border-white/20 hover:bg-white/20 transition-all">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    رجوع للتصنيع
                </a>

                <button type="button"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-white text-indigo-600 hover:bg-indigo-50 font-semibold transition-all shadow-lg">
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إنشاء طلب عرض جديد
                </button>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">قائمة العروض</h2>
                <span class="px-3 py-1 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-full">قيد التطوير</span>
            </div>
        </div>

        <div class="p-6">
            {{-- Empty State محسّن --}}
            <div class="text-center py-16">
                <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center mb-4 shadow-inner">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-5-3-5 3-5-3-5 3V6a2 2 0 012-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد عروض حتى الآن</h3>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">ابدأ بإنشاء طلب عرض سعر جديد لتصنيع منتجاتك وستظهر جميع العروض هنا</p>

                <button type="button"
                        class="inline-flex items-center px-6 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    إنشاء أول طلب عرض
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
