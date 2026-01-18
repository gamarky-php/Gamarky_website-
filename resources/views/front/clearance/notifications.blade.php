@extends('layouts.front')
@section('title','الإشعارات والتقييم')
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-4">الإشعارات والتقييم</h1>
  <div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="font-semibold mb-2">سجل الإشعارات</h2>
      <ul class="list-disc mr-5 text-gray-700 space-y-1">
        <li>تم تحديد موعد الكشف يوم 12/11 10:30</li>
        <li>تم الإفراج عن الشحنة — رقم الحاوية MSKU1234567</li>
      </ul>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="font-semibold mb-2">لوحة التقييم</h2>
      <p class="text-gray-600">تقييم الموقع (آلي) + تقييم العميل (يدوي).</p>
    </div>
  </div>
</div>
@endsection
