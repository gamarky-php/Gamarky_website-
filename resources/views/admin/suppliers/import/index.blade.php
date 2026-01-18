{{-- صفحة عرض الموردين قيد المراجعة --}}
@extends('layouts.dashboard')

@section('title', 'الوارد من الإكسيل (Pending)')

@section('dashboard')
<div class="container mx-auto px-4 py-6" dir="rtl">
    {{-- العنوان --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">الوارد من الإكسيل (Pending)</h1>
            <p class="text-gray-600">مراجعة واعتماد الموردين المستوردين من ملفات Excel</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.suppliers.import.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                رفع ملف جديد
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                إدارة الموردين
            </a>
        </div>
    </div>

    {{-- عرض الرسائل --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <div class="font-medium mb-2">يرجى تصحيح الأخطاء التالية:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($rows->count() > 0)
        {{-- نموذج الاعتماد --}}
        <form action="{{ route('admin.suppliers.import.approve') }}" method="POST" id="approveForm">
            @csrf
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                {{-- رأس الجدول مع أزرار التحكم --}}
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">
                            إجمالي الموردين: {{ $rows->total() }}
                        </h2>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 text-sm">
                                <button type="button" id="selectAll" class="text-blue-600 hover:text-blue-800 underline">
                                    تحديد الكل
                                </button>
                                <span class="text-gray-300">|</span>
                                <button type="button" id="deselectAll" class="text-blue-600 hover:text-blue-800 underline">
                                    إلغاء التحديد
                                </button>
                            </div>
                            <span class="text-sm text-gray-600">
                                محدد: <span id="selectedCount">0</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- الجدول --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAllCheckbox" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Company
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Province
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    City
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Main Products
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($rows as $supplier)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="supplier_ids[]" value="{{ $supplier->id }}" class="form-checkbox h-4 w-4 text-blue-600 rounded supplier-checkbox">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $supplier->company_name }}
                                        </div>
                                        @if($supplier->company_name_cn)
                                            <div class="text-sm text-gray-500">
                                                {{ $supplier->company_name_cn }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $supplier->province ?: 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $supplier->city ?: 'غير محدد' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ Str::limit($supplier->main_products ?: 'غير محدد', 100) }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- شريط الإجراءات --}}
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            صفحة {{ $rows->currentPage() }} من {{ $rows->lastPage() }}
                            ({{ $rows->firstItem() }}-{{ $rows->lastItem() }} من {{ $rows->total() }})
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed" 
                                id="approveButton" 
                                disabled>
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            اعتماد المحدد
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- ترقيم الصفحات --}}
        <div class="mt-6">
            {{ $rows->links() }}
        </div>
    @else
        {{-- رسالة عدم وجود بيانات --}}
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد موردين قيد المراجعة</h3>
            <p class="text-sm text-gray-500 mb-6">جميع الموردين تم اعتمادهم أو لم يتم استيراد موردين جدد بعد.</p>
            <a href="{{ route('admin.suppliers.import.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                استيراد موردين جدد
            </a>
        </div>
    @endif
</div>

{{-- JavaScript للتحكم في الـ checkboxes --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const supplierCheckboxes = document.querySelectorAll('.supplier-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const approveButton = document.getElementById('approveButton');
    const selectAllButton = document.getElementById('selectAll');
    const deselectAllButton = document.getElementById('deselectAll');
    const approveForm = document.getElementById('approveForm');

    // تحديث عداد المحدد وحالة زر الاعتماد
    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.supplier-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCountSpan.textContent = count;
        approveButton.disabled = count === 0;
        
        // تحديث حالة selectAllCheckbox
        if (count === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (count === supplierCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // تحديد/إلغاء تحديد الكل عبر الـ checkbox الرئيسي
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            supplierCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }

    // تحديد الكل عبر الرابط
    if (selectAllButton) {
        selectAllButton.addEventListener('click', function(e) {
            e.preventDefault();
            supplierCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount();
        });
    }

    // إلغاء تحديد الكل عبر الرابط
    if (deselectAllButton) {
        deselectAllButton.addEventListener('click', function(e) {
            e.preventDefault();
            supplierCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });
    }

    // مراقبة تغيير حالة الـ checkboxes الفردية
    supplierCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // تأكيد الاعتماد
    if (approveForm) {
        approveForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.supplier-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('يرجى اختيار مورد واحد على الأقل للاعتماد');
                return;
            }
            
            if (!confirm(`هل أنت متأكد من اعتماد ${checkedBoxes.length} مورد؟`)) {
                e.preventDefault();
            }
        });
    }

    // تحديث العداد في البداية
    updateSelectedCount();
});
</script>
@endsection