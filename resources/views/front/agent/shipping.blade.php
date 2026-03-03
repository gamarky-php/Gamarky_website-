@extends('layouts.front')

@section('title', __('front.agent.shipping.title'))

@section('content')
{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    {{-- Hero Section --}}
    <section class="bg-gradient-to-l from-blue-600 to-blue-700 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    {{ __('front.agent.shipping.hero_title') }}
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8">
                    {{ __('front.agent.shipping.hero_subtitle') }}
                </p>
                <div class="flex flex-wrap justify-center gap-4 text-sm md:text-base">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        {{ __('front.agent.shipping.badge_1') }}
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        {{ __('front.agent.shipping.badge_2') }}
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-6 py-3">
                        {{ __('front.agent.shipping.badge_3') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- KPIs Section --}}
    <section class="py-12 bg-white border-b">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ \App\Models\Agent::count() }}+</div>
                    <div class="text-gray-600">{{ __('front.agent.shipping.kpi_agents') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">24</div>
                    <div class="text-gray-600">{{ __('front.agent.shipping.kpi_response') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">92%</div>
                    <div class="text-gray-600">{{ __('front.agent.shipping.kpi_ontime') }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Search Component --}}
    <section class="py-12">
        <div class="container mx-auto px-4">
            <livewire:agent.shipping.search />
        </div>
    </section>

    {{-- Points System Info --}}
    <section class="py-16 bg-gradient-to-l from-amber-50 to-yellow-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">
                            {{ __('front.agent.shipping.points_title') }}
                        </h2>
                        <p class="text-gray-600 text-lg">
                            {{ __('front.agent.shipping.points_subtitle') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                            <div class="text-3xl ml-4">📦</div>
                            <div>
                                <div class="font-bold text-gray-800">{{ __('front.agent.shipping.point_collect_goods') }}</div>
                                <div class="text-sm text-gray-600">{{ __('front.agent.shipping.one_point') }}</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                            <div class="text-3xl ml-4">🏪</div>
                            <div>
                                <div class="font-bold text-gray-800">{{ __('front.agent.shipping.point_safe_storage') }}</div>
                                <div class="text-sm text-gray-600">{{ __('front.agent.shipping.one_point') }}</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                            <div class="text-3xl ml-4">🚛</div>
                            <div>
                                <div class="font-bold text-gray-800">{{ __('front.agent.shipping.point_load_container') }}</div>
                                <div class="text-sm text-gray-600">{{ __('front.agent.shipping.one_point') }}</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                            <div class="text-3xl ml-4">📄</div>
                            <div>
                                <div class="font-bold text-gray-800">{{ __('front.agent.shipping.point_complete_docs') }}</div>
                                <div class="text-sm text-gray-600">{{ __('front.agent.shipping.two_points') }}</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                            <div class="text-3xl ml-4">🌐</div>
                            <div>
                                <div class="font-bold text-gray-800">{{ __('front.agent.shipping.point_upload_cargox') }}</div>
                                <div class="text-sm text-gray-600">{{ __('front.agent.shipping.two_points') }}</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4 flex items-center">
                            <div class="text-3xl ml-4">✈️</div>
                            <div>
                                <div class="font-bold text-gray-800">{{ __('front.agent.shipping.point_send_importer') }}</div>
                                <div class="text-sm text-gray-600">{{ __('front.agent.shipping.one_point') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-l from-green-50 to-emerald-50 rounded-xl p-6 border-2 border-green-200">
                        <div class="flex items-start">
                            <div class="text-3xl ml-4">💡</div>
                            <div>
                                <h3 class="font-bold text-green-800 mb-2">{{ __('front.agent.shipping.full_transparency') }}</h3>
                                <p class="text-green-700 text-sm">
                                    {{ __('front.agent.shipping.full_transparency_desc') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">
                    {{ __('front.agent.shipping.how_platform_works') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            1️⃣
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.shipping.step_1_title') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('front.agent.shipping.step_1_desc') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            2️⃣
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.shipping.step_2_title') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('front.agent.shipping.step_2_desc') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            3️⃣
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.shipping.step_3_title') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('front.agent.shipping.step_3_desc') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            4️⃣
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">{{ __('front.agent.shipping.step_4_title') }}</h3>
                        <p class="text-sm text-gray-600">{{ __('front.agent.shipping.step_4_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 bg-gradient-to-l from-blue-600 to-blue-700 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">{{ __('front.agent.shipping.cta_title') }}</h2>
            <p class="text-xl text-blue-100 mb-8">{{ __('front.agent.shipping.cta_subtitle') }}</p>
            <a href="#" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-blue-50 transition">
                {{ __('front.agent.shipping.cta_button') }}
            </a>
        </div>
    </section>
</div>
@endsection
