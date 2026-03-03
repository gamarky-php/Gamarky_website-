@extends('layouts.dashboard')
@section('title', __('dashboard.admin.expat_cars.title'))
@section('dashboard')
  {{-- dir inherited from layout --}}
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">{{ __('dashboard.admin.expat_cars.title') }}</h1>
    <p class="text-gray-600">{{ __('dashboard.admin.expat_cars.placeholder') }}</p>
  </div>
@endsection

