@extends('layouts.front')
@section('title','تسجيل مستخلص')
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-4">تسجيل مستخلص جديد</h1>
  <form class="space-y-4 max-w-3xl">
    <input class="input input-bordered w-full" placeholder="الاسم التجاري">
    <div class="grid md:grid-cols-2 gap-3">
      <input class="input input-bordered w-full" placeholder="الدولة/المدينة">
      <input class="input input-bordered w-full" placeholder="الموانئ المغطاة">
    </div>
    <textarea class="textarea textarea-bordered w-full" rows="4" placeholder="التخصصات والملاحظات"></textarea>
    <button type="button" class="btn btn-primary">إرسال للمراجعة</button>
  </form>
</div>
@endsection
