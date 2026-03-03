@extends('layouts.dashboard')

@section('title', __('الأدوار والصلاحيات'))

@section('content')
<div class="space-y-6">
    
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                <svg class="w-8 h-8 inline-block ml-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                {{ __('الأدوار والصلاحيات') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('إدارة أدوار المستخدمين وصلاحيات الوصول') }}</p>
        </div>
        <a href="{{ route('dashboard.index') }}" 
           class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('العودة للوحة التحكم') }}
        </a>
    </div>
    
    <!-- Main Placeholder Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-12 text-center">
            <!-- Icon -->
            <div class="w-24 h-24 mx-auto mb-6 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            
            <!-- Title -->
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">
                {{ __('صفحة مؤقتة - قيد التطوير') }}
            </h2>
            
            <!-- Description -->
            <p class="text-gray-600 dark:text-gray-400 text-lg mb-8 max-w-2xl mx-auto">
                {{ __('هذه صفحة مؤقتة - سيتم ربط إدارة الأدوار والصلاحيات لاحقاً') }}
            </p>
            
            <!-- Info Box -->
            <div class="inline-flex items-start gap-3 px-6 py-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-right max-w-2xl">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-800 dark:text-blue-200">
                    <p class="font-semibold mb-1">{{ __('ماذا ستتضمن هذه الصفحة؟') }}</p>
                    <ul class="space-y-1 text-right mr-4">
                        <li>{{ __('• إنشاء وتعديل الأدوار (Admin, Manager, User, etc.)') }}</li>
                        <li>{{ __('• تحديد الصلاحيات لكل دور (View, Create, Edit, Delete)') }}</li>
                        <li>{{ __('• ربط المستخدمين بالأدوار المناسبة') }}</li>
                        <li>{{ __('• مراقبة وتدقيق تغييرات الصلاحيات') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feature Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Feature 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('صلاحيات مرنة') }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('تحديد صلاحيات دقيقة لكل دور حسب احتياجات العمل') }}</p>
        </div>
        
        <!-- Feature 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('أمان متقدم') }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('حماية البيانات الحساسة بنظام صلاحيات محكم') }}</p>
        </div>
        
        <!-- Feature 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('سجل التدقيق') }}</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ __('تتبع جميع التغييرات على الأدوار والصلاحيات') }}</p>
        </div>
        
    </div>
    
</div>
@endsection
