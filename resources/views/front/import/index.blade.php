@extends('layouts.front')

@section('title', __('front.import.index.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-4">{{ __('front.import.index.heading') }}</h1>
  <p class="text-gray-600 mb-6">{{ __('front.import.index.intro') }}</p>

  <a href="{{ route('front.suppliers.index') }}" class="inline-block mb-4 text-sm text-blue-600 hover:underline">
    {{ __('front.import.index.suppliers_link') }}
  </a>

  {{-- بقية محتوى الاستيراد --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white rounded-lg border p-4">{{ __('front.import.index.placeholder_1') }}</div>
    <div class="bg-white rounded-lg border p-4">{{ __('front.import.index.placeholder_2') }}</div>
  </div>
</div>
@endsection

