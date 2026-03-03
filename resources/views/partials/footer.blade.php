{{-- dir inherited from layout --}}
<footer class="bg-[#143B6E] text-white mt-12 pt-8 pb-6">
  <div class="mx-auto max-w-7xl px-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      <div class="md:col-span-1">
        <div class="rounded-2xl bg-white/5 p-5 shadow-inner">
          <div class="text-2xl font-extrabold">{{ __('footer.brand') }}</div>
          <div class="text-sm text-slate-200/80 mt-1">{{ __('footer.tagline') }}</div>
        </div>
      </div>

      <div>
        <h3 class="font-semibold mb-3">{{ __('footer.customs_user') }}</h3>
        <ul class="space-y-2 text-slate-100/90">
          <li><a class="hover:underline" href="{{ url('/customs/calculator') }}">{{ __('footer.customs_calculator') }}</a></li>
          <li><a class="hover:underline" href="{{ url('/customs/next-steps') }}">{{ __('footer.procedural_pages_soon') }}</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-semibold mb-3">{{ __('footer.manufacturing') }}</h3>
        <ul class="space-y-2 text-slate-100/90">
          <li><a class="hover:underline" href="{{ url('/manufacturing/calculator') }}">{{ __('footer.manufacturing_calculator') }}</a></li>
          <li><a class="hover:underline" href="{{ url('/manufacturing/customs-link') }}">{{ __('footer.customs_link_soon') }}</a></li>
        </ul>
      </div>

      <div>
        <h3 class="font-semibold mb-3">{{ __('footer.export') }}</h3>
        <ul class="space-y-2 text-slate-100/90">
          <li><a class="hover:underline" href="{{ url('/export/calculator') }}">{{ __('footer.export_calculator') }}</a></li>
          <li><a class="hover:underline" href="{{ url('/export/markets') }}">{{ __('footer.explore_markets') }}</a></li>
        </ul>
      </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-white/10 mt-8 pt-4 text-sm text-slate-100/80">
      <div class="flex items-center gap-2">
        <span class="inline-block rounded-full border border-white/20 px-2 py-0.5">{{ __('footer.iso_badge') }}</span>
        <span class="inline-block rounded-full border border-white/20 px-2 py-0.5">{{ __('footer.gdpr_badge') }}</span>
      </div>
      <div class="flex items-center gap-4">
        <a class="hover:underline" href="{{ url('/privacy') }}">{{ __('footer.privacy_policy') }}</a>
        <span>{{ __('footer.separator_dot') }}</span>
        <a class="hover:underline" href="{{ url('/terms') }}">{{ __('footer.terms_of_use') }}</a>
      </div>
      <div>{{ __('footer.copyright', ['year' => date('Y')]) }}</div>
    </div>
  </div>
</footer>

