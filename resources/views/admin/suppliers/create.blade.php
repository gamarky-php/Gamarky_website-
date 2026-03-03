@extends('layouts.app')

@section('title', __('dashboard.admin.suppliers.create.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold mb-4">{{ __('dashboard.admin.suppliers.create.heading') }}</h1>

  <form action="{{ route('admin.suppliers.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.name') }}</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required />
        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.slug') }}</label>
        <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border rounded p-2" />
        @error('slug') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.country_code') }}</label>
        <input type="text" name="country_code" value="{{ old('country_code') }}" class="w-full border rounded p-2" maxlength="2" required />
        @error('country_code') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.city') }}</label>
        <input type="text" name="city" value="{{ old('city') }}" class="w-full border rounded p-2" />
        @error('city') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.contact_name') }}</label>
        <input type="text" name="contact_name" value="{{ old('contact_name') }}" class="w-full border rounded p-2" />
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2" />
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.email') }}</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded p-2" />
      </div>

      <div>
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.website') }}</label>
        <input type="url" name="website" value="{{ old('website') }}" class="w-full border rounded p-2" />
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.categories') }}</label>
        <input type="text" name="categories" value="{{ old('categories') }}" class="w-full border rounded p-2" placeholder="{{ __('dashboard.admin.suppliers.create.categories_placeholder') }}" />
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium">{{ __('dashboard.admin.suppliers.create.fields.description') }}</label>
        <textarea name="description" class="w-full border rounded p-2" rows="4">{{ old('description') }}</textarea>
      </div>

      <div class="md:col-span-2 flex items-center gap-4">
        <label class="inline-flex items-center"><input type="checkbox" name="approved" value="1" class="mr-2"> {{ __('dashboard.admin.suppliers.create.flags.approved') }}</label>
        <label class="inline-flex items-center"><input type="checkbox" name="featured" value="1" class="mr-2"> {{ __('dashboard.admin.suppliers.create.flags.featured') }}</label>
      </div>

    </div>

    <div class="mt-4">
      <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('dashboard.admin.suppliers.create.save') }}</button>
      <a href="{{ route('admin.suppliers.index') }}" class="ms-3 text-gray-600">{{ __('dashboard.admin.suppliers.create.cancel') }}</a>
    </div>
  </form>
</div>
@endsection

