<header x-data="{ open:false }" class="sticky top-0 z-50 bg-[#143B6E] text-white">
  <div class="max-w-screen-md mx-auto px-4 h-14 flex items-center justify-between">
    <a href="{{ route('mobile.home') }}" class="font-extrabold text-2xl tracking-wide select-none">GAMARKY</a>
    <div class="flex items-center gap-3">
      {{-- Language button --}}
      <a href="{{ route('mobile.home', ['lang' => app()->getLocale() === 'ar' ? 'en' : 'ar']) }}"
         class="text-sm bg-white/10 hover:bg-white/20 px-2 py-1 rounded-md">
         {{ app()->getLocale()==='ar' ? 'En' : __('nav.arabic_short') }}
      </a>
      {{-- Menu button --}}
      <button @click="open=true" aria-label="{{ __('nav.open_menu') }}" class="w-9 h-9 grid place-items-center rounded-md bg-white/10 hover:bg-white/20">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </div>
  @include('partials.mobile-drawer')
</header>
