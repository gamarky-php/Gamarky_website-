<!doctype html>
<html lang="@locale" dir="@dir" translate="no" class="notranslate">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="google" content="notranslate" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', __('nav.brand'))</title>

  @include('layouts.partials.fonts')

  {{-- Vite Assets (تحميل Alpine مرة واحدة فقط) --}}
  @vite(['resources/css/app.css','resources/js/app.js'])
  
  {{-- Livewire Styles --}}
  @livewireStyles
</head>
<body class="min-h-screen bg-white text-gray-900 antialiased notranslate" dir="@dir" translate="no">

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





