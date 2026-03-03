@extends('layouts.front')
@section('title', __('dashboard.admin.suppliers.import_upload.title'))
@section('content')
<div class="max-w-3xl mx-auto p-6">
  <h1 class="text-xl font-bold mb-4">{{ __('dashboard.admin.suppliers.import_upload.heading') }}</h1>
  <form method="POST" action="{{ route('admin.suppliers.import.store') }}" enctype="multipart/form-data" class="space-y-4 bg-white p-4 rounded-xl shadow">
    @csrf
    <input type="file" name="file" class="block w-full border rounded p-2">
    <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('dashboard.admin.suppliers.import_upload.upload_preview') }}</button>
  </form>
</div>
@endsection

