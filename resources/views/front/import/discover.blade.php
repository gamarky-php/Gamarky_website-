@extends('layouts.front')

@section('title','اكتشف المورد')
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">اكتشف المورد</h1>
  @php($spec = optional(Auth::user())->specialization ?? optional(Auth::user())->activity ?? 'عام')
  <p class="mb-4">تصفية حسب تخصصك: <span class="font-semibold">{{ $spec }}</span></p>

  <div class="grid md:grid-cols-3 gap-4">
    <div class="p-4 rounded-lg border">
      <h2 class="font-semibold mb-2">مواد خام</h2>
      <p class="text-sm">موردون مرتبطون بتخصصك.</p>
    </div>
    <div class="p-4 rounded-lg border">
      <h2 class="font-semibold mb-2">معدات</h2>
      <p class="text-sm">علامات تجارية ووكلاء.</p>
    </div>
    <div class="p-4 rounded-lg border">
      <h2 class="font-semibold mb-2">خدمات لوجستية</h2>
      <p class="text-sm">شحن، تخليص، نقل داخلي.</p>
    </div>
  </div>
</div>
@endsection
