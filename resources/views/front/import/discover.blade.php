@extends('layouts.front')

@section('title', __('front.import.discover.title'))
@section('content')
<div class="container mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">{{ __('front.import.discover.heading') }}</h1>
  @php
    $rawSpec = trim((string) (optional(Auth::user())->specialization ?? optional(Auth::user())->activity ?? ''));
    $normalizedSpec = mb_strtolower($rawSpec);
    $specKey = match ($normalizedSpec) {
      '', 'عام', 'general' => 'front.import.discover.general',
      'مواد خام', 'raw materials' => 'front.import.discover.raw_materials',
      'معدات', 'equipment' => 'front.import.discover.equipment',
      'خدمات لوجستية', 'logistics services' => 'front.import.discover.logistics_services',
      default => null,
    };
    $spec = $specKey ? __($specKey) : ($rawSpec !== '' ? $rawSpec : __('front.import.discover.general'));
  @endphp
  <p class="mb-4">{{ __('front.import.discover.filter_by_specialization') }} <span class="font-semibold">{{ $spec }}</span></p>

  <div class="grid md:grid-cols-3 gap-4">
    <div class="p-4 rounded-lg border">
      <h2 class="font-semibold mb-2">{{ __('front.import.discover.raw_materials') }}</h2>
      <p class="text-sm">{{ __('front.import.discover.raw_materials_desc') }}</p>
    </div>
    <div class="p-4 rounded-lg border">
      <h2 class="font-semibold mb-2">{{ __('front.import.discover.equipment') }}</h2>
      <p class="text-sm">{{ __('front.import.discover.equipment_desc') }}</p>
    </div>
    <div class="p-4 rounded-lg border">
      <h2 class="font-semibold mb-2">{{ __('front.import.discover.logistics_services') }}</h2>
      <p class="text-sm">{{ __('front.import.discover.logistics_services_desc') }}</p>
    </div>
  </div>
</div>
@endsection
