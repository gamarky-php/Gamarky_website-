@extends('layouts.front')

@section('title','الاستيراد')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">
  <h1 class="text-2xl font-bold mb-4">الاستيراد</h1>
  <p class="text-gray-600 mb-6">مقدمة عن قسم الاستيراد وخدماته.</p>

  <a href="{{ route('front.suppliers.index') }}" class="inline-block mb-4 text-sm text-blue-600 hover:underline">
    الموردون
  </a>

  {{-- بقية محتوى الاستيراد --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white rounded-lg border p-4">محتوى توضيحي 1</div>
    <div class="bg-white rounded-lg border p-4">محتوى توضيحي 2</div>
  </div>
</div>
@endsection

