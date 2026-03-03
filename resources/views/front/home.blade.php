@extends('layouts.front')

@section('title', __('common.home_title'))

@section('content')
  <div class="container mx-auto px-4 py-12">
    {{-- Hero Section --}}
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
      <h1 class="text-4xl font-bold text-[#143B6E] mb-4">{{ __('common.home_hero_title') }}</h1>
      <p class="text-lg text-slate-600 mb-6">{{ __('common.home_hero_subtitle') }}</p>
      <div class="flex gap-4 justify-center">
        <a href="#services" class="bg-[#143B6E] text-white px-6 py-3 rounded-lg hover:bg-[#1D4ED8] transition">{{ __('common.home_explore_services') }}</a>
        <a href="{{ url('/register') }}" class="bg-white text-[#143B6E] border-2 border-[#143B6E] px-6 py-3 rounded-lg hover:bg-slate-50 transition">{{ __('common.home_start_now') }}</a>
      </div>
    </div>

    {{-- Services Grid --}}
    <div id="services" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">📦</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('common.home_import_title') }}</h3>
        <p class="text-slate-600 mb-4">{{ __('common.home_import_desc') }}</p>
        <a href="{{ route('front.import.calculator') }}" class="text-[#2563EB] hover:underline">{{ __('common.home_learn_more') }}</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🚢</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('common.home_export_title') }}</h3>
        <p class="text-slate-600 mb-4">{{ __('common.home_export_desc') }}</p>
        <a href="{{ route('front.export.calculator') }}" class="text-[#2563EB] hover:underline">{{ __('common.home_learn_more') }}</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🏭</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('common.home_manufacturing_title') }}</h3>
        <p class="text-slate-600 mb-4">{{ __('common.home_manufacturing_desc') }}</p>
        <a href="{{ route('front.manufacturing.calculator') }}" class="text-[#2563EB] hover:underline">{{ __('common.home_learn_more') }}</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">🧾</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('common.home_customs_user_title') }}</h3>
        <p class="text-slate-600 mb-4">{{ __('common.home_customs_user_desc') }}</p>
        <a href="{{ route('front.customs.index') }}" class="text-[#2563EB] hover:underline">{{ __('common.home_learn_more') }}</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">👤</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('common.home_agent_title') }}</h3>
        <p class="text-slate-600 mb-4">{{ __('common.home_agent_desc') }}</p>
        <a href="{{ route('front.agent.shipping') }}" class="text-[#2563EB] hover:underline">{{ __('common.home_learn_more') }}</a>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
        <div class="text-3xl mb-4">📊</div>
        <h3 class="text-xl font-bold text-[#143B6E] mb-2">{{ __('common.home_containers_title') }}</h3>
        <p class="text-slate-600 mb-4">{{ __('common.home_containers_desc') }}</p>
        <a href="{{ route('front.shipping.quote') }}" class="text-[#2563EB] hover:underline">{{ __('common.home_learn_more') }}</a>
      </div>
    </div>
  </div>
@endsection
