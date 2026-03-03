@extends('layouts.front')

@section('title', __('front.agent.brand.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    {{-- Hero Section --}}
    <section class="bg-gradient-to-l from-purple-600 to-indigo-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    {{ __('front.agent.brand.hero_title') }}
                </h1>
                <p class="text-xl md:text-2xl text-purple-100 mb-8">
                    {{ __('front.agent.brand.hero_subtitle') }}
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm md:text-base">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        {{ __('front.agent.brand.hero_badge_1') }}
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        {{ __('front.agent.brand.hero_badge_2') }}
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        {{ __('front.agent.brand.hero_badge_3') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-12 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">{{ \App\Models\BrandAgencyRequest::count() }}+</div>
                    <div class="text-gray-600">{{ __('front.agent.brand.kpi_requests') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ \App\Models\BrandAgencyRequest::where('decision', 'accepted')->count() }}</div>
                    <div class="text-gray-600">{{ __('front.agent.brand.kpi_accepted') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ \App\Models\Brand::count() }}+</div>
                    <div class="text-gray-600">{{ __('front.agent.brand.kpi_brands') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Brands Grid (Sample) --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                    {{ __('front.agent.brand.brands_section_title') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    {{-- Sample Brand Cards --}}
                    @php
                        $sampleBrands = [
                            ['name' => 'TechVision', 'sector' => __('front.agent.brand.sector_electronics'), 'countries' => [__('front.agent.brand.country_saudi'), __('front.agent.brand.country_uae')], 'color' => 'blue'],
                            ['name' => 'FreshFood Co.', 'sector' => __('front.agent.brand.sector_food_beverage'), 'countries' => [__('front.agent.brand.country_egypt'), __('front.agent.brand.country_jordan')], 'color' => 'green'],
                            ['name' => 'FashionStyle', 'sector' => __('front.agent.brand.sector_fashion'), 'countries' => [__('front.agent.brand.country_kuwait'), __('front.agent.brand.country_qatar')], 'color' => 'pink'],
                            ['name' => 'HomeComfort', 'sector' => __('front.agent.brand.sector_furniture'), 'countries' => [__('front.agent.brand.country_saudi'), __('front.agent.brand.country_bahrain')], 'color' => 'amber'],
                            ['name' => 'BeautyGlow', 'sector' => __('front.agent.brand.sector_cosmetics'), 'countries' => [__('front.agent.brand.country_uae'), __('front.agent.brand.country_lebanon')], 'color' => 'purple'],
                            ['name' => 'SportsPro', 'sector' => __('front.agent.brand.sector_sports'), 'countries' => [__('front.agent.brand.country_oman'), __('front.agent.brand.country_saudi')], 'color' => 'red'],
                        ];
                    @endphp

                    @foreach($sampleBrands as $brand)
                        <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-{{ $brand['color'] }}-100 rounded-full flex items-center justify-center text-{{ $brand['color'] }}-600 font-bold text-xl mr-3">
                                    {{ substr($brand['name'], 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800">{{ $brand['name'] }}</h3>
                                    <p class="text-xs text-gray-500">{{ $brand['sector'] }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">{{ __('front.agent.brand.required_countries') }}</span>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($brand['countries'] as $country)
                                            <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $country }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <button class="w-full bg-{{ $brand['color'] }}-600 text-white py-2 rounded-lg hover:bg-{{ $brand['color'] }}-700 transition text-sm font-medium">
                                {{ __('front.agent.brand.apply_agency') }}
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 text-center">
                    <p class="text-blue-800 font-medium mb-2">{{ __('front.agent.brand.brand_not_listed_title') }}</p>
                    <p class="text-blue-600 text-sm">{{ __('front.agent.brand.brand_not_listed_subtitle') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Application Form Section --}}
    <section class="py-16 bg-gradient-to-l from-purple-50 to-indigo-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">
                        {{ __('front.agent.brand.form_title') }}
                    </h2>
                    <p class="text-gray-600 text-lg">
                        {{ __('front.agent.brand.form_subtitle') }}
                    </p>
                </div>

                <livewire:brand-agency-request-form />
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    {{ __('front.agent.brand.how_it_works_title') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6">
                        <div class="text-4xl mb-4">📄</div>
                        <h3 class="font-bold text-gray-800 mb-3">{{ __('front.agent.brand.step1_title') }}</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>{{ __('front.agent.brand.step1_item1') }}</li>
                            <li>{{ __('front.agent.brand.step1_item2') }}</li>
                            <li>{{ __('front.agent.brand.step1_item3') }}</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                        <div class="text-4xl mb-4">⚖️</div>
                        <h3 class="font-bold text-gray-800 mb-3">{{ __('front.agent.brand.step2_title') }}</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>{{ __('front.agent.brand.step2_item1') }}</li>
                            <li>{{ __('front.agent.brand.step2_item2') }}</li>
                            <li>{{ __('front.agent.brand.step2_item3') }}</li>
                        </ul>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6">
                        <div class="text-4xl mb-4">✅</div>
                        <h3 class="font-bold text-gray-800 mb-3">{{ __('front.agent.brand.step3_title') }}</h3>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li>{{ __('front.agent.brand.step3_item1') }}</li>
                            <li>{{ __('front.agent.brand.step3_item2') }}</li>
                            <li>{{ __('front.agent.brand.step3_item3') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 bg-gradient-to-l from-amber-50 to-yellow-50 rounded-2xl p-8 border-2 border-amber-200">
                    <div class="flex items-start">
                        <div class="text-4xl ml-4">💡</div>
                        <div>
                            <h3 class="font-bold text-amber-800 mb-3">{{ __('front.agent.brand.tips_title') }}</h3>
                            <ul class="text-amber-700 space-y-2">
                                <li>{{ __('front.agent.brand.tip1') }}</li>
                                <li>{{ __('front.agent.brand.tip2') }}</li>
                                <li>{{ __('front.agent.brand.tip3') }}</li>
                                <li>{{ __('front.agent.brand.tip4') }}</li>
                                <li>{{ __('front.agent.brand.tip5') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits Section --}}
    <section class="py-16 bg-gradient-to-l from-indigo-50 to-purple-50">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    {{ __('front.agent.brand.benefits_title') }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">🌍</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.brand.benefit1_title') }}</h3>
                            <p class="text-gray-600 text-sm">{{ __('front.agent.brand.benefit1_desc') }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">📈</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.brand.benefit2_title') }}</h3>
                            <p class="text-gray-600 text-sm">{{ __('front.agent.brand.benefit2_desc') }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">🤝</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.brand.benefit3_title') }}</h3>
                            <p class="text-gray-600 text-sm">{{ __('front.agent.brand.benefit3_desc') }}</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-start">
                        <div class="text-3xl ml-4">⚡</div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.brand.benefit4_title') }}</h3>
                            <p class="text-gray-600 text-sm">{{ __('front.agent.brand.benefit4_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Paid Services Section --}}
    <section class="py-16 bg-white border-t">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">
                    {{ __('front.agent.brand.paid_services_title') }}
                </h2>
                <p class="text-center text-gray-600 mb-12">{{ __('front.agent.brand.paid_services_subtitle') }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="text-4xl mb-4">👨‍💼</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ __('front.agent.brand.service1_title') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('front.agent.brand.service1_desc') }}</p>
                        <a href="#" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            {{ __('front.agent.brand.service1_cta') }}
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="text-4xl mb-4">📚</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ __('front.agent.brand.service2_title') }}</h3>
                        <p class="text-gray-600 mb-4">{{ __('front.agent.brand.service2_desc') }}</p>
                        <a href="#" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                            {{ __('front.agent.brand.service2_cta') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-gradient-to-l from-purple-600 to-indigo-700 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">{{ __('front.agent.brand.cta_title') }}</h2>
            <p class="text-xl text-purple-100 mb-8">{{ __('front.agent.brand.cta_subtitle') }}</p>
            <a href="#" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-bold hover:bg-purple-50 transition">
                {{ __('front.agent.brand.cta_button') }}
            </a>
        </div>
    </section>
</div>
@endsection
