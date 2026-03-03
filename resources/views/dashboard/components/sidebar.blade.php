{{-- 
╔══════════════════════════════════════════════════════════════════╗
║  Sidebar Component - القائمة الجانبية RTL                        ║
║  Purpose: قائمة تنقل ثابتة مع هيكلة منظمة بمجموعات واضحة        ║
║  Width: 280px | Position: Right (RTL)                           ║
╚══════════════════════════════════════════════════════════════════╝
--}}

<aside class="fixed top-0 right-0 z-50 h-full w-[280px] bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out flex flex-col"
       :class="{ '-translate-x-full lg:translate-x-0': !sidebarOpen, 'translate-x-0': sidebarOpen || mobileMenuOpen }"
       x-cloak>
    
    <!-- HEADER: Logo + Brand + Home Button -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center justify-between mb-2">
            @if(Route::has('dashboard.index'))
                <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mardini</h1>
                </a>
                <a href="{{ route('dashboard.index') }}" 
                   class="w-9 h-9 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors"
                         title="{{ __('ui.nav.home') }}">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            @else
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mardini</h1>
                </div>
            @endif
            
            <!-- Close Button (Mobile) -->
            <button @click="mobileMenuOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('ui.dashboard.sidebar.unified_dashboard') }}</p>
    </div>
    
    <!-- NAVIGATION MENU -->
    <nav class="flex-1 overflow-y-auto py-4 px-3" style="scrollbar-width: thin;">
        
        <!-- ═══ نظرة عامة ═══ -->
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('ui.dashboard.sidebar.overview') }}</h3>
            @if(Route::has('dashboard.index'))
                <a href="{{ route('dashboard.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.index') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>{{ __('ui.nav.home') }}</span>
                </a>
            @endif
        </div>
        
        <!-- ═══ العمليات التجارية ═══ -->
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('ui.dashboard.sidebar.business_operations') }}</h3>
            
            <!-- الاستيراد (Accordion) -->
            <div x-data="{ open: {{ request()->routeIs('dashboard.import.*') ? 'true' : 'false' }} }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        <span>{{ __('ui.dashboard.sidebar.import') }}</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mr-8 mt-1 space-y-1">
                    @if(Route::has('dashboard.import.costs'))
                        <a href="{{ route('dashboard.import.costs') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.import.costs') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.import_calculator') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.import.index'))
                        <a href="{{ route('dashboard.import.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.import.index') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.import_procedures') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.import.shipments'))
                        <a href="{{ route('dashboard.import.shipments') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.import.shipments') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.discover_supplier') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.import.quotes'))
                        <a href="{{ route('dashboard.import.quotes') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.import.quotes') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.quotes') }}</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- التصدير (Accordion) -->
            <div x-data="{ open: {{ request()->routeIs('dashboard.export.*') ? 'true' : 'false' }} }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        <span>{{ __('ui.dashboard.sidebar.export') }}</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mr-8 mt-1 space-y-1">
                    @if(Route::has('dashboard.export.costs'))
                        <a href="{{ route('dashboard.export.costs') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.export.costs') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.export_calculator') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.export.index'))
                        <a href="{{ route('dashboard.export.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.export.index') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.export_procedures') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.export.markets'))
                        <a href="{{ route('dashboard.export.markets') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.export.markets') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.discover_markets') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.export.quotes'))
                        <a href="{{ route('dashboard.export.quotes') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.export.quotes') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.quotes') }}</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- التصنيع (Accordion) -->
            <div x-data="{ open: {{ request()->routeIs('dashboard.manufacturing.*') ? 'true' : 'false' }} }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <span>{{ __('ui.dashboard.sidebar.manufacturing') }}</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mr-8 mt-1 space-y-1">
                    @if(Route::has('dashboard.manufacturing.costs'))
                        <a href="{{ route('dashboard.manufacturing.costs') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.manufacturing.costs') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.manufacturing_calculator') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.manufacturing.bom'))
                        <a href="{{ route('dashboard.manufacturing.bom') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.manufacturing.bom') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.raw_materials_access') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.manufacturing.index'))
                        <a href="{{ route('dashboard.manufacturing.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.manufacturing.index') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.manufacturing_procedures') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.manufacturing.quotes'))
                        <a href="{{ route('dashboard.manufacturing.quotes') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.manufacturing.quotes') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.quotes') }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- ═══ الخدمات اللوجستية ═══ -->
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('ui.dashboard.sidebar.logistics_services') }}</h3>
            
            <!-- التخليص الجمركي (Accordion) -->
            <div x-data="{ open: {{ request()->routeIs('dashboard.clearance.*') ? 'true' : 'false' }} }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>{{ __('ui.dashboard.sidebar.customs_clearance') }}</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mr-8 mt-1 space-y-1">
                    @if(Route::has('dashboard.clearance.index'))
                        <a href="{{ route('dashboard.clearance.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.clearance.index') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.clearance_procedures') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.clearance.costs'))
                        <a href="{{ route('dashboard.clearance.costs') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.clearance.costs') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.clearance_calculator') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.clearance.brokers'))
                        <a href="{{ route('dashboard.clearance.brokers') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.clearance.brokers') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.customs_brokers') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.clearance.timeline'))
                        <a href="{{ route('dashboard.clearance.timeline') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.clearance.timeline') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.timeline') }}</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- نقل الحاويات (Accordion) -->
            <div x-data="{ open: {{ request()->routeIs('dashboard.containers.*') ? 'true' : 'false' }} }" class="mb-1">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>{{ __('ui.dashboard.sidebar.container_transport') }}</span>
                    </div>
                    <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-collapse class="mr-8 mt-1 space-y-1">
                    @if(Route::has('dashboard.containers.bookings'))
                        <a href="{{ route('dashboard.containers.bookings') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.containers.bookings') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.container_bookings') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.containers.tracking'))
                        <a href="{{ route('dashboard.containers.tracking') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.containers.tracking') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.container_tracking') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.containers.costs'))
                        <a href="{{ route('dashboard.containers.costs') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.containers.costs') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.cost_calculator') }}</span>
                        </a>
                    @endif
                    @if(Route::has('dashboard.containers.index'))
                        <a href="{{ route('dashboard.containers.index') }}" 
                           class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ request()->routeIs('dashboard.containers.index') ? 'text-blue-600 dark:text-blue-400 font-medium' : '' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            <span>{{ __('ui.dashboard.sidebar.all_containers') }}</span>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- وكلاء الشحن -->
            @if(Route::has('dashboard.agency.index'))
                <a href="{{ route('dashboard.agency.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.agency.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.shipping_agents') }}</span>
                </a>
            @endif
        </div>
        
        <!-- ═══ إدارة المحتوى ═══ -->
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('ui.dashboard.sidebar.content_management') }}</h3>
            
            @if(Route::has('dashboard.ads.index'))
                <a href="{{ route('dashboard.ads.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.ads.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.ads') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.articles.index'))
                <a href="{{ route('dashboard.articles.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.articles.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.articles') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.media.index'))
                <a href="{{ route('dashboard.media.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.media.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.media_library') }}</span>
                </a>
            @endif
        </div>
        
        <!-- ═══ التحليلات والتقارير ═══ -->
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('ui.dashboard.sidebar.analytics_reports') }}</h3>
            
            @if(Route::has('dashboard.analytics.index'))
                <a href="{{ route('dashboard.analytics.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.analytics.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.analytics_dashboard') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.notifications.index'))
                <a href="{{ route('dashboard.notifications.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.notifications.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.notifications') }}</span>
                </a>
            @endif
        </div>
        
        <!-- ═══ إدارة النظام ═══ -->
        <div class="mb-6">
            <h3 class="px-3 mb-2 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ __('ui.dashboard.sidebar.system_management') }}</h3>
            
            @if(Route::has('dashboard.users.index'))
                <a href="{{ route('dashboard.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.users.*') && !request()->routeIs('dashboard.users.mobile') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.users') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.users.mobile'))
                <a href="{{ route('dashboard.users.mobile') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.users.mobile') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.mobile_users') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.roles.index'))
                <a href="{{ route('dashboard.roles.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.roles.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.roles_permissions') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.settings.index'))
                <a href="{{ route('dashboard.settings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.settings.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.general_settings') }}</span>
                </a>
            @endif
            
            @if(Route::has('dashboard.subscriptions.index'))
                <a href="{{ route('dashboard.subscriptions.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.subscriptions.*') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-semibold border-r-4 border-blue-600' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span>{{ __('ui.dashboard.sidebar.subscriptions') }}</span>
                </a>
            @endif
        </div>
        
    </nav>
    
    <!-- FOOTER: Version -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Mardini v3.0.0</p>
    </div>
</aside>
