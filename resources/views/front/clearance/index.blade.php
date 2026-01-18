@extends('layouts.front')
@section('title','استكشاف المستخلص المناسب')
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-2">استكشاف المستخلص المناسب</h1>
  <p class="text-gray-600 mb-6">فلترة حسب (المدينة، الميناء، نوع البضاعة، متوسط زمن التخليص، نسبة الالتزام بالـSLA).</p>

  <div class="grid md:grid-cols-3 gap-3 mb-6">
    <input class="input input-bordered w-full" placeholder="المدينة">
    <input class="input input-bordered w-full" placeholder="الميناء">
    <input class="input input-bordered w-full" placeholder="نوع البضاعة">
  </div>

  <div class="bg-white rounded-2xl shadow p-5">
    <div class="flex items-center justify-between">
      <div>
        <div class="font-semibold">مستخلص مثال</div>
        <div class="text-sm text-gray-500">متوسط الزمن: 4.2 يوم • ميناء: الإسكندرية</div>
      </div>
      <div class="text-sm text-gray-600">SLA 92%</div>
    </div>
    <div class="mt-3">
      <a href="#" class="text-primary hover:underline">تفاصيل وتقييمات</a>
    </div>
  </div>
</div>
@endsection
