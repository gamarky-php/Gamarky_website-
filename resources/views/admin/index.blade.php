{{-- resources/views/admin/index.blade.php --}}
@extends('layouts.front')

@section('title', __('dashboard.dashboard'))

@section('dashboard')
<div class="min-h-[calc(100vh-200px)] bg-gray-50">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">

    {{-- Mobile top bar for menu button --}}
    <div class="md:hidden mb-4 flex justify-between items-center">
      <h1 class="text-lg font-semibold">{{ __('dashboard.dashboard') }}</h1>
      <button x-data @click="$dispatch('toggle-admin-sidebar')" class="px-3 py-1.5 rounded-xl bg-blue-600 text-white">
        {{ __('nav.menu') }}
      </button>
    </div>

    {{-- Grid layout: sidebar + content --}}
    <div class="grid grid-cols-1 md:grid-cols-[18rem_minmax(0,1fr)] gap-6">

      {{-- Sidebar --}}
      <aside x-data="{ open: true }"
             @toggle-admin-sidebar.window="open = !open"
             class="md:sticky md:top-24 md:self-start">
        {{-- On mobile: slide-over --}}
        <div class="md:hidden" x-show="open" x-transition>
          <div class="fixed inset-0 bg-black/30 z-40" @click="open=false"></div>
          <div class="fixed inset-y-0 end-0 w-72 bg-white z-50 shadow-xl p-4 overflow-y-auto">
            <x-admin-sidebar />
          </div>
        </div>

        {{-- On md and up: fixed box --}}
        <div class="hidden md:block w-72">
          <x-admin-sidebar />
        </div>
      </aside>

      {{-- Main content --}}
      <main class="min-w-0">
        {{-- Page heading --}}
        <div class="mb-6">
          <h2 class="text-xl font-semibold text-gray-800">{{ __('dashboard.admin.main_sections') }}</h2>
          <p class="text-sm text-gray-500 mt-1">{{ __('dashboard.admin.main_sections_desc') }}</p>
        </div>

        {{-- Services --}}
        <section class="mt-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-3">{{ __('dashboard.admin.services') }}</h3>
          <div class="overflow-hidden rounded-2xl bg-white shadow">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">{{ __('dashboard.admin.service') }}</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">{{ __('dashboard.admin.description') }}</th>
                  <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">‏</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @php
                  $services = [
                    ['name'=>__('nav.suppliers'),'desc'=>__('dashboard.admin.service_descriptions.suppliers'),'route'=>'admin.suppliers.index'],
                    ['name'=>__('dashboard.admin.services_list.import_suppliers'),'desc'=>__('dashboard.admin.service_descriptions.import_suppliers'),'route'=>'admin.suppliers.import'],
                    ['name'=>__('dashboard.admin.tariff.title'),'desc'=>__('dashboard.admin.service_descriptions.tariff'),'route'=>'admin.tariffs.index'],
                    ['name'=>__('dashboard.admin.services_list.customs_users'),'desc'=>__('dashboard.admin.service_descriptions.customs_users'),'route'=>'admin.customs.users'],
                    ['name'=>__('dashboard.admin.services_list.resident_cars'),'desc'=>__('dashboard.admin.service_descriptions.resident_cars'),'route'=>'admin.cars.index'],
                    ['name'=>__('dashboard.admin.containers.title'),'desc'=>__('dashboard.admin.service_descriptions.containers'),'route'=>'admin.containers.board'],
                    ['name'=>__('dashboard.admin.services_list.articles'),'desc'=>__('dashboard.admin.service_descriptions.articles'),'route'=>'admin.posts.index'],
                    ['name'=>__('dashboard.admin.ads.title'),'desc'=>__('dashboard.admin.service_descriptions.ads'),'route'=>'admin.ads.index'],
                    ['name'=>'Console / API','desc'=>__('dashboard.admin.service_descriptions.console_api'),'route'=>'admin.console.index'],
                  ];
                @endphp
                @foreach ($services as $s)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ $s['name'] }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $s['desc'] }}</td>
                    <td class="px-4 py-3 text-left">
                      <a href="{{ route($s['route']) }}" class="text-blue-700 hover:underline">{{ __('dashboard.admin.open') }}</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </div>
</div>

{{-- Small Blade component for sidebar (main sections) --}}
@once
  @push('components')
    @verbatim
    @endverbatim
  @endpush
@endonce

{{-- Define component inline in same file (without creating a new file) --}}
@php
  // simple hack to define inline Blade component
@endphp
<?php $__env->startComponent('components.dynamic', ['slot' => 'admin-sidebar']); ?>
<?php $__env->slot('slot'); ?>
  <div class="space-y-3">
    <div class="text-sm text-gray-500 mb-1">{{ __('dashboard.admin.main_sections') }}</div>
    @php
      $items = [
        ['label'=>__('nav.import'), 'route'=>'admin.import.index'],
        ['label'=>__('nav.export'), 'route'=>'admin.export.index'],
        ['label'=>__('nav.manufacturing'), 'route'=>'admin.manufacturing.index'],
        ['label'=>__('nav.customs_broker'), 'route'=>'admin.customs.index'],
        ['label'=>__('nav.containers'), 'route'=>'admin.containers.index'],
        ['label'=>__('nav.agent'), 'route'=>'admin.agents.index'],
      ];
    @endphp
    <nav class="space-y-1">
      @foreach ($items as $it)
        @php $active = request()->routeIs($it['route'].'*'); @endphp
        <a href="{{ route($it['route']) }}"
           class="block rounded-xl px-4 py-2.5 transition
                  {{ $active ? 'bg-blue-600 text-white shadow' : 'bg-white text-gray-800 shadow hover:shadow-md hover:bg-gray-50' }}">
          {{ $it['label'] }}
        </a>
      @endforeach
    </nav>
  </div>
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
@endsection
