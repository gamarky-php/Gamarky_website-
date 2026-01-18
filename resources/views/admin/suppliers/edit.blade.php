@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" dir="rtl">
  <h1 class="text-2xl font-bold">تعديل المورد</h1>

  <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="space-y-4">
    @csrf @method('PUT')

    <div>
      <label class="block mb-1">الاسم (Company Name)</label>
      <input name="company_name" value="{{ old('company_name', $supplier->company_name) }}" class="w-full border rounded p-2">
      @error('company_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-1">الدولة</label>
        <input name="country_code" value="{{ old('country_code', $supplier->country_code) }}" class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block mb-1">المدينة</label>
        <input name="city" value="{{ old('city', $supplier->city) }}" class="w-full border rounded p-2">
      </div>
    </div>

    <div>
      <label class="block mb-1">الحالة</label>
      <select name="status" class="w-full border rounded p-2">
        <option value="pending"  @selected(old('status',$supplier->status)==='pending')>قيد المراجعة</option>
        <option value="approved" @selected(old('status',$supplier->status)==='approved')>معتمد</option>
      </select>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">حفظ</button>
      <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 border rounded">إلغاء</a>
    </div>
  </form>
</div>
@endsection