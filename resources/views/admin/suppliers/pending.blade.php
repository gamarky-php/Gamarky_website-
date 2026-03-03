@extends('layouts.app')

@section('title', __('dashboard.admin.suppliers.pending.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">{{ __('dashboard.admin.suppliers.pending.heading') }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.suppliers.import.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                {{ __('dashboard.admin.suppliers.pending.import_new') }}
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                {{ __('dashboard.admin.suppliers.pending.back_to_suppliers') }}
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 ml-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">{{ __('dashboard.admin.suppliers.pending.success_title') }}</p>
                    <p class="text-sm">{{ session('success') }}</p>
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
                    <p class="font-bold">{{ __('dashboard.admin.suppliers.pending.error_title') }}</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($pendingSuppliers->count() > 0)
        <form action="{{ route('admin.suppliers.import.approve') }}" method="POST" id="approveForm">
            @csrf
            
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ __('dashboard.admin.suppliers.pending.waiting_review') }} ({{ $pendingSuppliers->total() }})
                        </h2>
                        <div class="flex items-center gap-2">
                            <button type="button" id="selectAll" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ __('dashboard.admin.suppliers.pending.select_all') }}
                            </button>
                            <span class="text-gray-300">|</span>
                            <button type="button" id="deselectAll" class="text-sm text-blue-600 hover:text-blue-800">
                                {{ __('dashboard.admin.suppliers.pending.deselect_all') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right">
                                    <input type="checkbox" id="selectAllCheckbox" class="form-checkbox h-4 w-4 text-blue-600">
                                </th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.company_name') }}</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.province') }}</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.city') }}</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.contact') }}</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.mobile') }}</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.website') }}</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.columns.created_at') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingSuppliers as $supplier)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="supplier_ids[]" value="{{ $supplier->id }}" class="form-checkbox h-4 w-4 text-blue-600 supplier-checkbox">
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $supplier->company_name }}
                                        @if($supplier->company_name_cn)
                                            <br><span class="text-sm text-gray-500">{{ $supplier->company_name_cn }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $supplier->province ?: __('dashboard.admin.suppliers.pending.unspecified') }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $supplier->city ?: __('dashboard.admin.suppliers.pending.unspecified') }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        @if($supplier->contact_person)
                                            {{ $supplier->mr_ms }} {{ $supplier->contact_person }}
                                        @else
                                            {{ __('dashboard.admin.suppliers.pending.unspecified') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $supplier->mobile_phone ?: __('dashboard.admin.suppliers.pending.unspecified') }}</td>
                                    <td class="px-4 py-3">
                                        @if($supplier->website)
                                            <a href="http://{{ $supplier->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                                {{ Str::limit($supplier->website, 30) }}
                                            </a>
                                        @else
                                            <span class="text-gray-500">{{ __('dashboard.admin.suppliers.pending.unspecified') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 text-xs">{{ $supplier->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span id="selectedCount">0</span> {{ __('dashboard.admin.suppliers.pending.selected_suffix') }}
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed" id="approveButton" disabled>
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('dashboard.admin.suppliers.pending.approve_selected') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $pendingSuppliers->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('dashboard.admin.suppliers.pending.empty_title') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('dashboard.admin.suppliers.pending.empty_desc') }}</p>
            <div class="mt-6">
                <a href="{{ route('admin.suppliers.import.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    {{ __('dashboard.admin.suppliers.pending.import_new') }}
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const supplierCheckboxes = document.querySelectorAll('.supplier-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const approveButton = document.getElementById('approveButton');
    const selectAllButton = document.getElementById('selectAll');
    const deselectAllButton = document.getElementById('deselectAll');

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.supplier-checkbox:checked');
        const count = checkedBoxes.length;
        selectedCountSpan.textContent = count;
        approveButton.disabled = count === 0;
        
        // Update selectAll checkbox state
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

    // Toggle all from master checkbox
    selectAllCheckbox.addEventListener('change', function() {
        supplierCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Select all from link
    selectAllButton.addEventListener('click', function(e) {
        e.preventDefault();
        supplierCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectedCount();
    });

    // Deselect all from link
    deselectAllButton.addEventListener('click', function(e) {
        e.preventDefault();
        supplierCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelectedCount();
    });

    // Watch individual checkbox changes
    supplierCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Confirm approval
    document.getElementById('approveForm').addEventListener('submit', function(e) {
        const checkedBoxes = document.querySelectorAll('.supplier-checkbox:checked');
        if (checkedBoxes.length === 0) {
            e.preventDefault();
                alert("{{ __('dashboard.admin.suppliers.pending.alert_select_one') }}");
            return;
        }
        
        if (!confirm(`{{ __('dashboard.admin.suppliers.pending.confirm_prefix') }} ${checkedBoxes.length} {{ __('dashboard.admin.suppliers.pending.confirm_suffix') }}`)) {
            e.preventDefault();
        }
    });

    // Initialize selected count
    updateSelectedCount();
});
</script>
@endsection