{{-- 
╔══════════════════════════════════════════════════════════════════╗
║  Topbar Component - الشريط العلوي                                ║
║  Purpose: شريط علوي مع بحث + dark mode + إشعارات + ملف شخصي    ║
╚══════════════════════════════════════════════════════════════════╝
--}}

<header class="sticky top-0 z-40 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6">
    
    <!-- RIGHT SIDE: Menu Toggle + Search -->
    <div class="flex items-center gap-4 flex-1">
        
        <!-- Mobile Menu Toggle -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Desktop Sidebar Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="hidden lg:block text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Global Search -->
        <div class="relative flex-1 max-w-md" x-data="{ searchOpen: false }">
            <div class="relative">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       @focus="searchOpen = true"
                       @blur="setTimeout(() => searchOpen = false, 200)"
                      placeholder="{{ __('ui.dashboard.topbar.search_placeholder') }}"
                       class="w-full pr-10 pl-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <!-- Search Results Dropdown -->
            <div x-show="searchOpen" 
                 x-cloak
                 x-transition
                 class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-4 text-sm text-gray-500 dark:text-gray-400 text-center">
                    {{ __('ui.dashboard.topbar.start_typing') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- LEFT SIDE: Dark Mode + Notifications + Language + Profile -->
    <div class="flex items-center gap-2">
        
        <!-- Dark Mode Toggle -->
        <button @click="darkMode = !darkMode" 
                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>
        
        <!-- Language Switcher -->
        <div class="relative" x-data="{ langOpen: false }">
            <button @click="langOpen = !langOpen" 
                    class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    title="{{ __('ui.dashboard.topbar.change_language') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
            </button>
            
            <!-- Language Dropdown -->
            <div x-show="langOpen" 
                 @click.away="langOpen = false"
                 x-cloak
                 x-transition
                 class="absolute left-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <a href="{{ route('locale.switch', 'ar') }}" 
                   class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() === 'ar' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span>🇸🇦</span>
                    {{ __('ui.dashboard.topbar.lang_ar') }}
                </a>
                <a href="{{ route('locale.switch', 'zh') }}" 
                   class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() === 'zh' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span>🇨🇳</span>
                    {{ __('ui.dashboard.topbar.lang_zh') }}
                </a>
                <a href="{{ route('locale.switch', 'en') }}" 
                   class="flex items-center gap-2 px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 {{ app()->getLocale() === 'en' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                    <span>🇬🇧</span>
                    {{ __('ui.dashboard.topbar.lang_en') }}
                </a>
            </div>
        </div>
        
        <!-- Notifications -->
        <div class="relative" x-data="{ notifOpen: false }">
            <button @click="notifOpen = !notifOpen" 
                    class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <!-- Badge -->
                <span class="absolute -top-1 -left-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                    3
                </span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div x-show="notifOpen" 
                 @click.away="notifOpen = false"
                 x-cloak
                 x-transition
                 class="absolute left-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                <!-- Header -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('ui.dashboard.topbar.notifications') }}</h3>
                    <button class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ __('ui.dashboard.topbar.mark_all_read') }}
                    </button>
                </div>
                
                <!-- Notifications List -->
                <div class="max-h-96 overflow-y-auto">
                    <!-- Notification Item -->
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.dashboard.topbar.notif_import_title') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('ui.dashboard.topbar.notif_import_desc') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('ui.dashboard.topbar.notif_time_5m') }}</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.dashboard.topbar.notif_approval_title') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('ui.dashboard.topbar.notif_approval_desc') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('ui.dashboard.topbar.notif_time_hour') }}</p>
                            </div>
                        </div>
                    </a>
                    
                    <a href="#" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('ui.dashboard.topbar.notif_deadline_title') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('ui.dashboard.topbar.notif_deadline_desc') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ __('ui.dashboard.topbar.notif_time_3h') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Footer -->
                @if(Route::has('dashboard.notifications.index'))
                    <a href="{{ route('dashboard.notifications.index') }}" 
                       class="block px-4 py-3 text-center text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium">
                        {{ __('ui.dashboard.topbar.view_all_notifications') }}
                    </a>
                @else
                    <span class="block px-4 py-3 text-center text-sm text-gray-400 dark:text-gray-500 font-medium cursor-not-allowed opacity-60">
                        {{ __('ui.dashboard.topbar.view_all_notifications_pending') }}
                    </span>
                @endif
            </div>
        </div>
        
        <!-- User Profile Menu -->
        <div class="relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen" 
                    class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'User').'&background=3b82f6&color=fff' }}" 
                     alt="{{ Auth::user()->name ?? 'User' }}" 
                     class="w-8 h-8 rounded-full ring-2 ring-gray-200 dark:ring-gray-700">
                <div class="hidden md:block text-right">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name ?? __('ui.dashboard.topbar.default_user') }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        @if(Auth::user()->roles->first())
                            {{ Auth::user()->roles->first()->name }}
                        @else
                            {{ __('ui.dashboard.topbar.default_user') }}
                        @endif
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Profile Dropdown -->
            <div x-show="profileOpen" 
                 @click.away="profileOpen = false"
                 x-cloak
                 x-transition
                 class="absolute left-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                
                <!-- User Info -->
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::user()->name ?? __('ui.dashboard.topbar.default_user') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Auth::user()->email }}</p>
                </div>
                
                <!-- Menu Items -->
                <div class="py-2">
                    @if(Route::has('profile.show'))
                        <a href="{{ route('profile.show') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('ui.dashboard.topbar.profile') }}
                        </a>
                    @else
                        <span class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-60">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('ui.dashboard.topbar.profile_pending') }}
                        </span>
                    @endif
                    
                    @if(Route::has('dashboard.settings.index'))
                        <a href="{{ route('dashboard.settings.index') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('ui.dashboard.topbar.settings') }}
                        </a>
                    @else
                        <span class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-60">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ __('ui.dashboard.topbar.settings_pending') }}
                        </span>
                    @endif
                    
                    @if(Route::has('dashboard.notifications.settings'))
                        <a href="{{ route('dashboard.notifications.settings') }}" 
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            {{ __('ui.dashboard.topbar.notification_settings') }}
                        </a>
                    @else
                        <span class="flex items-center gap-3 px-4 py-2 text-sm text-gray-400 dark:text-gray-500 cursor-not-allowed opacity-60">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            {{ __('ui.dashboard.topbar.notification_settings_pending') }}
                        </span>
                    @endif
                </div>
                
                <!-- Logout -->
                <div class="border-t border-gray-200 dark:border-gray-700 py-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 w-full text-right">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('ui.nav.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</header>
