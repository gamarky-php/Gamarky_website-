@extends('layouts.front')

@section('title', __('front.manufacturing.raw_materials.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="container mx-auto px-4 py-8">
  {{-- Hero Section --}}
  <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl shadow-lg p-8 mb-8 text-white">
    <h1 class="text-3xl md:text-4xl font-bold mb-4">🏭 {{ __('front.manufacturing.raw_materials.heading') }}</h1>
    <p class="text-lg text-blue-100">{{ __('front.manufacturing.raw_materials.subtitle') }}</p>
  </div>

  {{-- Content Section --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Card 1 --}}
    <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-blue-600 hover:shadow-lg transition">
      <div class="flex items-start gap-4">
        <div class="text-4xl">📦</div>
        <div>
          <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('front.manufacturing.raw_materials.local_suppliers_title') }}</h3>
          <p class="text-slate-600 mb-3">{{ __('front.manufacturing.raw_materials.local_suppliers_desc') }}</p>
          <span class="text-sm text-blue-600 font-medium">{{ __('front.manufacturing.raw_materials.coming_soon') }}</span>
        </div>
      </div>
    </div>

    {{-- Card 2 --}}
    <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-green-600 hover:shadow-lg transition">
      <div class="flex items-start gap-4">
        <div class="text-4xl">🌍</div>
        <div>
          <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('front.manufacturing.raw_materials.import_title') }}</h3>
          <p class="text-slate-600 mb-3">{{ __('front.manufacturing.raw_materials.import_desc') }}</p>
          <span class="text-sm text-green-600 font-medium">{{ __('front.manufacturing.raw_materials.coming_soon') }}</span>
        </div>
      </div>
    </div>

    {{-- Card 3 --}}
    <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-purple-600 hover:shadow-lg transition">
      <div class="flex items-start gap-4">
        <div class="text-4xl">💰</div>
        <div>
          <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('front.manufacturing.raw_materials.compare_prices_title') }}</h3>
          <p class="text-slate-600 mb-3">{{ __('front.manufacturing.raw_materials.compare_prices_desc') }}</p>
          <span class="text-sm text-purple-600 font-medium">{{ __('front.manufacturing.raw_materials.coming_soon') }}</span>
        </div>
      </div>
    </div>

    {{-- Card 4 --}}
    <div class="bg-white rounded-xl shadow-md p-6 border-r-4 border-orange-600 hover:shadow-lg transition">
      <div class="flex items-start gap-4">
        <div class="text-4xl">✅</div>
        <div>
          <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('front.manufacturing.raw_materials.quality_title') }}</h3>
          <p class="text-slate-600 mb-3">{{ __('front.manufacturing.raw_materials.quality_desc') }}</p>
          <span class="text-sm text-orange-600 font-medium">{{ __('front.manufacturing.raw_materials.coming_soon') }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Info Notice --}}
  <div class="bg-blue-50 border-r-4 border-blue-600 rounded-lg p-6">
    <div class="flex items-start gap-3">
      <div class="text-2xl">ℹ️</div>
      <div>
        <h4 class="font-bold text-blue-900 mb-2">{{ __('front.manufacturing.raw_materials.in_development_title') }}</h4>
        <p class="text-blue-800 leading-relaxed">
          {{ __('front.manufacturing.raw_materials.in_development_desc') }}
        </p>
      </div>
    </div>
  </div>

  {{-- Related Links --}}
  <div class="mt-8 flex flex-wrap gap-4">
    <a href="{{ route('front.manufacturing.calculator') }}" 
       class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
      <span>📊</span>
      <span>{{ __('front.manufacturing.raw_materials.manufacturing_calculator') }}</span>
    </a>
    <a href="{{ route('front.import.calculator') }}" 
       class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
      <span>📦</span>
      <span>{{ __('front.manufacturing.raw_materials.import_calculator') }}</span>
    </a>
  </div>
</div>
@endsection
