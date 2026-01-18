<?php

namespace App\Services\Notifications\Channels;

use App\Contracts\NotificationChannelInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Database Channel (In-App Notifications)
 * 
 * Stores notifications in database for in-app display
 */
class DatabaseChannel implements NotificationChannelInterface
{
    protected $config = [];

    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Load channel configuration from database
     */
    protected function loadConfig(): void
    {
        $channel = DB::table('notification_channels')
            ->where('name', 'database')
            ->first();

        if ($channel) {
            $this->config = [
                'is_active' => (bool) $channel->is_active,
                'rate_limit' => $channel->rate_limit,
                'daily_limit' => $channel->daily_limit,
            ];
        }
    }

    /**
     * Send notification (store in database)
     */
    public function send(array $recipient, string $subject, string $body, array $data = []): array
    {
        try {
            // Validate recipient
            if (!$this->validateRecipient($recipient)) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Invalid recipient data for database channel',
                ];
            }

            // Check if channel is available
            if (!$this->isAvailable()) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Database channel is not available',
                ];
            }

            // Store notification (already stored by NotificationService)
            // Just return success since the notification is already in DB
            
            $this->updateStats(true);

            return [
                'success' => true,
                'message_id' => $data['notification_id'] ?? null,
                'error' => null,
            ];

        } catch (\Exception $e) {
            $this->updateStats(false);
            
            return [
                'success' => false,
                'message_id' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check if channel is available
     */
    public function isAvailable(): bool
    {
        return $this->config['is_active'] ?? true;
    }

    /**
     * Get channel name
     */
    public function getName(): string
    {
        return 'database';
    }

    /**
     * Get channel configuration
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Validate recipient
     */
    public function validateRecipient(array $recipient): bool
    {
        // Database channel requires user_id
        return isset($recipient['user_id']) && !empty($recipient['user_id']);
    }

    /**
     * Update channel statistics
     */
    protected function updateStats(bool $success): void
    {
        DB::table('notification_channels')
            ->where('name', 'database')
            ->increment($success ? 'total_sent' : 'total_failed')
            ->update(['last_sent_at' => Carbon::now()]);
    }

    /**
     * Get unread count for user
     */
    public static function getUnreadCount(int $userId): int
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('channel', 'database')
            ->where('status', '!=', 'read')
            ->count();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId, int $userId): bool
    {
        return DB::table('notifications')
            ->where('id', $notificationId)
            ->where('user_id', $userId)
            ->where('channel', 'database')
            ->update([
                'status' => 'read',
                'read_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]) > 0;
    }

    /**
     * Get recent notifications for user
     */
    public static function getRecent(int $userId, int $limit = 10): array
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('channel', 'database')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
