<div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40" @click="open=false"></div>
<aside x-show="open" x-transition
       class="fixed top-0 bottom-0 {{ app()->getLocale()==='ar' ? 'left-0' : 'right-0' }}
              w-72 bg-white shadow-2xl z-[60] overflow-y-auto">
  <div class="p-4 border-b flex items-center justify-between">
    <span class="font-bold text-lg">{{ __('القائمة') }}</span>
    <button @click="open=false" aria-label="إغلاق" class="w-8 h-8 grid place-items-center rounded-md hover:bg-black/5">
      ✕
    </button>
  </div>
  <nav class="p-3 space-y-1 {{ app()->getLocale()==='ar' ? 'text-right' : 'text-left' }}">
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#import">{{ __('الاستيراد') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#export">{{ __('التصدير') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#broker">{{ __('المستخلص الجمركي') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#manufact">{{ __('التصنيع') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#agent">{{ __('الوكيل') }}</a>
    <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="#containers">{{ __('بورصة أسعار الحاويات') }}</a>
    <hr class="my-2">
    @auth
      <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('profile.show') }}">{{ __('حسابي') }}</a>
      <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
        @csrf
        <button class="w-full text-start rounded-lg hover:bg-black/5 px-0 py-0">{{ __('تسجيل الخروج') }}</button>
      </form>
    @else
      <a class="block px-3 py-2 rounded-lg hover:bg-black/5" href="{{ route('login') }}">{{ __('الدخول / التسجيل') }}</a>
    @endauth
  </nav>
</aside>
