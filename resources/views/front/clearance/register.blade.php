@extends('layouts.front')
@section('title', __('front.clearance.register.title'))
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-4">{{ __('front.clearance.register.heading') }}</h1>
  <form class="space-y-4 max-w-3xl">
    <input class="input input-bordered w-full" placeholder="{{ __('front.clearance.register.trade_name_placeholder') }}">
    <div class="grid md:grid-cols-2 gap-3">
      <input class="input input-bordered w-full" placeholder="{{ __('front.clearance.register.country_city_placeholder') }}">
      <input class="input input-bordered w-full" placeholder="{{ __('front.clearance.register.covered_ports_placeholder') }}">
    </div>
    <textarea class="textarea textarea-bordered w-full" rows="4" placeholder="{{ __('front.clearance.register.specializations_placeholder') }}"></textarea>
    <button type="button" class="btn btn-primary">{{ __('front.clearance.register.submit_for_review') }}</button>
  </form>
</div>
@endsection
