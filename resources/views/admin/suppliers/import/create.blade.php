{{-- Suppliers import upload page --}}
@extends('layouts.app')

@section('title', __('dashboard.admin.suppliers.import_create.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ __('dashboard.admin.suppliers.import_create.heading') }}</h1>
        <p class="text-gray-600">{{ __('dashboard.admin.suppliers.import_create.subtitle') }}</p>
    </div>

    {{-- Flash messages --}}
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
            <div class="font-medium mb-2">{{ __('dashboard.admin.suppliers.import_create.validation_errors') }}</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Upload form --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">{{ __('dashboard.admin.suppliers.import_create.upload_csv') }}</h2>
            
            <form action="{{ route('admin.suppliers.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="excel" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('dashboard.admin.suppliers.import_create.choose_csv') }}
                    </label>
                    <input type="file" 
                           name="excel" 
                           id="excel" 
                           accept=".csv,text/csv"
                           class="block w-full text-sm text-gray-500 file:ml-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('excel')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 ml-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-800">{{ __('dashboard.admin.suppliers.import_create.important_notice') }}</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li><strong>{{ __('dashboard.admin.suppliers.import_create.notes.save_utf8') }}</strong></li>
                                    <li>{{ __('dashboard.admin.suppliers.import_create.notes.delimiter_detection') }}</li>
                                    <li>{{ __('dashboard.admin.suppliers.import_create.notes.flexible_headers') }}</li>
                                    <li>{{ __('dashboard.admin.suppliers.import_create.notes.max_size') }}</li>
                                    <li>{{ __('dashboard.admin.suppliers.import_create.notes.pending_status') }}</li>
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
                    {{ __('dashboard.admin.suppliers.import_create.upload_import') }}
                </button>
            </form>
        </div>

        {{-- Usage instructions --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">{{ __('dashboard.admin.suppliers.import_create.required_headers') }}</h2>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
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

            <div class="space-y-3">
                <a href="{{ route('admin.suppliers.import.index') }}" 
                   class="block w-full text-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    <svg class="w-4 h-4 inline ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ __('dashboard.admin.suppliers.import_create.review_page') }}
                </a>
                
                <a href="{{ route('admin.suppliers.index') }}" 
                   class="block w-full text-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition">
                    {{ __('dashboard.admin.suppliers.import_create.back_to_suppliers') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection