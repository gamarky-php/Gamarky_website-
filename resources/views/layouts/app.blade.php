<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'جماركي'))</title>
    
    {{-- Vite Assets (تحميل Alpine مرة واحدة فقط) --}}
    @vite(['resources/css/app.css','resources/js/app.js'])
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased bg-white min-h-screen">

    {{-- النافبار الرئيسي --}}
    @include('partials.navbar')

    {{-- محتوى الصفحة --}}
    <main id="page" class="min-h-[calc(100vh-200px)]">
        @yield('content')
    </main>

    {{-- فوتر --}}
    @includeIf('partials.footer')

    {{-- Livewire Scripts --}}
    @livewireScripts
    
    @stack('modals')
    @stack('scripts')
</body>
</html>
