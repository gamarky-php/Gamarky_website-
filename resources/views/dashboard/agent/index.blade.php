@extends('layouts.app')
@section('title','الوكيل')

@section('content')
<div class="container mx-auto py-8" dir="rtl">
  <h1 class="text-2xl font-bold mb-4">الوكيل</h1>
  <p class="text-gray-600">هنا ستظهر أدوات وإعدادات قسم الوكلاء.</p>
  <a href="{{ route('admin.dashboard') }}" class="inline-block mt-4 text-blue-600 hover:underline">
    رجوع إلى لوحة التحكم
  </a>
</div>
@endsection
