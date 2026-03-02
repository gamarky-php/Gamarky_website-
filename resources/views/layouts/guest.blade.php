<!DOCTYPE html>
<html lang="@locale" dir="@dir">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('nav.brand') }}</title>

        @include('layouts.partials.fonts')

        {{-- Vite Assets (تحميل Alpine مرة واحدة فقط) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Livewire Styles --}}
        @livewireStyles
    </head>
    <body dir="@dir">
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        {{-- Livewire Scripts --}}
        @livewireScripts
    </body>
</html>
