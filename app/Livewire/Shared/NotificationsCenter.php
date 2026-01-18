<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Livewire\WithPagination;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Notifications Center - مركز الإشعارات
 * 
 * Features:
 * - Inbox (الواردة) / Outbox (المرسلة)
 * - Filters: Channel, Status, Date Range
 * - Actions: Send, Resend, Mark as Read, Delete
 * - Real-time updates
 */
class NotificationsCenter extends Component
{
    use WithPagination;

    // View Mode
    public $view = 'inbox'; // inbox|outbox|templates|webhooks

    // Filters
    public $filterChannel = 'all'; // all|email|sms|database|webhook
    public $filterStatus = 'all'; // all|sent|failed|pending|read
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $search = '';
    public bool $showOnlyUnread = false;

    // Selection
    public $selectedNotifications = [];
    public $selectAll = false;

    // Modal states
    public $showSendModal = false;
    public $showDetailsModal = false;
    public $selectedNotification = null;

    // Send notification form
    public $sendTemplateSlug = '';
    public $sendRecipientEmail = '';
    public $sendRecipientPhone = '';
    public $sendRecipientName = '';
    public $sendVariables = [];

    // Stats
    public $stats = [];

    protected $notificationService;

    protected $queryString = [
        'view' => ['except' => 'inbox'],
        'filterChannel' => ['except' => 'all'],
        'filterStatus' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    /**
     * Boot service
     */
    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Mount
     */
    public function mount()
    {
        $this->filterDateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->filterDateTo = Carbon::now()->format('Y-m-d');
        $this->loadStats();
    }

    /**
     * Load statistics
     */
    public function loadStats()
    {
        $this->stats = $this->notificationService->getStats([
            'user_id' => auth()->id(),
            'date_from' => $this->filterDateFrom,
            'date_to' => $this->filterDateTo,
        ]);
    }

    /**
     * Get notifications (Inbox)
     */
    public function getNotificationsProperty()
    {
        $query = DB::table('notifications')
            ->select('notifications.*', 'notification_templates.name as template_name')
            ->leftJoin('notification_templates', 'notifications.template_id', '=', 'notification_templates.id')
            ->where('notifications.user_id', auth()->id())
            ->orderBy('notifications.created_at', 'desc');

        // Apply filters
        $this->applyFilters($query);

        return $query->paginate(20);
    }

    /**
     * Get sent notifications (Outbox) - للـ admin فقط
     */
    public function getSentNotificationsProperty()
    {
        $query = DB::table('notifications')
            ->select('notifications.*', 'notification_templates.name as template_name')
            ->leftJoin('notification_templates', 'notifications.template_id', '=', 'notification_templates.id')
            ->orderBy('notifications.created_at', 'desc');

        // Apply filters
        $this->applyFilters($query);

        return $query->paginate(20);
    }

    /**
     * Get templates
     */
    public function getTemplatesProperty()
    {
        return DB::table('notification_templates')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get webhook subscriptions
     */
    public function getWebhooksProperty()
    {
        return DB::table('webhook_subscriptions')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get unread count
     */
    public function getUnreadCountProperty()
    {
        return DB::table('notifications')
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Apply filters to query
     */
    protected function applyFilters($query)
    {
        // Channel filter
        if ($this->filterChannel !== 'all') {
            $query->where('notifications.channel', $this->filterChannel);
        }

        // Status filter
        if ($this->filterStatus !== 'all') {
            $query->where('notifications.status', $this->filterStatus);
        }

        // Date range
        if ($this->filterDateFrom) {
            $query->where('notifications.created_at', '>=', $this->filterDateFrom . ' 00:00:00');
        }

        if ($this->filterDateTo) {
            $query->where('notifications.created_at', '<=', $this->filterDateTo . ' 23:59:59');
        }

        // Show only unread filter
        if ($this->showOnlyUnread) {
            $query->whereNull('notifications.read_at');
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('notifications.subject', 'like', "%{$this->search}%")
                  ->orWhere('notifications.body', 'like', "%{$this->search}%")
                  ->orWhere('notifications.recipient_email', 'like', "%{$this->search}%")
                  ->orWhere('notifications.recipient_phone', 'like', "%{$this->search}%");
            });
        }
    }

    // ==================== ACTIONS ====================

    /**
     * Switch view
     */
    public function switchView($view)
    {
        $this->view = $view;
        $this->resetPage();
    }

    /**
     * Update filters
     */
    public function updateFilters()
    {
        $this->resetPage();
        $this->loadStats();
    }

    /**
     * Clear filters
     */
    public function clearFilters()
    {
        $this->filterChannel = 'all';
        $this->filterStatus = 'all';
        $this->search = '';
        $this->filterDateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->filterDateTo = Carbon::now()->format('Y-m-d');
        $this->resetPage();
        $this->loadStats();
    }

    /**
     * Toggle select all
     */
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedNotifications = $this->notifications->pluck('id')->toArray();
        } else {
            $this->selectedNotifications = [];
        }
    }

    /**
     * Mark as read
     */
    public function markAsRead($notificationId = null)
    {
        if ($notificationId) {
            $this->notificationService->markAsRead($notificationId);
            $this->dispatch('notification-updated');
        } elseif (!empty($this->selectedNotifications)) {
            $this->notificationService->markManyAsRead($this->selectedNotifications);
            $this->selectedNotifications = [];
            $this->selectAll = false;
            $this->dispatch('notification-updated');
        }

        $this->loadStats();
    }

    /**
     * Delete notification
     */
    public function delete($notificationId = null)
    {
        if ($notificationId) {
            DB::table('notifications')->where('id', $notificationId)->delete();
        } elseif (!empty($this->selectedNotifications)) {
            DB::table('notifications')->whereIn('id', $this->selectedNotifications)->delete();
            $this->selectedNotifications = [];
            $this->selectAll = false;
        }

        $this->dispatch('notification-deleted');
        $this->loadStats();
    }

    /**
     * Resend notification
     */
    public function resend($notificationId)
    {
        $result = $this->notificationService->retry($notificationId);

        if ($result['success']) {
            $this->dispatch('notification-sent', ['message' => 'تم إعادة الإرسال بنجاح']);
        } else {
            $this->dispatch('notification-error', ['message' => $result['error'] ?? 'فشل إعادة الإرسال']);
        }

        $this->loadStats();
    }

    /**
     * Show notification details
     */
    public function showDetails($notificationId)
    {
        $this->selectedNotification = DB::table('notifications')
            ->select('notifications.*', 'notification_templates.name as template_name', 'notification_templates.name_ar')
            ->leftJoin('notification_templates', 'notifications.template_id', '=', 'notification_templates.id')
            ->where('notifications.id', $notificationId)
            ->first();

        $this->showDetailsModal = true;
    }

    /**
     * Close details modal
     */
    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedNotification = null;
    }

    /**
     * Open send modal
     */
    public function openSendModal($templateSlug = null)
    {
        $this->sendTemplateSlug = $templateSlug ?? '';
        $this->sendRecipientEmail = '';
        $this->sendRecipientPhone = '';
        $this->sendRecipientName = '';
        $this->sendVariables = [];
        $this->showSendModal = true;
    }

    /**
     * Send new notification
     */
    public function sendNotification()
    {
        $this->validate([
            'sendTemplateSlug' => 'required|exists:notification_templates,slug',
            'sendRecipientEmail' => 'nullable|email',
            'sendRecipientPhone' => 'nullable|string',
            'sendRecipientName' => 'required|string',
        ]);

        try {
            $recipient = [
                'user_id' => auth()->id(),
                'email' => $this->sendRecipientEmail,
                'phone' => $this->sendRecipientPhone,
                'name' => $this->sendRecipientName,
            ];

            $result = $this->notificationService->send(
                $this->sendTemplateSlug,
                $recipient,
                $this->sendVariables
            );

            $this->showSendModal = false;
            $this->dispatch('notification-sent', ['message' => 'تم إرسال الإشعار بنجاح']);
            $this->loadStats();

        } catch (\Exception $e) {
            $this->dispatch('notification-error', ['message' => $e->getMessage()]);
        }
    }

    /**
     * Toggle webhook status
     */
    public function toggleWebhook($webhookId)
    {
        $webhook = DB::table('webhook_subscriptions')->find($webhookId);
        
        if ($webhook) {
            DB::table('webhook_subscriptions')
                ->where('id', $webhookId)
                ->update([
                    'is_active' => !$webhook->is_active,
                    'updated_at' => Carbon::now(),
                ]);

            $this->dispatch('webhook-updated');
        }
    }

    /**
     * Delete webhook
     */
    public function deleteWebhook($webhookId)
    {
        DB::table('webhook_subscriptions')->where('id', $webhookId)->delete();
        $this->dispatch('webhook-deleted');
    }

    /**
     * Refresh data
     */
    public function refresh()
    {
        $this->loadStats();
        $this->dispatch('notifications-refreshed');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.shared.notifications-center', [
            'notifications' => $this->view === 'inbox' ? $this->notifications : $this->sentNotifications,
            'templates' => $this->templates,
            'webhooks' => $this->webhooks,
            'unreadCount' => $this->unreadCount,
            'showOnlyUnread' => $this->showOnlyUnread,
        ])->layout('layouts.dashboard');
    }
}
