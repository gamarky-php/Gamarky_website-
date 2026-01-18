<footer class="bg-[#143B6E] text-white mt-12 pt-8 pb-6" dir="rtl">
  <div class="mx-auto max-w-7xl px-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <div class="md:col-span-1">
        <div class="rounded-2xl bg-white/5 p-5 shadow-inner">
          <div class="text-2xl font-extrabold">جماركي</div>
          <div class="text-sm text-slate-200/80 mt-1">منصة رجال الأعمال</div>
        </div>
      </div>

      <div>
        <h3 class="font-semibold mb-3">المستخدم الجمركي</h3>
        <ul class="space-y-2 text-slate-100/90">
          <li><a class="hover:underline" href="{{ url('/customs/calculator') }}">حاسبة الجمارك</a></li>
          <li><a class="hover:underline" href="{{ url('/customs/next-steps') }}">صفحات إجرائية لاحقًا</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-semibold mb-3">التصنيع</h3>
        <ul class="space-y-2 text-slate-100/90">
          <li><a class="hover:underline" href="{{ url('/manufacturing/calculator') }}">حاسبة التصنيع</a></li>
          <li><a class="hover:underline" href="{{ url('/manufacturing/customs-link') }}">ربط جمركي لاحق</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-semibold mb-3">التصدير</h3>
        <ul class="space-y-2 text-slate-100/90">
          <li><a class="hover:underline" href="{{ url('/export/calculator') }}">حاسبة التصدير</a></li>
          <li><a class="hover:underline" href="{{ url('/export/markets') }}">استكشاف الأسواق</a></li>
        </ul>
      </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-white/10 mt-8 pt-4 text-sm text-slate-100/80">
      <div class="flex items-center gap-2">
        <span class="inline-block rounded-full border border-white/20 px-2 py-0.5">ISO 27001</span>
        <span class="inline-block rounded-full border border-white/20 px-2 py-0.5">GDPR</span>
      </div>
      <div class="flex items-center gap-4">
        <a class="hover:underline" href="{{ url('/privacy') }}">سياسة الخصوصية</a>
        <span>•</span>
        <a class="hover:underline" href="{{ url('/terms') }}">شروط الاستخدام</a>
      </div>
      <div>© {{ date('Y') }} جماركي — جميع الحقوق محفوظة</div>
    </div>
  </div>
</footer>

