@extends('layouts.front')
@section('title', __('front.clearance.notifications.title'))
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-4">{{ __('front.clearance.notifications.heading') }}</h1>
  <div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="font-semibold mb-2">{{ __('front.clearance.notifications.notifications_log') }}</h2>
      <ul class="list-disc mr-5 text-gray-700 space-y-1">
        <li>{{ __('front.clearance.notifications.notif_1') }}</li>
        <li>{{ __('front.clearance.notifications.notif_2') }}</li>
      </ul>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="font-semibold mb-2">{{ __('front.clearance.notifications.rating_panel') }}</h2>
      <p class="text-gray-600">{{ __('front.clearance.notifications.rating_desc') }}</p>
    </div>
  </div>
</div>
@endsection
