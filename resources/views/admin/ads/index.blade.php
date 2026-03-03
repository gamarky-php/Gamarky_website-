@extends('layouts.dashboard')
@section('title', __('dashboard.admin.ads.title'))
@section('dashboard')
  {{-- dir inherited from layout --}}
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">{{ __('dashboard.admin.ads.title') }}</h1>
    <p class="text-gray-600">{{ __('dashboard.admin.ads.placeholder') }}</p>
  </div>
@endsection

