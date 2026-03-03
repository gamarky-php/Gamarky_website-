{{-- Navbar - RTL - Tailwind + AlpineJS --}}
{{-- dir inherited from layout --}}
<header class="bg-[#0F2E5D] text-white sticky top-0 z-50 shadow-lg backdrop-blur-sm bg-opacity-95">
  <div class="max-w-7xl mx-auto px-4 lg:px-6 h-[70px] flex items-center justify-between gap-4">

    {{-- Logo --}}
    <a href="{{ url('/mardini/public') }}"
       class="flex flex-col justify-center leading-tight select-none">
      <span class="text-xl md:text-2xl font-extrabold tracking-tight text-white">
        {{ __('nav.brand') }}
      </span>
      <span class="text-[11px] md:text-xs text-blue-100/90 -mt-0.5">
        {{ __('nav.tagline') }}
      </span>
    </a>

    {{-- Navigation Links --}}
    <nav class="hidden lg:flex items-center gap-1 flex-1">

    {{-- 1) Import (Dropdown) --}}
@if (Route::has('front.import.index'))
  <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
    <button type="button"
       class="px-4 py-2 rounded-lg text-white hover:bg-blue-800 transition flex items-center gap-1">
      {{ __('nav.import') }}
      <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
      </svg>
    </button>
    <div x-cloak x-show="open" x-transition
         class="absolute start-0 mt-1 w-56 rounded-lg bg-white text-gray-800 shadow-lg ring-1 ring-black/5 overflow-hidden z-50">
      @if (Route::has('front.import.calculator'))
        <a href="{{ route('front.import.calculator') }}"
           class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
          {{ __('nav.import_calculator') }}
        </a>
      @endif
      @if (Route::has('front.import.procedures'))
        <a href="{{ route('front.import.procedures') }}"
           class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
          {{ __('nav.import_procedures') }}
        </a>
      @endif
      @if (Route::has('front.import.discover'))
        <a href="{{ route('front.import.discover') }}"
           class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
          {{ __('nav.discover_supplier') }}
        </a>
      @endif
      {{-- Tariff link removed --}}
    </div>
  </div>
@endif


      {{-- 2) Export (Dropdown) --}}
      @if (Route::has('front.export.index'))
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
          <button type="button"
             class="px-4 py-2 rounded-lg text-white hover:bg-blue-800 transition flex items-center gap-1">
            {{ __('nav.export') }}
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div x-cloak x-show="open" x-transition
               class="absolute start-0 mt-1 w-56 rounded-lg bg-white text-gray-800 shadow-lg ring-1 ring-black/5 overflow-hidden z-50">
            @if (Route::has('front.export.calculator'))
              <a href="{{ route('front.export.calculator') }}"
                 class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
                {{ __('nav.export_calculator') }}
              </a>
            @endif
            @if (Route::has('front.export.procedures'))
              <a href="{{ route('front.export.procedures') }}"
                 class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
                {{ __('nav.export_procedures') }}
              </a>
            @endif
            @if (Route::has('front.export.markets'))
              <a href="{{ route('front.export.markets') }}"
                 class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
                {{ __('nav.discover_markets') }}
              </a>
            @endif
            @if (Route::has('front.export.tariffs'))
              <a href="{{ route('front.export.tariffs') }}"
                 class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm">
                {{ __('nav.tariffs') }}
              </a>
            @endif
          </div>
        </div>
      @endif

      {{-- 3) Manufacturing (Dropdown) --}}
      @if (Route::has('front.manufacturing.index'))
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
          <button type="button"
             class="px-4 py-2 rounded-lg text-white hover:bg-blue-800 transition flex items-center gap-1 {{ request()->routeIs('front.manufacturing.*') ? 'bg-blue-800 font-semibold' : '' }}">
            {{ __('nav.manufacturing') }}
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div x-cloak x-show="open" x-transition
               class="absolute start-0 mt-1 w-56 rounded-lg bg-white text-gray-800 shadow-lg ring-1 ring-black/5 overflow-hidden z-50">
            @if (Route::has('front.manufacturing.calculator'))
              <a href="{{ route('front.manufacturing.calculator') }}"
                 class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm {{ request()->routeIs('front.manufacturing.calculator') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
                {{ __('nav.manufacturing_calculator') }}
              </a>
            @endif
            @if (Route::has('front.manufacturing.raw-materials'))
              <a href="{{ route('front.manufacturing.raw-materials') }}"
                 class="block px-4 py-2.5 hover:bg-gray-100 transition text-sm {{ request()->routeIs('front.manufacturing.raw-materials') ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
                {{ __('nav.raw_materials') }}
              </a>
            @endif
          </div>
        </div>
      @endif

      {{-- 4) Customs Broker (Dropdown) --}}
      @if (Route::has('front.customs.index'))
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
          <button type="button"
             class="px-4 py-2 rounded-lg text-white hover:bg-blue-800 transition flex items-center gap-1 {{ request()->routeIs('front.customs.*') ? 'bg-blue-800 font-semibold ring-2 ring-blue-400' : '' }}">
            {{ __('nav.customs_broker') }}
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div x-cloak x-show="open" x-transition
               class="absolute right-0 mt-2 w-64 rounded-2xl bg-white text-gray-800 shadow-xl ring-1 ring-black/5 overflow-hidden z-[60] backdrop-blur-lg">
            @if (Route::has('front.customs.index'))
              <a href="{{ route('front.customs.index') }}" 
                 class="block px-4 py-2.5 hover:bg-blue-50 transition text-sm {{ request()->routeIs('front.customs.index') ? 'bg-blue-50 text-blue-700 font-semibold border-r-4 border-blue-600' : '' }}">
                <i class="fas fa-search text-blue-500 ml-2"></i>{{ __('nav.search_broker') }}
              </a>
            @endif
            @if (Route::has('front.customs.role'))
              <a href="{{ route('front.customs.role') }}" 
                 class="block px-4 py-2.5 hover:bg-green-50 transition text-sm {{ request()->routeIs('front.customs.role') ? 'bg-green-50 text-green-700 font-semibold border-r-4 border-green-600' : '' }}">
                <i class="fas fa-user-tie text-green-500 ml-2"></i>{{ __('nav.broker_role') }}
              </a>
            @endif
            @if (Route::has('front.customs.notifications'))
              <a href="{{ route('front.customs.notifications') }}" 
                 class="block px-4 py-2.5 hover:bg-orange-50 transition text-sm {{ request()->routeIs('front.customs.notifications') ? 'bg-orange-50 text-orange-700 font-semibold border-r-4 border-orange-600' : '' }}">
                <i class="fas fa-bell text-orange-500 ml-2"></i>{{ __('nav.notifications_rating') }}
              </a>
            @endif
          </div>
        </div>
      @endif

      {{-- 5) Container & Freight Exchange (Dropdown) --}}
      @if (Route::has('front.shipping.quote'))
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
          <button type="button"
             class="px-4 py-2 rounded-lg text-white hover:bg-blue-800 transition flex items-center gap-1">
            {{ __('nav.containers') }}
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div x-cloak x-show="open" x-transition
               class="absolute start-0 mt-1 w-56 rounded-lg bg-white text-gray-800 shadow-lg ring-1 ring-black/5 overflow-hidden z-50">
            @if (Route::has('front.shipping.quote'))
              <a href="{{ route('front.shipping.quote') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                <i class="fas fa-calculator ml-2 text-teal-500"></i>{{ __('nav.container_quote') }}
              </a>
            @endif
            @if (Route::has('front.shipping.book'))
              <a href="{{ route('front.shipping.book') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                <i class="fas fa-box ml-2 text-blue-500"></i>{{ __('nav.book_container') }}
              </a>
            @endif
            @if (Route::has('front.shipping.track-container'))
              <a href="{{ route('front.shipping.track-container') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                <i class="fas fa-map-marker-alt ml-2 text-red-500"></i>{{ __('nav.track_container') }}
              </a>
            @endif
            <div class="border-t border-gray-200 my-1"></div>
            @if (Route::has('front.shipping.truck-quote'))
              <a href="{{ route('front.shipping.truck-quote') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                <i class="fas fa-truck ml-2 text-teal-500"></i>{{ __('nav.truck_quote') }}
              </a>
            @endif
            @if (Route::has('front.shipping.book-truck'))
              <a href="{{ route('front.shipping.book-truck') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                <i class="fas fa-shipping-fast ml-2 text-blue-500"></i>{{ __('nav.book_truck') }}
              </a>
            @endif
            @if (Route::has('front.shipping.track-truck'))
              <a href="{{ route('front.shipping.track-truck') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                <i class="fas fa-route ml-2 text-red-500"></i>{{ __('nav.track_truck') }}
              </a>
            @endif
          </div>
        </div>
      @endif

      {{-- 6) Agent (Dropdown) --}}
      @if (Route::has('front.agent.index'))
        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
          <button type="button"
             class="px-4 py-2 rounded-lg text-white hover:bg-blue-800 transition flex items-center gap-1">
            {{ __('nav.agent') }}
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div x-cloak x-show="open" x-transition
               class="absolute start-0 mt-1 w-56 rounded-lg bg-white text-gray-800 shadow-lg ring-1 ring-black/5 overflow-hidden z-50">
            @if (Route::has('front.agent.shipping'))
              <a href="{{ route('front.agent.shipping') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                🚚 {{ __('nav.shipping_agent') }}
              </a>
            @endif
            @if (Route::has('front.agent.brand'))
              <a href="{{ route('front.agent.brand') }}" class="block px-4 py-2.5 hover:bg-gray-100 text-sm">
                🏆 {{ __('nav.brand_agents') }}
              </a>
            @endif
          </div>
        </div>
      @endif

    </nav>

    {{-- Auth Buttons - right side in RTL --}}
    <div class="flex items-center gap-3">
      
      {{-- Language Switcher Dropdown - ALWAYS VISIBLE --}}
      <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
                class="inline-flex items-center gap-2 px-4 py-2 min-h-[40px] rounded-xl bg-slate-700 text-white border border-white/70 ring-1 ring-white/20 hover:border-white hover:ring-white/35 hover:bg-white/10 transition duration-200 ease-out text-sm font-semibold shadow-[0_0_0_1px_rgba(255,255,255,0.12)] whitespace-nowrap leading-none">
          🌐 {{ __('nav.languages') }}
          <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
          </svg>
        </button>

        {{-- Language Dropdown Menu --}}
        <div x-cloak x-show="open" @click.outside="open = false" x-transition
             class="absolute end-0 mt-2 w-44 rounded-xl bg-white shadow-xl ring-1 ring-black/10 overflow-hidden z-50">
          <a href="{{ route('locale.switch', 'ar') }}"
             class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition {{ app()->getLocale() === 'ar' ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
            <span>🇸🇦</span>
            {{ __('nav.arabic') }}
          </a>
          <a href="{{ route('locale.switch', 'zh') }}"
             class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-t border-gray-100 {{ app()->getLocale() === 'zh' ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
            <span>🇨🇳</span>
            {{ __('nav.chinese') }}
          </a>
          <a href="{{ route('locale.switch', 'en') }}"
             class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-t border-gray-100 {{ app()->getLocale() === 'en' ? 'bg-blue-50 text-blue-700 font-semibold' : '' }}">
            <span>🇬🇧</span>
            {{ __('nav.english') }}
          </a>
        </div>
      </div>

      @auth
        {{-- User Dropdown --}}
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open"
                  class="flex items-center gap-2 rounded-xl px-3 py-2 text-white hover:bg-blue-800 transition">
            {{-- Avatar --}}
            @php
              $user = Auth::user();
              $avatar = method_exists($user, 'profile_photo_url')
                ? $user->profile_photo_url
                : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff';
            @endphp
              <img src="{{ $avatar }}" alt="{{ __('nav.avatar_alt') }}"
                 class="h-9 w-9 rounded-full ring-2 ring-white/30 object-cover">
            <span class="hidden md:block text-white text-sm font-medium truncate max-w-[120px]">
              {{ $user->name }}
            </span>
            <svg class="h-4 w-4 text-white/90 hidden md:block" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>

          {{-- Dropdown Menu --}}
          <div x-cloak x-show="open" @click.outside="open = false" x-transition
               class="absolute end-0 mt-2 w-56 rounded-xl bg-white shadow-xl ring-1 ring-black/10 overflow-hidden z-50">
            <div class="px-4 py-3 border-b border-gray-100">
              <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
              <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
            </div>

            <nav class="py-1">
              {{-- Admin dashboard only --}}
              @if(Auth::check() && method_exists(Auth::user(), 'is_admin') && Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                  <span class="text-blue-600">🏢</span>
                  {{ __('nav.dashboard') }}
                </a>
              @endif

              {{-- Profile --}}
              @if (Route::has('profile.show'))
                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                  <span class="text-gray-600">👤</span>
                  {{ __('nav.profile') }}
                </a>
              @endif

              <hr class="my-1 border-gray-100">

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition text-start">
                  <span>🚪</span>
                  {{ __('nav.logout') }}
                </button>
              </form>
            </nav>
          </div>
        </div>
      @else
        {{-- Guest: Login & Register Dropdown --}}
        <div x-data="{ open: false }" class="relative">
          <button @click="open = !open"
                  class="inline-flex items-center gap-2 px-4 py-2 min-h-[40px] rounded-xl bg-[#153E7E] text-white border border-white/70 ring-1 ring-white/20 hover:border-white hover:ring-white/35 hover:bg-white/10 transition duration-200 ease-out text-sm font-semibold shadow-[0_0_0_1px_rgba(255,255,255,0.12)] whitespace-nowrap leading-none">
            {{ __('nav.login') }}
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>

          {{-- Dropdown Menu --}}
          <div x-cloak x-show="open" @click.outside="open = false" x-transition
               class="absolute end-0 mt-2 w-48 rounded-xl bg-white shadow-xl ring-1 ring-black/10 overflow-hidden z-50">
            @if (Route::has('login'))
              <a href="{{ route('login') }}"
                 class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                <span class="text-blue-600">🔑</span>
                {{ __('nav.login') }}
              </a>
            @endif
            @if (Route::has('register'))
              <a href="{{ route('register') }}"
                 class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition border-t border-gray-100">
                <span class="text-green-600">✨</span>
                {{ __('nav.register') }}
              </a>
            @endif
          </div>
        </div>
      @endauth

      {{-- Mobile Menu Button --}}
      <button @click="$dispatch('toggle-mobile-menu')"
              class="lg:hidden p-2 rounded-lg text-white hover:bg-blue-800 transition">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

  </div>
</header>

{{-- Mobile Menu (AlpineJS) --}}
<div x-data="{ mobileOpen: false }"
     @toggle-mobile-menu.window="mobileOpen = !mobileOpen"
     x-show="mobileOpen"
     x-cloak
     class="lg:hidden fixed inset-0 z-50 bg-black/50"
     @click="mobileOpen = false">

  <div @click.stop class="absolute end-0 top-0 h-full w-72 bg-blue-900 text-white shadow-xl overflow-y-auto">

    {{-- Close Button --}}
    <div class="flex items-center justify-between p-4 border-b border-white/10">
      <span class="text-lg font-bold text-white">{{ __('nav.menu') }}</span>
      <button @click="mobileOpen = false" class="p-2 rounded-lg text-white hover:bg-blue-800 transition">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    {{-- Mobile Links --}}
    <nav class="p-4 space-y-2">
      @if (Route::has('front.import.calculator'))
        <div class="px-4 py-2 text-white/70 text-xs font-semibold uppercase tracking-wide">{{ __('nav.import') }}</div>
        <a href="{{ route('front.import.calculator') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
          {{ __('nav.import_calculator') }}
        </a>
      @endif
      @if (Route::has('front.export.calculator'))
        <div class="px-4 py-2 text-white/70 text-xs font-semibold uppercase tracking-wide">{{ __('nav.export') }}</div>
        <a href="{{ route('front.export.calculator') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
          {{ __('nav.export_calculator') }}
        </a>
      @endif
      @if (Route::has('front.manufacturing.calculator'))
        <div class="px-4 py-2 text-white/70 text-xs font-semibold uppercase tracking-wide">{{ __('nav.manufacturing') }}</div>
        <a href="{{ route('front.manufacturing.calculator') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
          {{ __('nav.manufacturing_calculator') }}
        </a>
        @if (Route::has('front.manufacturing.raw-materials'))
          <a href="{{ route('front.manufacturing.raw-materials') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
            {{ __('nav.raw_materials') }}
          </a>
        @endif
      @endif
      @if (Route::has('front.customs.index'))
        {{-- Mobile Accordion for Customs --}}
        <div x-data="{ customsOpen: false }" class="space-y-1">
          <button @click="customsOpen = !customsOpen" 
                  class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-white hover:bg-blue-800 transition {{ request()->routeIs('front.customs.*') ? 'bg-blue-800 font-semibold' : '' }}">
            <span>{{ __('nav.customs_broker') }}</span>
            <svg class="h-5 w-5 transition-transform" :class="customsOpen ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
            </svg>
          </button>
          <div x-show="customsOpen" x-cloak x-transition class="space-y-1 pr-4">
            @if (Route::has('front.customs.index'))
              <a href="{{ route('front.customs.index') }}" 
                 class="block px-4 py-2.5 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm {{ request()->routeIs('front.customs.index') ? 'bg-blue-800 font-semibold' : '' }}">
                <i class="fas fa-search text-blue-300 ml-2"></i>{{ __('nav.search_broker') }}
              </a>
            @endif
            @if (Route::has('front.customs.role'))
              <a href="{{ route('front.customs.role') }}" 
                 class="block px-4 py-2.5 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm {{ request()->routeIs('front.customs.role') ? 'bg-blue-800 font-semibold' : '' }}">
                <i class="fas fa-user-tie text-green-300 ml-2"></i>{{ __('nav.broker_role') }}
              </a>
            @endif
            @if (Route::has('front.customs.notifications'))
              <a href="{{ route('front.customs.notifications') }}" 
                 class="block px-4 py-2.5 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm {{ request()->routeIs('front.customs.notifications') ? 'bg-blue-800 font-semibold' : '' }}">
                <i class="fas fa-bell text-orange-300 ml-2"></i>{{ __('nav.notifications_rating') }}
              </a>
            @endif
          </div>
        </div>
      @endif
      @if (Route::has('front.shipping.quote'))
        <div class="px-4 py-2 text-white/70 text-xs font-semibold uppercase tracking-wide">{{ __('nav.containers') }}</div>
        <a href="{{ route('front.shipping.quote') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
          {{ __('nav.container_quote') }}
        </a>
      @endif
      @if (Route::has('front.agent.shipping'))
        <div class="px-4 py-2 text-white/70 text-xs font-semibold uppercase tracking-wide">{{ __('nav.agent') }}</div>
        <a href="{{ route('front.agent.shipping') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
          🚚 {{ __('nav.shipping_agent') }}
        </a>
        @if (Route::has('front.agent.brand'))
          <a href="{{ route('front.agent.brand') }}" class="block px-4 py-2 pr-8 rounded-lg text-white/90 hover:bg-blue-800 transition text-sm">
            🏆 {{ __('nav.brand_agents') }}
          </a>
        @endif
      @endif
    </nav>
  </div>
</div>
