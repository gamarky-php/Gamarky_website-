@extends('layouts.app')
@section('title', __('الوكيل'))

@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto py-8">
  <h1 class="text-2xl font-bold mb-4">{{ __('الوكيل') }}</h1>
  <p class="text-gray-600">{{ __('هنا ستظهر أدوات وإعدادات قسم الوكلاء.') }}</p>
  <a href="{{ route('admin.dashboard') }}" class="inline-block mt-4 text-blue-600 hover:underline">
    {{ __('رجوع إلى لوحة التحكم') }}
  </a>
</div>
@endsection
