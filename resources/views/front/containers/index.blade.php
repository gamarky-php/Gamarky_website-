@extends('layouts.front')
@section('title', __('front.containers.index.title'))
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-2">{{ __('front.containers.index.heading') }}</h1>
  <p class="text-gray-600 mb-6">{{ __('front.containers.index.intro') }}</p>
  <div class="bg-white rounded-2xl shadow p-5">
    <div class="text-gray-700">{{ __('front.containers.index.placeholder') }}</div>
  </div>
</div>
@endsection
