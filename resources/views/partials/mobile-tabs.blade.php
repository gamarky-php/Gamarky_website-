<nav class="fixed bottom-0 inset-x-0 z-50 bg-white border-t shadow-lg">
  <ul class="max-w-screen-md mx-auto px-2 py-1 grid grid-cols-4 gap-2 text-slate-700 text-xs">
    <li>
      <a href="{{ route('mobile.home') }}" class="flex flex-col items-center py-2 hover:text-[#143B6E] transition">
        <x-icon-home class="w-6 h-6 mb-0.5" />
        <span>{{ __('الرئيسية') }}</span>
      </a>
    </li>
    <li>
      <a href="#invoice" class="flex flex-col items-center py-2 hover:text-[#143B6E] transition">
        <x-icon-receipt class="w-6 h-6 mb-0.5" />
        <span>{{ __('الفاتورة') }}</span>
      </a>
    </li>
    <li>
      <a href="@auth {{ route('profile.show') }} @else {{ route('login') }} @endauth" 
         class="flex flex-col items-center py-2 hover:text-[#143B6E] transition">
        <x-icon-user class="w-6 h-6 mb-0.5" />
        <span>{{ __('حسابي') }}</span>
      </a>
    </li>
    <li>
      <a href="#alerts" class="flex flex-col items-center py-2 hover:text-[#143B6E] transition relative">
        <x-icon-bell class="w-6 h-6 mb-0.5" />
        {{-- Badge للإشعارات --}}
        <span class="absolute top-1 {{ app()->getLocale()==='ar' ? 'left-6' : 'right-6' }} w-2 h-2 bg-red-500 rounded-full"></span>
        <span>{{ __('الإشعارات') }}</span>
      </a>
    </li>
  </ul>
</nav>
