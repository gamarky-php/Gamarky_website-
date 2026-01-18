<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
<!--
╔═══════════════════════════════════════════════════════════════════════╗
║  Dashboard Layout - لوحة التحكم المتقدمة RTL                          ║
║  Purpose: قالب موحد لجميع صفحات لوحة التحكم الجديدة                  ║
║  Features: Sidebar + Topbar + Breadcrumbs + Dark Mode + Notifications ║
║  Tech Stack: Tailwind CSS 3 + Alpine.js + Livewire 3                 ║
║  Version: 1.0.0 | Date: 2025-11-09                                    ║
╚═══════════════════════════════════════════════════════════════════════╝
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name', 'Gamarky') }}</title>
    
    {{-- Fonts: IBM Plex Sans Arabic --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Vite Assets (تحميل Alpine مرة واحدة فقط) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    {{-- Custom Styles --}}
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'IBM Plex Sans Arabic', sans-serif; }
    </style>
    
    @stack('styles')
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 antialiased" 
      x-data="dashboardApp()"
      x-init="init()"
      @keydown.escape="closeAllMenus()"
      @keydown.ctrl.k.prevent="$refs.search?.focus()">
    
    <div class="flex h-full">
        
        <!-- ═══════════ SIDEBAR ═══════════ -->
        @include('dashboard.components.sidebar')
        
        <!-- ═══════════ MAIN CONTENT ═══════════ -->
        <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300"
             :class="{ 'mr-0': !sidebarOpen, 'mr-[280px]': sidebarOpen }">
            
            <!-- TOPBAR -->
            @include('dashboard.components.topbar')
            
            <!-- BREADCRUMBS -->
            @include('dashboard.components.breadcrumbs')
            
            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-r-4 border-green-500 p-4 rounded-lg"
                         x-data="{ show: true }"
                         x-show="show"
                         x-transition
                         x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-r-4 border-red-500 p-4 rounded-lg"
                         x-data="{ show: true }"
                         x-show="show"
                         x-transition
                         x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border-r-4 border-yellow-500 p-4 rounded-lg"
                         x-data="{ show: true }"
                         x-show="show"
                         x-transition
                         x-init="setTimeout(() => show = false, 5000)">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-yellow-800 dark:text-yellow-200 font-medium">{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Page Content Slot -->
                @yield('content')
                
            </main>
            
            <!-- FOOTER -->
            @include('dashboard.components.footer')
            
        </div>
        
    </div>
    
    {{-- MOBILE OVERLAY --}}
    <div x-show="mobileMenuOpen"
         x-cloak
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>
    
    {{-- Livewire Scripts --}}
    @livewireScripts
    
    {{-- Custom Scripts --}}
    @stack('scripts')
    
    {{-- Dashboard App Script --}}
    <script>
        function dashboardApp() {
            return {
                darkMode: localStorage.getItem('darkMode') === 'true',
                sidebarOpen: window.innerWidth >= 1024,
                mobileMenuOpen: false,
                
                init() {
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }
                    
                    this.$watch('darkMode', value => {
                        localStorage.setItem('darkMode', value);
                        if (value) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    });
                    
                    window.addEventListener('resize', () => {
                        if (window.innerWidth >= 1024) {
                            this.mobileMenuOpen = false;
                            this.sidebarOpen = true;
                        }
                    });
                },
                
                closeAllMenus() {
                    this.mobileMenuOpen = false;
                }
            }
        }
    </script>
</body>
</html>

