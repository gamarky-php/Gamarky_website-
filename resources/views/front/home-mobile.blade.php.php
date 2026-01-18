<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>جماركي (موبايل)</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-dvh">
  <div class="mx-auto max-w-sm min-h-dvh flex flex-col" x-data="{menu:false}">
    {{-- Topbar --}}
    <header class="bg-[#123f78] text-white px-4 pt-[calc(env(safe-area-inset-top,0)+8px)] pb-2">
      <div class="flex items-center justify-between">
        <a href="{{ url('/') }}" class="text-3xl font-black tracking-wide select-none">GAMARKY</a>
        <button @click="menu=!menu" class="p-2 rounded-lg hover:bg-white/10">
          <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
      <div class="mt-3">
        <label class="relative block">
          <span class="absolute inset-y-0 left-3 flex items-center text-white/70">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-width="2" d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/></svg>
          </span>
          <input class="w-full rounded-xl bg-white/10 text-white placeholder-white/70 pr-3 pl-10 py-2 outline-none focus:ring-2 focus:ring-white/60" type="search" placeholder="...البحث">
        </label>
      </div>
      <div x-show="menu" x-transition class="mt-3 bg-white/95 backdrop-blur rounded-xl p-3 shadow text-slate-800 space-y-2">
        <a href="#" class="block py-2">تغيير اللغة</a>
        <a href="{{ route('login') }}" class="block py-2">تسجيل الدخول</a>
        <a href="{{ route('register') }}" class="block py-2">حساب جديد</a>
      </div>
    </header>

    {{-- المحتوى --}}
    <main class="flex-1 pb-20">
      {{-- شبكة 6 كروت --}}
      <section class="px-4 py-4">
        <div class="grid grid-cols-2 gap-3">
          @php
            $items = [
              ['نص'=>'استيراد','رابط'=>route('front.import.index')],
              ['نص'=>'تصدير','رابط'=>route('front.export.index')],
              ['نص'=>'مستخلص جمركي','رابط'=>route('front.clearance.index')],
              ['نص'=>'تصنيع','رابط'=>route('front.manufacturing.index')],
              ['نص'=>'وكيل','رابط'=>route('front.agent.index')],
              ['نص'=>'بورصة أسعار الحاويات','رابط'=>route('front.containers.index')],
            ];
          @endphp
          @foreach($items as $it)
            <a href="{{ $it['رابط'] }}" class="rounded-2xl bg-white shadow-sm hover:shadow p-4 flex flex-col items-center justify-center text-[#123f78]">
              <div class="w-8 h-8 mb-2 rounded-full bg-[#123f78]/10"></div>
              <div class="text-center text-sm font-medium leading-5">{{ $it['نص'] }}</div>
            </a>
          @endforeach
        </div>
      </section>

      {{-- خريطة دائرية (Placeholder) --}}
      <section class="px-4 pb-4">
        <div class="mx-auto aspect-square w-64 rounded-full overflow-hidden shadow bg-slate-200"></div>
      </section>
    </main>

    {{-- تبويب سفلي ثابت --}}
    <nav class="fixed inset-x-0 bottom-0 z-50 bg-white/90 backdrop-blur shadow pt-2 pb-[calc(env(safe-area-inset-bottom,0)+8px)]">
      <ul class="mx-auto max-w-sm px-4 grid grid-cols-5 text-center text-xs text-slate-700">
        @php
          $tabs = [
            ['label'=>'الرئيسية','href'=>url('/')],
            ['label'=>'الفاتورة','href'=>'#'],
            ['label'=>'حسابي','href'=>route('profile.show')],
            ['label'=>'الإشعارات','href'=>'#'],
            ['label'=>'الإعدادات','href'=>'#'],
          ];
        @endphp
        @foreach($tabs as $t)
          <li><a href="{{ $t['href'] }}" class="flex flex-col items-center gap-1 py-1">{{ $t['label'] }}</a></li>
        @endforeach
      </ul>
    </nav>
  </div>
</body>
</html>
