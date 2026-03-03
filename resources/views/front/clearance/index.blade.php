@extends('layouts.front')
@section('title', __('front.clearance.index.title'))
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-2">{{ __('front.clearance.index.heading') }}</h1>
  <p class="text-gray-600 mb-6">{{ __('front.clearance.index.intro') }}</p>

  <div class="grid md:grid-cols-3 gap-3 mb-6">
    <input class="input input-bordered w-full" placeholder="{{ __('front.clearance.index.city_placeholder') }}">
    <input class="input input-bordered w-full" placeholder="{{ __('front.clearance.index.port_placeholder') }}">
    <input class="input input-bordered w-full" placeholder="{{ __('front.clearance.index.goods_type_placeholder') }}">
  </div>

  <div class="bg-white rounded-2xl shadow p-5">
    <div class="flex items-center justify-between">
      <div>
        <div class="font-semibold">{{ __('front.clearance.index.sample_broker') }}</div>
        <div class="text-sm text-gray-500">{{ __('front.clearance.index.sample_meta') }}</div>
      </div>
      <div class="text-sm text-gray-600">{{ __('front.clearance.index.sla_label') }}</div>
    </div>
    <div class="mt-3">
      <a href="#" class="text-primary hover:underline">{{ __('front.clearance.index.details_and_reviews') }}</a>
    </div>
  </div>
</div>
@endsection
