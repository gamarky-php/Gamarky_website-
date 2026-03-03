<div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40" @click="open=false"></div>
<aside x-show="open" x-transition
       class="fixed top-0 bottom-0 {{ app()->getLocale()==='ar' ? 'left-0' : 'right-0' }}
              w-72 bg-white shadow-2xl z-[60] overflow-y-auto">
  <div class="p-4 border-b flex items-center justify-between">
    <span class="font-bold text-lg">{{ __('nav.menu') }}</span>
    <button @click="open=false" aria-label="{{ __('nav.close_menu') }}" class="w-8 h-8 grid place-items-center rounded-md hover:bg-black/5">
      ✕
    </button>
  </div>
  <nav class="p-3 space-y-1 {{ app()->getLocale()==='ar' ? 'text-right' : 'text-left' }}">
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#import">{{ __('nav.import') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#export">{{ __('nav.export') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#broker">{{ __('nav.customs_broker') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#manufact">{{ __('nav.manufacturing') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#agent">{{ __('nav.agent') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#containers">{{ __('nav.containers') }}</a>
    <hr class="my-2">
    @auth
      <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('profile.show') }}">{{ __('auth.my_account') }}</a>
      <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
        @csrf
        <button class="w-full text-start rounded-lg hover:bg-black/5 px-0 py-0">{{ __('nav.logout') }}</button>
      </form>
    @else
      <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('login') }}">{{ __('nav.login_register') }}</a>
    @endauth
  </nav>
</aside>
