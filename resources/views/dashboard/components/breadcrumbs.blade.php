{{-- 
╔══════════════════════════════════════════════════════════════════╗
║  Breadcrumbs Component - مسار التنقل                             ║
║  Purpose: عرض المسار الحالي للمستخدم                             ║
╚══════════════════════════════════════════════════════════════════╝
--}}

<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-3">
    <div class="flex items-center justify-between">
        
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('dashboard.index') }}" 
               class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
            
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                @foreach($breadcrumbs as $breadcrumb)
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    
                    @if($loop->last)
                        <span class="font-medium text-gray-900 dark:text-white">{{ $breadcrumb['title'] }}</span>
                    @else
                        <a href="{{ $breadcrumb['url'] }}" 
                           class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                            {{ $breadcrumb['title'] }}
                        </a>
                    @endif
                @endforeach
            @else
                {{-- Auto-generate breadcrumbs from route --}}
                @php
                    $routeName = Route::currentRouteName();
                    $segments = explode('.', $routeName);
                    $breadcrumbsAuto = [];
                    
                    // Translation keys
                    $translations = [
                        'dashboard' => 'ui.dashboard.breadcrumbs.dashboard',
                        'import' => 'ui.dashboard.breadcrumbs.import',
                        'export' => 'ui.dashboard.breadcrumbs.export',
                        'manufacturing' => 'ui.dashboard.breadcrumbs.manufacturing',
                        'clearance' => 'ui.dashboard.breadcrumbs.clearance',
                        'containers' => 'ui.dashboard.breadcrumbs.containers',
                        'agency' => 'ui.dashboard.breadcrumbs.agency',
                        'index' => 'ui.dashboard.breadcrumbs.index',
                        'costs' => 'ui.dashboard.breadcrumbs.costs',
                        'quotes' => 'ui.dashboard.breadcrumbs.quotes',
                        'shipments' => 'ui.dashboard.breadcrumbs.shipments',
                        'markets' => 'ui.dashboard.breadcrumbs.markets',
                        'bom' => 'ui.dashboard.breadcrumbs.bom',
                        'pending' => 'ui.dashboard.breadcrumbs.pending',
                        'brokers' => 'ui.dashboard.breadcrumbs.brokers',
                        'bookings' => 'ui.dashboard.breadcrumbs.bookings',
                        'tracking' => 'ui.dashboard.breadcrumbs.tracking',
                        'shipping' => 'ui.dashboard.breadcrumbs.shipping',
                        'brands' => 'ui.dashboard.breadcrumbs.brands',
                        'commissions' => 'ui.dashboard.breadcrumbs.commissions',
                        'ads' => 'ui.dashboard.breadcrumbs.ads',
                        'notifications' => 'ui.dashboard.breadcrumbs.notifications',
                        'articles' => 'ui.dashboard.breadcrumbs.articles',
                        'media' => 'ui.dashboard.breadcrumbs.media',
                        'subscriptions' => 'ui.dashboard.breadcrumbs.subscriptions',
                        'users' => 'ui.dashboard.breadcrumbs.users',
                        'roles' => 'ui.dashboard.breadcrumbs.roles',
                        'settings' => 'ui.dashboard.breadcrumbs.settings',
                    ];
                    
                    $url = '';
                    foreach ($segments as $index => $segment) {
                        if ($index > 0) {
                            $url .= ($index === 1 ? '' : '.') . $segment;
                            $title = isset($translations[$segment]) ? __($translations[$segment]) : ucfirst($segment);
                            
                            // Smart fallback: try route, then route.index, then '#'
                            $route1 = 'dashboard.' . $url;
                            $route2 = 'dashboard.' . $url . '.index';
                            $link = Route::has($route1) ? route($route1) : (Route::has($route2) ? route($route2) : '#');
                            
                            $breadcrumbsAuto[] = [
                                'title' => $title,
                                'url' => $link,
                                'active' => $index === count($segments) - 1
                            ];
                        }
                    }
                @endphp
                
                @foreach($breadcrumbsAuto as $breadcrumb)
                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    
                    @if($breadcrumb['active'])
                        <span class="font-medium text-gray-900 dark:text-white">{{ $breadcrumb['title'] }}</span>
                    @else
                        <a href="{{ $breadcrumb['url'] }}" 
                           class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                            {{ $breadcrumb['title'] }}
                        </a>
                    @endif
                @endforeach
            @endif
        </div>
        
        <!-- Page Actions (Optional) -->
        @yield('page-actions')
        
    </div>
</nav>
