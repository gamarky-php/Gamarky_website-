@extends('layouts.app')

@section('content')
{{-- dir inherited from layout --}}
<div class="max-w-3xl mx-auto space-y-6">
  <h1 class="text-2xl font-bold">{{ __('dashboard.admin.suppliers.edit.heading') }}</h1>

  <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="space-y-4">
    @csrf @method('PUT')

    <div>
      <label class="block mb-1">{{ __('dashboard.admin.suppliers.edit.fields.company_name') }}</label>
      <input name="company_name" value="{{ old('company_name', $supplier->company_name) }}" class="w-full border rounded p-2">
      @error('company_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block mb-1">{{ __('dashboard.admin.suppliers.edit.fields.country') }}</label>
        <input name="country_code" value="{{ old('country_code', $supplier->country_code) }}" class="w-full border rounded p-2">
      </div>
      <div>
        <label class="block mb-1">{{ __('dashboard.admin.suppliers.edit.fields.city') }}</label>
        <input name="city" value="{{ old('city', $supplier->city) }}" class="w-full border rounded p-2">
      </div>
    </div>

    <div>
      <label class="block mb-1">{{ __('dashboard.admin.suppliers.edit.fields.status') }}</label>
      <select name="status" class="w-full border rounded p-2">
        <option value="pending"  @selected(old('status',$supplier->status)==='pending')>{{ __('dashboard.admin.suppliers.index.status.pending') }}</option>
        <option value="approved" @selected(old('status',$supplier->status)==='approved')>{{ __('dashboard.admin.suppliers.index.status.approved') }}</option>
      </select>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">{{ __('dashboard.admin.suppliers.create.save') }}</button>
      <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 border rounded">{{ __('dashboard.admin.suppliers.create.cancel') }}</a>
    </div>
  </form>
</div>
@endsection