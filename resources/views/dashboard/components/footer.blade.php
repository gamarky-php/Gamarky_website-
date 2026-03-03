{{-- 
╔══════════════════════════════════════════════════════════════════╗
║  Footer Component - التذييل                                      ║
║  Purpose: تذييل بسيط مع معلومات النظام                           ║
╚══════════════════════════════════════════════════════════════════╝
--}}

<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600 dark:text-gray-400">
        
        <!-- Copyright -->
        <div class="flex items-center gap-2">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}.</span>
            <span class="hidden md:inline">{{ __('ui.dashboard.footer.rights_reserved') }}</span>
        </div>
        
        <!-- Links & Info -->
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors">
                {{ __('ui.dashboard.footer.privacy_policy') }}
            </a>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <a href="#" class="hover:text-gray-900 dark:hover:text-white transition-colors">
                {{ __('ui.dashboard.footer.terms_of_use') }}
            </a>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <span class="text-xs">
                {{ __('ui.dashboard.footer.version') }} <span class="font-mono">1.0.0</span>
            </span>
        </div>
        
    </div>
</footer>
