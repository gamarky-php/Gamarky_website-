@extends('layouts.front')

@section('title','الموردون')

@section('content')
<div class="container mx-auto px-4 py-8" dir="rtl">
  <h1 class="text-2xl font-bold mb-4">الموردون</h1>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($suppliers as $supplier)
      <div class="bg-white rounded-lg border p-4">
        <div class="flex items-start gap-4">
          @if($supplier->logo_path)
            <img src="{{ asset($supplier->logo_path) }}" alt="{{ $supplier->name }}" class="w-16 h-16 object-cover rounded" />
          @endif
          <div class="flex-1 text-right">
            <h3 class="font-semibold">{{ $supplier->name }}</h3>
            <div class="text-sm text-gray-600">{{ $supplier->city }} - {{ $supplier->country_code }}</div>
            <p class="text-sm text-gray-700 mt-2">{{ Str::limit($supplier->description, 120) }}</p>
            <div class="mt-3">
              <a href="#" class="text-blue-600 hover:underline text-sm">عرض التفاصيل</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <p class="text-gray-600">لا توجد موردون معتمدون حالياً.</p>
    @endforelse
  </div>

  <div class="mt-6">
    {{ $suppliers->links() }}
  </div>
</div>
@endsection

