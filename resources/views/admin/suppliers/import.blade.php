@extends('layouts.app')

@section('title', 'استيراد الموردين من Excel')

@section('content')
<div class="container mx-auto px-4 py-6" dir="rtl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">استيراد الموردين من Excel</h1>
        <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            العودة للموردين
        </a>
    </div>

    {{-- عرض الرسائل --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">تم بنجاح!</p>
                    <p class="text-sm whitespace-pre-line">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-red-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">حدث خطأ!</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- قسم رفع الملف --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">رفع ملف Excel</h2>
            
            <form action="{{ route('admin.suppliers.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">
                        اختر ملف Excel (.xlsx, .xls, .csv)
                    </label>
                    <input type="file" 
                           name="excel_file" 
                           id="excel_file" 
                           accept=".xlsx,.xls,.csv"
                           class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('excel_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="mr-3">
                            <h3 class="text-sm font-medium text-yellow-800">تنبيه هام:</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>يجب أن يحتوي الملف على رؤوس الأعمدة المطلوبة بالضبط</li>
                                    <li>الحد الأقصى لحجم الملف: 10 ميجابايت</li>
                                    <li>سيتم تجاهل الموردين المكررين تلقائياً</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    رفع واستيراد البيانات
                </button>
            </form>
        </div>

        {{-- قسم تحميل القالب والإرشادات --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">إرشادات الاستيراد</h2>
            
            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-800 mb-2">رؤوس الأعمدة المطلوبة:</h3>
                    <div class="grid grid-cols-2 gap-2 text-sm text-blue-700">
                        <div>• Company Name</div>
                        <div>• Province</div>
                        <div>• City</div>
                        <div>• Contact Person</div>
                        <div>• Mr/Ms</div>
                        <div>• Mobile Phone</div>
                        <div>• Tel</div>
                        <div>• Fax</div>
                        <div>• Address</div>
                        <div>• Post Code</div>
                        <div>• Website</div>
                        <div>• Introduction</div>
                        <div>• Main Products</div>
                        <div>• Company Name（CN）</div>
                    </div>
                </div>

                <a href="{{ route('admin.suppliers.import.index') }}" 
                   class="block w-full text-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">
                    <svg class="w-4 h-4 inline ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    مراجعة الموردين المستوردين
                </a>

                <div class="text-sm text-gray-600 space-y-2">
                    <p><strong>ملاحظات مهمة:</strong></p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>يجب أن يكون الصف الأول يحتوي على رؤوس الأعمدة بالضبط</li>
                        <li>عمود Company Name مطلوب ولا يمكن أن يكون فارغاً</li>
                        <li>سيتم وضع جميع الموردين الجدد في حالة "قيد المراجعة"</li>
                        <li>في حالة وجود أخطاء، ستحصل على تقرير مفصل</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection