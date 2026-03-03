@extends('layouts.front')
@section('title', __('dashboard.admin.suppliers.import_review.title'))
@section('content')
<div class="max-w-5xl mx-auto p-6">
  <h1 class="text-xl font-bold mb-4">{{ __('dashboard.admin.suppliers.import_review.heading') }}</h1>
  <div class="bg-white rounded-xl shadow p-4">
    <p class="text-sm text-gray-600 mb-4">{{ __('dashboard.admin.suppliers.import_review.placeholder') }}</p>
    <form method="POST" action="{{ route('admin.suppliers.import.approve') }}">
      @csrf
      <button class="px-4 py-2 bg-green-600 text-white rounded">{{ __('dashboard.admin.suppliers.import_review.approve') }}</button>
    </form>
  </div>
</div>
@endsection

