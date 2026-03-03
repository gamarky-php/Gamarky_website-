{{-- resources/views/livewire/front/customs/customs-role.blade.php --}}
{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
  
  {{-- Hero Section --}}
  <section class="relative bg-gradient-to-l from-[#0F2E5D] via-[#1a3f6e] to-[#0F2E5D] text-white py-20 overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 rounded-full mb-6 backdrop-blur-sm">
        <i class="fas fa-user-tie text-4xl text-yellow-400"></i>
      </div>
      <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
        {{ __('front.clearance.role.hero_title') }}
      </h1>
      <p class="text-xl text-blue-100 max-w-3xl mx-auto mb-8">
        {{ __('front.clearance.role.hero_subtitle') }}
      </p>
      <div class="flex flex-wrap items-center justify-center gap-4 text-sm">
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-shield-check text-green-400"></i>
          <span>{{ __('front.clearance.role.badges.officially_certified') }}</span>
        </span>
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-bolt text-yellow-400"></i>
          <span>{{ __('front.clearance.role.badges.fast_procedures') }}</span>
        </span>
        <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
          <i class="fas fa-headset text-blue-400"></i>
          <span>{{ __('front.clearance.role.badges.continuous_support') }}</span>
        </span>
      </div>
    </div>
  </section>

  {{-- Services Grid --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
        <i class="fas fa-briefcase text-blue-600 ml-3"></i>
        {{ __('front.clearance.role.services_title') }}
      </h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        {{ __('front.clearance.role.services_subtitle') }}
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($services as $service)
        @php
          $colorMap = [
            'blue' => 'from-blue-500 to-blue-600',
            'green' => 'from-green-500 to-green-600',
            'purple' => 'from-purple-500 to-purple-600',
            'teal' => 'from-teal-500 to-teal-600',
            'orange' => 'from-orange-500 to-orange-600',
            'red' => 'from-red-500 to-red-600',
          ];
          $gradient = $colorMap[$service['color']] ?? 'from-gray-500 to-gray-600';
        @endphp
        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-{{ $service['color'] }}-300">
          <div class="bg-gradient-to-l {{ $gradient }} p-6 text-white">
            <div class="flex items-center gap-4 mb-3">
              <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                <i class="fas {{ $service['icon'] }} text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold flex-1">{{ $service['title'] }}</h3>
            </div>
          </div>
          <div class="p-6">
            <p class="text-gray-600 leading-relaxed">{{ $service['description'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- Timeline Section: Customs Clearance Stages --}}
  <section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          <i class="fas fa-route text-purple-600 ml-3"></i>
          {{ __('front.clearance.role.timeline_title') }}
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          {{ __('front.clearance.role.timeline_subtitle') }}
        </p>
      </div>

      {{-- Timeline --}}
      <div class="relative">
        {{-- Vertical Line (hidden on mobile, shown on md+) --}}
        <div class="hidden md:block absolute right-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-600 via-purple-600 to-indigo-600 transform translate-x-1/2"></div>

        {{-- Timeline Items --}}
        <div class="space-y-12">
          @foreach($clearanceStages as $index => $stage)
            @php
              $isEven = $index % 2 === 0;
              $colorMap = [
                'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'border' => 'border-blue-600', 'gradient' => 'from-blue-500 to-blue-600'],
                'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-600', 'gradient' => 'from-green-500 to-green-600'],
                'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-600', 'gradient' => 'from-purple-500 to-purple-600'],
                'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'border' => 'border-orange-600', 'gradient' => 'from-orange-500 to-orange-600'],
                'teal' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-600', 'border' => 'border-teal-600', 'gradient' => 'from-teal-500 to-teal-600'],
                'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600', 'border' => 'border-indigo-600', 'gradient' => 'from-indigo-500 to-indigo-600'],
              ];
              $colors = $colorMap[$stage['color']] ?? $colorMap['blue'];
            @endphp

            <div class="relative flex items-center {{ $isEven ? 'md:flex-row-reverse' : '' }}">
              
              {{-- Timeline Circle (Center) --}}
              <div class="hidden md:flex absolute right-1/2 transform translate-x-1/2 items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br {{ $colors['gradient'] }} text-white shadow-lg z-10">
                <i class="fas {{ $stage['icon'] }} text-2xl"></i>
              </div>

              {{-- Stage Card --}}
              <div class="w-full md:w-[calc(50%-4rem)] {{ $isEven ? 'md:pl-16' : 'md:pr-16' }}">
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 {{ $colors['border'] }}">
                  
                  {{-- Header --}}
                  <div class="bg-gradient-to-l {{ $colors['gradient'] }} text-white p-6">
                    <div class="flex items-center gap-4 mb-3">
                      <div class="md:hidden w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class="fas {{ $stage['icon'] }} text-2xl"></i>
                      </div>
                      <div class="flex-1">
                        <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold mb-2">
                          {{ __('front.clearance.role.stage_of', ['current' => $stage['id'], 'total' => 6]) }}
                        </span>
                        <h3 class="text-2xl font-bold">{{ $stage['title'] }}</h3>
                      </div>
                    </div>
                    <p class="text-white/90 text-sm">{{ $stage['description'] }}</p>
                  </div>

                  {{-- Body --}}
                  <div class="p-6">
                    {{-- Typical Time --}}
                    <div class="flex items-center gap-3 mb-4 p-4 {{ $colors['bg'] }} rounded-xl">
                      <div class="flex items-center justify-center w-10 h-10 {{ $colors['text'] }} bg-white rounded-lg">
                        <i class="fas fa-clock text-xl"></i>
                      </div>
                      <div class="flex-1">
                        <div class="text-xs text-gray-600 font-medium">{{ __('front.clearance.role.standard_time') }}</div>
                        <div class="text-lg font-bold {{ $colors['text'] }}">{{ $stage['typical_time'] }}</div>
                      </div>
                    </div>

                    {{-- Documents Checklist --}}
                    <div class="mb-4">
                      <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-list-check {{ $colors['text'] }}"></i>
                        <span>{{ __('front.clearance.role.requirements_documents') }}</span>
                      </h4>
                      <ul class="space-y-2">
                        @foreach($stage['documents'] as $doc)
                          <li class="flex items-start gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle {{ $colors['text'] }} mt-0.5"></i>
                            <span>{{ $doc }}</span>
                          </li>
                        @endforeach
                      </ul>
                    </div>

                    {{-- @todo Placeholders --}}
                    <div class="pt-4 border-t border-gray-200 space-y-2">
                      <button class="w-full py-2 px-4 bg-gradient-to-l {{ $colors['gradient'] }} text-white rounded-lg font-semibold hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-upload"></i>
                        <span>{{ __('front.clearance.role.upload_attachments_todo') }}</span>
                      </button>
                      <button class="w-full py-2 px-4 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-bell"></i>
                        <span>{{ __('front.clearance.role.enable_notifications_todo') }}</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Total Estimated Time --}}
      <div class="mt-12 text-center">
        <div class="inline-flex items-center gap-4 bg-gradient-to-l from-blue-600 to-purple-600 text-white rounded-2xl px-8 py-4 shadow-xl">
          <i class="fas fa-hourglass-half text-3xl"></i>
          <div class="text-right">
            <div class="text-sm font-medium opacity-90">{{ __('front.clearance.role.total_estimated_time') }}</div>
            <div class="text-2xl font-bold">{{ __('front.clearance.role.total_estimated_time_value') }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Benefits Section --}}
  <section class="bg-gradient-to-br from-indigo-50 to-purple-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
          <i class="fas fa-star text-yellow-500 ml-3"></i>
          {{ __('front.clearance.role.benefits_title') }}
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          {{ __('front.clearance.role.benefits_subtitle') }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($benefits as $benefit)
          @php
            $colorMap = [
              'yellow' => ['bg' => 'from-yellow-400 to-amber-500', 'icon' => 'text-yellow-600'],
              'green' => ['bg' => 'from-green-400 to-emerald-500', 'icon' => 'text-green-600'],
            ];
            $colors = $colorMap[$benefit['color']] ?? $colorMap['green'];
          @endphp
          <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
            <div class="p-8 text-center">
              <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br {{ $colors['bg'] }} rounded-full mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas {{ $benefit['icon'] }} text-3xl text-white"></i>
              </div>
              <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $benefit['title'] }}</h3>
              <p class="text-gray-600 leading-relaxed">{{ $benefit['description'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="bg-gradient-to-l from-blue-600 to-purple-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ __('front.clearance.role.cta_title') }}</h2>
      <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
        {{ __('front.clearance.role.cta_subtitle') }}
      </p>
      <div class="flex flex-wrap items-center justify-center gap-4">
        <a href="{{ route('front.customs.index') }}" 
           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
          <i class="fas fa-search"></i>
          <span>{{ __('front.clearance.role.cta_find_broker') }}</span>
        </a>
        <a href="{{ route('front.customs.register') }}" 
           class="inline-flex items-center gap-2 px-8 py-4 bg-transparent border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-blue-600 transition-all duration-200">
          <i class="fas fa-user-plus"></i>
          <span>{{ __('front.clearance.role.cta_register_broker') }}</span>
        </a>
      </div>
    </div>
  </section>

</div>
