{{-- resources/views/livewire/front/customs/customs-index.blade.php --}}
{{-- dir inherited from layout --}}
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
  
  {{-- Hero Section with Search --}}
  <section class="relative bg-gradient-to-l from-[#0F2E5D] via-[#1a3f6e] to-[#0F2E5D] text-white py-16 overflow-hidden">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 drop-shadow-lg">
          <i class="fas fa-search ml-3 text-yellow-400"></i>
          {{ __('front.clearance.index.hero_title') }}
        </h1>
        <p class="text-xl text-blue-100 max-w-3xl mx-auto">
          {{ __('front.clearance.index.hero_subtitle') }}
        </p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-sm">
          <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <i class="fas fa-shield-alt text-green-400"></i>
            <span>{{ __('front.clearance.index.badges.trusted') }}</span>
          </span>
          <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <i class="fas fa-star text-yellow-400"></i>
            <span>{{ __('front.clearance.index.badges.real_ratings') }}</span>
          </span>
          <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <i class="fas fa-clock text-blue-400"></i>
            <span>{{ __('front.clearance.index.badges.fast_response') }}</span>
          </span>
        </div>
      </div>

      {{-- Advanced Filters Card --}}
      <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl p-6 md:p-8">
        <form wire:submit.prevent="searchBrokers">
          {{-- Row 1: Main Search --}}
          <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2 text-right">
              <i class="fas fa-search text-blue-600 ml-2"></i>
              {{ __('front.clearance.index.search_label') }}
            </label>
            <input type="text" wire:model="search_query" 
                   placeholder="{{ __('front.clearance.index.search_placeholder') }}"
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
          </div>

          {{-- Row 2: Filters Grid --}}
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Country --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-flag ml-2 text-green-600"></i>{{ __('front.clearance.index.filters.country') }}
              </label>
              <select wire:model="country" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">{{ __('front.clearance.index.options.all_countries') }}</option>
                @foreach($countries as $code => $name)
                  <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
              </select>
            </div>

            {{-- Port --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-anchor ml-2 text-blue-600"></i>{{ __('front.clearance.index.filters.port') }}
              </label>
              <select wire:model="port" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">{{ __('front.clearance.index.options.all_ports') }}</option>
                @foreach($ports as $portName)
                  <option value="{{ $portName }}">{{ $portName }}</option>
                @endforeach
              </select>
            </div>

            {{-- Activity Type --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-boxes ml-2 text-purple-600"></i>{{ __('front.clearance.index.filters.activity_type') }}
              </label>
              <select wire:model="activity_type" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">{{ __('front.clearance.index.options.all_activities') }}</option>
                @foreach($activityTypes as $code => $name)
                  <option value="{{ $code }}">{{ $name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          {{-- Row 3: Advanced Filters --}}
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            {{-- Experience --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-award ml-2 text-amber-600"></i>{{ __('front.clearance.index.filters.experience_years') }}
              </label>
              <select wire:model="min_experience" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">{{ __('front.clearance.index.options.any_experience') }}</option>
                <option value="5">{{ __('front.clearance.index.options.exp_5_plus') }}</option>
                <option value="10">{{ __('front.clearance.index.options.exp_10_plus') }}</option>
                <option value="15">{{ __('front.clearance.index.options.exp_15_plus') }}</option>
              </select>
            </div>

            {{-- Rating --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-star ml-2 text-yellow-500"></i>{{ __('front.clearance.index.filters.rating') }}
              </label>
              <select wire:model="min_rating" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">{{ __('front.clearance.index.options.all_ratings') }}</option>
                <option value="4.5">{{ __('front.clearance.index.options.rating_4_5_plus') }}</option>
                <option value="4">{{ __('front.clearance.index.options.rating_4_0_plus') }}</option>
                <option value="3.5">{{ __('front.clearance.index.options.rating_3_5_plus') }}</option>
              </select>
            </div>

            {{-- Price Range --}}
            <div>
              <label class="block text-gray-700 font-medium mb-2 text-right text-sm">
                <i class="fas fa-dollar-sign ml-2 text-green-600"></i>{{ __('front.clearance.index.filters.price_range') }}
              </label>
              <select wire:model="price_range" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-right">
                <option value="">{{ __('front.clearance.index.options.any_price') }}</option>
                <option value="low">{{ __('front.clearance.index.options.price_low') }}</option>
                <option value="medium">{{ __('front.clearance.index.options.price_medium') }}</option>
                <option value="high">{{ __('front.clearance.index.options.price_high') }}</option>
              </select>
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="flex flex-wrap items-center gap-3 justify-center">
            <button type="submit" 
                    class="px-8 py-3 bg-gradient-to-l from-blue-600 to-blue-700 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 flex items-center gap-2">
              <i class="fas fa-search"></i>
              <span>{{ __('front.clearance.index.actions.advanced_search') }}</span>
            </button>
            <button type="button" wire:click="resetFilters"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center gap-2">
              <i class="fas fa-redo"></i>
              <span>{{ __('front.clearance.index.actions.reset') }}</span>
            </button>
          </div>
        </form>

        {{-- Results Counter --}}
        @if($searchPerformed && $total_results > 0)
          <div class="mt-6 pt-6 border-t border-gray-200 text-center">
            <p class="text-gray-600 font-medium">
              <i class="fas fa-check-circle text-green-500 ml-2"></i>
              {{ __('front.clearance.index.results_found', ['count' => $total_results]) }}
            </p>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- Results Section --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    @if(count($brokers) > 0)
      {{-- Sort & View Controls --}}
      <div class="flex flex-wrap items-center justify-between mb-8 bg-white rounded-xl shadow-sm p-4 gap-4">
        <div class="flex items-center gap-3">
          <label class="text-gray-600 font-medium text-sm">{{ __('front.clearance.index.sort.label') }}</label>
          <select wire:model="sort_by" class="px-4 py-2 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition text-sm">
            <option value="rating_desc">{{ __('front.clearance.index.sort.rating_desc') }}</option>
            <option value="experience_desc">{{ __('front.clearance.index.sort.experience_desc') }}</option>
            <option value="price_asc">{{ __('front.clearance.index.sort.price_asc') }}</option>
            <option value="reviews_desc">{{ __('front.clearance.index.sort.reviews_desc') }}</option>
          </select>
        </div>
        
        <div class="flex items-center gap-2">
          <button wire:click="$set('view_mode', 'grid')" 
                  class="p-2 rounded-lg {{ $view_mode === 'grid' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
            <i class="fas fa-th-large"></i>
          </button>
          <button wire:click="$set('view_mode', 'list')" 
                  class="p-2 rounded-lg {{ $view_mode === 'list' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition">
            <i class="fas fa-list"></i>
          </button>
        </div>
      </div>

      {{-- Brokers Grid --}}
      <div class="grid grid-cols-1 {{ $view_mode === 'grid' ? 'md:grid-cols-2 lg:grid-cols-3' : '' }} gap-6">
        @foreach($brokers as $broker)
          <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border-2 border-transparent hover:border-blue-300 {{ $broker['featured'] ? 'ring-2 ring-yellow-400' : '' }}">
            
            {{-- Featured Badge --}}
            @if($broker['featured'])
              <div class="bg-gradient-to-l from-yellow-400 to-amber-500 text-white px-4 py-2 text-center font-bold text-sm flex items-center justify-center gap-2">
                <i class="fas fa-crown"></i>
                <span>{{ __('front.clearance.index.featured_badge') }}</span>
              </div>
            @endif

            <div class="p-6">
              {{-- Header: Name & Badge --}}
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition">
                    {{ $broker['name'] }}
                  </h3>
                  <div class="flex flex-wrap items-center gap-2 mb-2">
                    @if($broker['verified'])
                      <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                        <i class="fas fa-check-circle"></i>
                        {{ $broker['badge'] }}
                      </span>
                    @endif
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                      <i class="fas fa-map-marker-alt"></i>
                      {{ $broker['country'] }}
                    </span>
                  </div>
                </div>
              </div>

              {{-- Rating & Reviews --}}
              <div class="flex flex-wrap items-center gap-3 mb-4 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-1">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star {{ $i <= floor($broker['rating']) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                  @endfor
                </div>
                <span class="font-bold text-gray-900">{{ $broker['rating'] }}</span>
                <span class="text-gray-500 text-sm">{{ __('front.clearance.index.review_count', ['count' => $broker['reviews_count']]) }}</span>
              </div>

              {{-- Stats Grid --}}
              <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                  <div class="text-2xl font-bold text-blue-600">{{ $broker['experience_years'] }}</div>
                  <div class="text-xs text-gray-600 mt-1">{{ __('front.clearance.index.stats.experience_year') }}</div>
                </div>
                <div class="bg-green-50 rounded-xl p-3 text-center">
                  <div class="text-2xl font-bold text-green-600">{{ $broker['success_rate'] }}%</div>
                  <div class="text-xs text-gray-600 mt-1">{{ __('front.clearance.index.stats.success_rate') }}</div>
                </div>
              </div>

              {{-- Port & Response Time --}}
              <div class="space-y-2 mb-4 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                  <i class="fas fa-anchor text-blue-500"></i>
                  <span>{{ $broker['port'] }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                  <i class="fas fa-clock text-orange-500"></i>
                  <span>{{ __('front.clearance.index.stats.response_time') }}: <strong class="text-gray-900">{{ $broker['response_time'] }}</strong></span>
                </div>
                <div class="flex items-center gap-2 text-gray-600">
                  <i class="fas fa-dollar-sign text-green-500"></i>
                  <span>{{ __('front.clearance.index.stats.estimated_price') }}: <strong class="text-gray-900">{{ $broker['avg_price'] }}</strong></span>
                </div>
              </div>

              {{-- Specialties --}}
              <div class="mb-4">
                <div class="flex flex-wrap gap-2">
                  @foreach($broker['specialties'] as $specialty)
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                      {{ $specialty }}
                    </span>
                  @endforeach
                </div>
              </div>

              {{-- CTA Buttons --}}
              <div class="flex flex-col gap-2 pt-4 border-t border-gray-100">
                <button class="w-full py-3 bg-gradient-to-l from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                  <i class="fas fa-rocket"></i>
                  <span>{{ __('front.clearance.index.actions.start_with_broker') }}</span>
                </button>
                <button class="w-full py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-2">
                  <i class="fas fa-file-alt"></i>
                  <span>{{ __('front.clearance.index.actions.full_profile') }}</span>
                </button>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination Placeholder --}}
      <div class="mt-12 flex justify-center">
        <div class="bg-white rounded-xl shadow-md px-6 py-3">
          <p class="text-gray-500 text-sm">
            <i class="fas fa-info-circle text-blue-500 ml-2"></i>
            {{ __('front.clearance.index.pagination_todo') }}
          </p>
        </div>
      </div>

    @else
      {{-- Empty State --}}
      <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
          <i class="fas fa-search text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-700 mb-3">{{ __('front.clearance.index.empty_state.title') }}</h3>
        <p class="text-gray-500 mb-6">{{ __('front.clearance.index.empty_state.subtitle') }}</p>
        <button wire:click="resetFilters" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">
          <i class="fas fa-redo ml-2"></i>
          {{ __('front.clearance.index.actions.search_again') }}
        </button>
      </div>
    @endif

  </section>

  {{-- Informational Banner --}}
  <section class="bg-gradient-to-l from-indigo-600 to-purple-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h2 class="text-3xl font-bold mb-4">{{ __('front.clearance.index.cta.title') }}</h2>
      <p class="text-xl text-indigo-100 mb-6 max-w-2xl mx-auto">
        {{ __('front.clearance.index.cta.subtitle') }}
      </p>
      <a href="{{ route('front.customs.register') }}" 
         class="inline-flex items-center gap-2 px-8 py-4 bg-white text-indigo-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-200">
        <i class="fas fa-user-plus"></i>
        <span>{{ __('front.clearance.index.cta.button') }}</span>
      </a>
    </div>
  </section>

</div>
