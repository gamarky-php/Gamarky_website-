<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ $currentDir ?? 'rtl' }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', __('nav.brand'))</title>

  {{-- Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  @elseif(app()->getLocale() === 'zh')
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@400;500;700&display=swap" rel="stylesheet">
  @else
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  @endif

  {{-- Vite Assets (تحميل Alpine مرة واحدة فقط) --}}
  @vite(['resources/css/app.css','resources/js/app.js'])
  
  {{-- Livewire Styles --}}
  @livewireStyles

  <style>
    [x-cloak] { display: none !important; }
    body {
      font-family: {{ app()->getLocale() === 'ar' ? "'Cairo', sans-serif" : (app()->getLocale() === 'zh' ? "'Noto Sans SC', sans-serif" : "'Inter', sans-serif") }};
    }
  </style>
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased">

  {{-- Navbar ثابت مع Z-Index مناسب --}}
  <header class="sticky top-0 z-50">
    @include('partials.navbar')
  </header>

  <main class="min-h-[60vh]">
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  {{-- Livewire Scripts --}}
  @livewireScripts
</body>
</html>





