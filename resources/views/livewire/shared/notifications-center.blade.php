{{-- dir inherited from layout --}}
<div class="space-y-6">
    {{-- Header with Stats --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ __('dashboard.shared.notifications_center.title') }}</h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ __('dashboard.shared.notifications_center.unread_prefix') }} <span class="font-semibold text-indigo-600">{{ $unreadCount }}</span> {{ __('dashboard.shared.notifications_center.unread_suffix') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="markAllAsRead" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium transition-colors">
                    {{ __('dashboard.shared.notifications_center.mark_all_read') }}
                </button>
                <button wire:click="deleteAllRead" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                    {{ __('dashboard.shared.notifications_center.delete_read') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">{{ __('dashboard.shared.notifications_center.filters_title') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.shared.notifications_center.type') }}</label>
                <select wire:model.live="filterType" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('dashboard.shared.notifications_center.all_types') }}</option>
                    <option value="info">{{ __('dashboard.shared.notifications_center.types.info') }}</option>
                    <option value="success">{{ __('dashboard.shared.notifications_center.types.success') }}</option>
                    <option value="warning">{{ __('dashboard.shared.notifications_center.types.warning') }}</option>
                    <option value="error">{{ __('dashboard.shared.notifications_center.types.error') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('dashboard.shared.notifications_center.status') }}</label>
                <select wire:model.live="filterStatus" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('dashboard.shared.notifications_center.all') }}</option>
                    <option value="unread">{{ __('dashboard.shared.notifications_center.unread') }}</option>
                    <option value="read">{{ __('dashboard.shared.notifications_center.read') }}</option>
                </select>
            </div>

            <div class="flex items-end">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="showOnlyUnread" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-medium text-gray-700">{{ __('dashboard.shared.notifications_center.show_unread_only') }}</span>
                </label>
            </div>
        </div>
    </div>

    {{-- Notifications List --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $typeConfig = [
                            'info' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'ℹ️'],
                            'success' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => '✓'],
                            'warning' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'icon' => '⚠️'],
                            'error' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => '✗'],
                        ];
                        $config = $typeConfig[$notification->type] ?? $typeConfig['info'];
                    @endphp

                    <div class="p-5 hover:bg-gray-50 transition-colors duration-150 {{ $isUnread ? 'bg-indigo-50/30' : '' }}">
                        <div class="flex items-start gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 w-10 h-10 {{ $config['bg'] }} rounded-full flex items-center justify-center">
                                <span class="text-lg">{{ $config['icon'] }}</span>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900 {{ $isUnread ? 'font-bold' : '' }}">
                                            {{ $notification->title }}
                                        </h4>
                                        <p class="text-sm text-gray-700 mt-1">{{ $notification->message }}</p>
                                        
                                        <div class="flex items-center gap-3 mt-2">
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                            </span>
                                            
                                            @if($isUnread)
                                                <span class="px-2 py-0.5 text-xs font-semibold bg-indigo-600 text-white rounded-full">{{ __('dashboard.shared.notifications_center.new') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-2">
                                        @if($isUnread)
                                            <button wire:click="markAsRead({{ $notification->id }})" 
                                                    class="p-2 text-gray-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors" 
                                                    title="{{ __('dashboard.shared.notifications_center.mark_as_read') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button wire:click="markAsUnread({{ $notification->id }})" 
                                                    class="p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors" 
                                                    title="{{ __('dashboard.shared.notifications_center.mark_as_unread') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </button>
                                        @endif

                                        <button wire:click="deleteNotification({{ $notification->id }})" 
                                                class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" 
                                                title="{{ __('dashboard.shared.notifications_center.delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ __('dashboard.shared.notifications_center.empty_title') }}</h3>
                <p class="text-sm text-gray-600">
                    @if($showOnlyUnread || $filterStatus === 'unread')
                        {{ __('dashboard.shared.notifications_center.empty_unread') }}
                    @else
                        {{ __('dashboard.shared.notifications_center.empty_default') }}
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
