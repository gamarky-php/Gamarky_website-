@extends('layouts.front')
@section('title','مراجعة الموردين قبل الاعتماد')
@section('content')
<div class="max-w-5xl mx-auto p-6">
  <h1 class="text-xl font-bold mb-4">مراجعة الموردين</h1>
  <div class="bg-white rounded-xl shadow p-4">
    <p class="text-sm text-gray-600 mb-4">عرض بيانات تجريبية/مؤقتة هنا...</p>
    <form method="POST" action="{{ route('admin.suppliers.import.approve') }}">
      @csrf
      <button class="px-4 py-2 bg-green-600 text-white rounded">اعتماد</button>
    </form>
  </div>
</div>
@endsection

