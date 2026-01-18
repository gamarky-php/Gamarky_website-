<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
<!--
╔═══════════════════════════════════════════════════════════════════════╗
║  Dashboard Advanced Layout - لوحة التحكم المتقدمة                      ║
║  Purpose: قالب RTL موحد لجميع صفحات لوحة التحكم الجديدة               ║
║  Features: Sidebar + Topbar + Breadcrumbs + Dark Mode + Notifications ║
║  Tech: Tailwind CSS 3 + Alpine.js + Livewire 3                       ║
╚═══════════════════════════════════════════════════════════════════════╝
-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>
    
    {{-- Fonts: IBM Plex Sans Arabic --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Vite Assets (تحميل Alpine مرة واحدة فقط) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Livewire Styles --}}
    @livewireStyles
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'IBM Plex Sans Arabic', sans-serif; }
    </style>
    
    @stack('styles')
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 antialiased" 
      x-data="dashboardApp()"
      x-init="init()"
      @keydown.escape="closeAllMenus()">
    
    <div class="flex h-full">
        
        <!-- SIDEBAR -->
        @include('dashboard.components.sidebar')
        
        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden"
             :class="{ 'mr-0': !sidebarOpen, 'mr-[280px]': sidebarOpen }"
             style="transition: margin 0.3s ease;">
            
            <!-- TOPBAR -->
            @include('dashboard.components.topbar')
            
            <!-- BREADCRUMBS -->
            @include('dashboard.components.breadcrumbs')
            
            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
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
         x-transition>
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
                sidebarOpen: true,
                mobileMenuOpen: false,
                notificationsOpen: false,
                profileMenuOpen: false,
                
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
                },
                
                closeAllMenus() {
                    this.mobileMenuOpen = false;
                    this.notificationsOpen = false;
                    this.profileMenuOpen = false;
                }
            }
        }
    </script>
</body>
</html>
