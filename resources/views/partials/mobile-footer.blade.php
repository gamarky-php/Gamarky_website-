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
      <div class="font-semibold">{{ __('المستخلص الجمركي') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('حاسبة الجمارك') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('صفحات إجرائية لاحقاً') }}</a></li>
      </ul>
    </div>
    <div>
      <div class="font-semibold">{{ __('التصنيع') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('حاسبة التصنيع') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('ربط جمركي لاحق') }}</a></li>
      </ul>
    </div>
    <div>
      <div class="font-semibold">{{ __('التصدير') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('حاسبة التصدير') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('استكشاف الأسواق') }}</a></li>
      </ul>
    </div>
    <div>
      <div class="font-semibold">{{ __('بورصة الحاويات والنقل') }}</div>
      <ul class="mt-1 space-y-1 opacity-90">
        <li><a href="#" class="hover:underline">{{ __('أسعار الحاويات') }}</a></li>
        <li><a href="#" class="hover:underline">{{ __('اختيار الحاوية') }}</a></li>
      </ul>
    </div>
  </div>
  <div class="mt-4 flex items-center justify-between text-xs opacity-90">
    <div class="space-x-3 space-x-reverse">
      <a href="#" class="hover:underline">{{ __('سياسة الخصوصية') }}</a>
      <span class="opacity-50">•</span>
      <a href="#" class="hover:underline">{{ __('شروط الاستخدام') }}</a>
    </div>
    <div>© {{ now()->year }} {{ __('جمــاركي') }}</div>
  </div>
</footer>
