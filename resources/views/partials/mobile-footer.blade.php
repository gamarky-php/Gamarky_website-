<footer class="mt-6 rounded-2xl p-4 text-white"
        style="background:linear-gradient(180deg,#143B6E, #0F2F56)">
  <div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/10 text-sm">
      <span class="text-sm">🛡️</span> GDPR
    </span>
    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/10 text-sm">
      <span class="text-sm">✅</span> ISO 27001
    </span>
  </div>
  <div class="mt-4 grid grid-cols-2 gap-4 text-sm {{ app()->getLocale()==='ar' ? 'text-right' : 'text-left' }}">
    <div>
      <div class="font-semibold">{{ __('nav.customs_broker') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('footer.customs_calculator') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('footer.procedural_pages_soon') }}</a></li>
      </ul>
    </div>
    <div>
      <div class="font-semibold">{{ __('footer.manufacturing') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('footer.manufacturing_calculator') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('footer.customs_link_soon') }}</a></li>
      </ul>
    </div>
    <div>
      <div class="font-semibold">{{ __('footer.export') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('footer.export_calculator') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('footer.explore_markets') }}</a></li>
      </ul>
    </div>
    <div>
      <div class="font-semibold">{{ __('nav.containers') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('footer.container_prices') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('footer.choose_container') }}</a></li>
      </ul>
    </div>
  </div>
  <div class="mt-4 flex items-center justify-between text-xs opacity-90">
    <div class="space-x-3 space-x-reverse">
      <a href="#" class="hover:underline">{{ __('footer.privacy_policy') }}</a>
      <span class="opacity-50">•</span>
      <a href="#" class="hover:underline">{{ __('footer.terms_of_use') }}</a>
    </div>
    <div>© {{ now()->year }} {{ __('footer.brand') }}</div>
  </div>
</footer>
