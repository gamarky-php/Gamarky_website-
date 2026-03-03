@extends('layouts.front')
@section('title', __('front.clearance.role.title'))
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-4">{{ __('front.clearance.role.heading') }}</h1>
  <ol class="space-y-3">
    <li class="bg-white rounded-xl shadow p-4">{{ __('front.clearance.role.step_1') }}</li>
    <li class="bg-white rounded-xl shadow p-4">{{ __('front.clearance.role.step_2') }}</li>
    <li class="bg-white rounded-xl shadow p-4">{{ __('front.clearance.role.step_3') }}</li>
    <li class="bg-white rounded-xl shadow p-4">{{ __('front.clearance.role.step_4') }}</li>
  </ol>
</div>
@endsection
