@extends('layouts.app')
@section('title', __('dashboard.tariff.title'))
@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto py-8">
  <h1 class="text-2xl font-bold mb-4">{{ __('dashboard.tariff.title') }}</h1>
  <p class="text-gray-600">{{ __('dashboard.tariff.placeholder') }}</p>
</div>
@endsection
