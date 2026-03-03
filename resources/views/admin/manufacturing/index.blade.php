@extends('layouts.dashboard')
@section('title', __('nav.manufacturing'))
@section('dashboard')
  {{-- dir inherited from layout --}}
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">{{ __('nav.manufacturing') }}</h1>
    <p class="text-gray-600">{{ __('dashboard.admin.generic_placeholder') }}</p>
  </div>
@endsection

