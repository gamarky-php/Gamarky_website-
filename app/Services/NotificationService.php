<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Notification Service
 * 
 * Centralized service for sending notifications across multiple channels:
 * - Email (SMTP)
 * - SMS (Twilio/Unifonic)
 * - Database (In-app)
 * - Webhooks (External systems)
 */
class NotificationService
{
    /**
     * Send notification using template
     * 
     * @param string $templateSlug Template identifier
     * @param array $recipient ['user_id' => 123, 'email' => '...', 'phone' => '...', 'name' => '...']
     * @param array $variables Placeholder values
     * @param array $options Override channels, priority, etc.
     * @return array Results for each channel
     */
    public function send(string $templateSlug, array $recipient, array $variables = [], array $options = []): array
    {
        // Load template
        $template = DB::table('notification_templates')
            ->where('slug', $templateSlug)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            throw new \Exception("Notification template '{$templateSlug}' not found or inactive");
        }

        // Check user preferences
        $preferences = $this->getUserPreferences($recipient['user_id'] ?? null, $templateSlug);

        // Prepare content
        $subject = $this->replaceVariables($template->subject_ar ?? $template->subject, $variables);
        $body = $this->replaceVariables($template->body_ar ?? $template->body, $variables);

        // Determine channels to use
        $channels = $this->getEnabledChannels($template, $preferences, $options);

        $results = [];

        // Send via each channel
        foreach ($channels as $channel) {
            try {
                $result = $this->sendViaChannel(
                    $channel,
                    $template,
                    $recipient,
                    $subject,
                    $body,
                    $variables
                );
                
                $results[$channel] = $result;
            } catch (\Exception $e) {
                $results[$channel] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Send via specific channel
     */
    protected function sendViaChannel(
        string $channel,
        object $template,
        array $recipient,
        string $subject,
        string $body,
        array $variables
    ): array {
        $uuid = Str::uuid();
        
        // Create notification record
        $notificationId = DB::table('notifications')->insertGetId([
            'uuid' => $uuid,
            'template_id' => $template->id,
            'user_id' => $recipient['user_id'] ?? null,
            'recipient_email' => $recipient['email'] ?? null,
            'recipient_phone' => $recipient['phone'] ?? null,
            'recipient_name' => $recipient['name'] ?? null,
            'subject' => $subject,
            'body' => $body,
            'data' => json_encode($variables),
            'channel' => $channel,
            'status' => 'pending',
            'notifiable_type' => $variables['notifiable_type'] ?? null,
            'notifiable_id' => $variables['notifiable_id'] ?? null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Send based on channel
        switch ($channel) {
            case 'email':
                return $this->sendEmail($notificationId, $recipient, $subject, $body);
                
            case 'sms':
                return $this->sendSms($notificationId, $recipient, $body);
                
            case 'database':
                return $this->sendDatabase($notificationId);
                
            case 'webhook':
                return $this->sendWebhook($notificationId, $template, $variables);
                
            default:
                throw new \Exception("Unsupported channel: {$channel}");
        }
    }

    /**
     * Send Email
     */
    protected function sendEmail(int $notificationId, array $recipient, string $subject, string $body): array
    {
        if (empty($recipient['email'])) {
            $this->markAsFailed($notificationId, 'No email address provided');
            return ['success' => false, 'error' => 'No email address'];
        }

        try {
            // Get channel config
            $channel = DB::table('notification_channels')
                ->where('name', 'email')
                ->where('is_active', true)
                ->first();

            if (!$channel) {
                throw new \Exception('Email channel is not configured');
            }

            $config = json_decode($channel->config, true);

            // Send email using Laravel Mail
            Mail::send([], [], function ($message) use ($recipient, $subject, $body, $config) {
                $message->to($recipient['email'], $recipient['name'] ?? '')
                    ->subject($subject)
                    ->from($config['from_email'], $config['from_name'])
                    ->html($body);
            });

            // Mark as sent
            $this->markAsSent($notificationId, 'email');

            // Update channel stats
            DB::table('notification_channels')
                ->where('name', 'email')
                ->increment('total_sent');

            return ['success' => true, 'notification_id' => $notificationId];

        } catch (\Exception $e) {
            $this->markAsFailed($notificationId, $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send SMS (Twilio/Unifonic)
     */
    protected function sendSms(int $notificationId, array $recipient, string $body): array
    {
        if (empty($recipient['phone'])) {
            $this->markAsFailed($notificationId, 'No phone number provided');
            return ['success' => false, 'error' => 'No phone number'];
        }

        try {
            // Get channel config
            $channel = DB::table('notification_channels')
                ->where('name', 'sms')
                ->where('is_active', true)
                ->first();

            if (!$channel) {
                throw new \Exception('SMS channel is not configured');
            }

            $config = json_decode($channel->config, true);

            // Strip HTML tags for SMS
            $plainBody = strip_tags($body);
            $plainBody = substr($plainBody, 0, 160); // SMS limit

            // Send via Twilio (example)
            // In production, use Twilio SDK or Unifonic API
            $response = Http::post('https://api.twilio.com/2010-04-01/Accounts/ACCOUNT_SID/Messages.json', [
                'From' => $config['from_number'],
                'To' => $recipient['phone'],
                'Body' => $plainBody,
            ]);

            if ($response->successful()) {
                $this->markAsSent($notificationId, 'sms');
                
                DB::table('notification_channels')
                    ->where('name', 'sms')
                    ->increment('total_sent');

                return ['success' => true, 'notification_id' => $notificationId];
            } else {
                throw new \Exception('SMS delivery failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            $this->markAsFailed($notificationId, $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send Database (In-app notification)
     */
    protected function sendDatabase(int $notificationId): array
    {
        try {
            // Already stored in notifications table, just mark as sent
            $this->markAsSent($notificationId, 'database');

            DB::table('notification_channels')
                ->where('name', 'database')
                ->increment('total_sent');

            return ['success' => true, 'notification_id' => $notificationId];

        } catch (\Exception $e) {
            $this->markAsFailed($notificationId, $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send Webhook
     */
    protected function sendWebhook(int $notificationId, object $template, array $variables): array
    {
        try {
            // Find active webhooks subscribed to this event
            $event = $template->slug; // e.g., 'booking_confirmed'

            $subscriptions = DB::table('webhook_subscriptions')
                ->where('is_active', true)
                ->whereRaw("JSON_CONTAINS(events, '\"$event\"')")
                ->get();

            if ($subscriptions->isEmpty()) {
                $this->markAsSent($notificationId, 'webhook');
                return ['success' => true, 'message' => 'No active webhook subscriptions'];
            }

            $results = [];

            foreach ($subscriptions as $subscription) {
                $results[] = $this->triggerWebhook($subscription, $event, $variables);
            }

            $this->markAsSent($notificationId, 'webhook');

            return [
                'success' => true,
                'notification_id' => $notificationId,
                'webhooks_triggered' => count($results),
                'results' => $results,
            ];

        } catch (\Exception $e) {
            $this->markAsFailed($notificationId, $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Trigger webhook call
     */
    protected function triggerWebhook(object $subscription, string $event, array $payload): array
    {
        $startTime = microtime(true);

        try {
            // Prepare payload
            $webhookPayload = [
                'event' => $event,
                'timestamp' => Carbon::now()->toIso8601String(),
                'data' => $payload,
            ];

            // Add signature if secret exists
            $headers = json_decode($subscription->headers ?? '{}', true);
            if ($subscription->secret) {
                $headers['X-Webhook-Signature'] = hash_hmac('sha256', json_encode($webhookPayload), $subscription->secret);
            }

            // Send HTTP request
            $response = Http::timeout($subscription->timeout)
                ->withHeaders($headers)
                ->post($subscription->url, $webhookPayload);

            $duration = (microtime(true) - $startTime) * 1000; // ms

            // Log webhook call
            $logId = DB::table('webhook_logs')->insertGetId([
                'subscription_id' => $subscription->id,
                'event' => $event,
                'payload' => json_encode($webhookPayload),
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'duration_ms' => round($duration),
                'attempt' => 1,
                'status' => $response->successful() ? 'success' : 'failed',
                'error_message' => $response->successful() ? null : $response->body(),
                'sent_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Update subscription stats
            DB::table('webhook_subscriptions')
                ->where('id', $subscription->id)
                ->increment('total_requests')
                ->increment($response->successful() ? 'total_successful' : 'total_failed')
                ->update(['last_triggered_at' => Carbon::now()]);

            return [
                'subscription_id' => $subscription->id,
                'status' => $response->status(),
                'success' => $response->successful(),
                'duration_ms' => round($duration),
                'log_id' => $logId,
            ];

        } catch (\Exception $e) {
            $duration = (microtime(true) - $startTime) * 1000;

            DB::table('webhook_logs')->insert([
                'subscription_id' => $subscription->id,
                'event' => $event,
                'payload' => json_encode($payload),
                'status_code' => null,
                'response_body' => null,
                'duration_ms' => round($duration),
                'attempt' => 1,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            DB::table('webhook_subscriptions')
                ->where('id', $subscription->id)
                ->increment('total_failed');

            return [
                'subscription_id' => $subscription->id,
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Replace variables in template
     */
    protected function replaceVariables(string $text, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }
        
        return $text;
    }

    /**
     * Get user notification preferences
     */
    protected function getUserPreferences(?int $userId, string $templateSlug): ?object
    {
        if (!$userId) {
            return null;
        }

        return DB::table('user_notification_preferences')
            ->where('user_id', $userId)
            ->where('template_slug', $templateSlug)
            ->first();
    }

    /**
     * Determine enabled channels
     */
    protected function getEnabledChannels(object $template, ?object $preferences, array $options): array
    {
        $channels = [];

        // Override from options
        if (isset($options['channels'])) {
            return $options['channels'];
        }

        // Check template settings
        if ($template->email_enabled && ($preferences->email_enabled ?? true)) {
            $channels[] = 'email';
        }

        if ($template->sms_enabled && ($preferences->sms_enabled ?? true)) {
            $channels[] = 'sms';
        }

        if ($template->database_enabled && ($preferences->database_enabled ?? true)) {
            $channels[] = 'database';
        }

        if ($template->webhook_enabled) {
            $channels[] = 'webhook';
        }

        return $channels;
    }

    /**
     * Mark notification as sent
     */
    protected function markAsSent(int $notificationId, string $channel): void
    {
        DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'status' => 'sent',
                'sent_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * Mark notification as failed
     */
    protected function markAsFailed(int $notificationId, string $error): void
    {
        DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'status' => 'failed',
                'failed_at' => Carbon::now(),
                'error_message' => $error,
                'attempts' => DB::raw('attempts + 1'),
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId): bool
    {
        return DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'status' => 'read',
                'read_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]) > 0;
    }

    /**
     * Mark multiple notifications as read
     */
    public function markManyAsRead(array $notificationIds): int
    {
        return DB::table('notifications')
            ->whereIn('id', $notificationIds)
            ->update([
                'status' => 'read',
                'read_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * Retry failed notification
     */
    public function retry(int $notificationId): array
    {
        $notification = DB::table('notifications')->find($notificationId);

        if (!$notification || $notification->status !== 'failed') {
            return ['success' => false, 'error' => 'Notification not found or not failed'];
        }

        // Get original template and variables
        $template = DB::table('notification_templates')->find($notification->template_id);
        $variables = json_decode($notification->data, true);

        $recipient = [
            'user_id' => $notification->user_id,
            'email' => $notification->recipient_email,
            'phone' => $notification->recipient_phone,
            'name' => $notification->recipient_name,
        ];

        // Reset status
        DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'status' => 'pending',
                'error_message' => null,
                'updated_at' => Carbon::now(),
            ]);

        // Retry via channel
        return $this->sendViaChannel(
            $notification->channel,
            $template,
            $recipient,
            $notification->subject,
            $notification->body,
            $variables
        );
    }

    /**
     * Get notification statistics
     */
    public function getStats(array $filters = []): array
    {
        $query = DB::table('notifications');

        // Check which columns exist
        $hasUserId = \Schema::hasColumn('notifications', 'user_id');
        $hasNotifiableId = \Schema::hasColumn('notifications', 'notifiable_id');
        $hasStatus = \Schema::hasColumn('notifications', 'status');
        $hasChannel = \Schema::hasColumn('notifications', 'channel');
        $hasReadAt = \Schema::hasColumn('notifications', 'read_at');

        // Filter by user
        if (isset($filters['user_id'])) {
            if ($hasUserId) {
                $query->where('user_id', $filters['user_id']);
            } elseif ($hasNotifiableId) {
                $query->where('notifiable_id', $filters['user_id'])
                      ->where('notifiable_type', \App\Models\User::class);
            }
        }

        // Filter by channel
        if (isset($filters['channel']) && $hasChannel) {
            $query->where('channel', $filters['channel']);
        }

        // Filter by date range
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Build statistics
        $stats = [
            'total' => $query->count(),
        ];

        // Status-based counts
        if ($hasStatus) {
            $stats['sent'] = (clone $query)->where('status', 'sent')->count();
            $stats['failed'] = (clone $query)->where('status', 'failed')->count();
            $stats['pending'] = (clone $query)->where('status', 'pending')->count();
        } else {
            $stats['sent'] = 0;
            $stats['failed'] = 0;
            $stats['pending'] = 0;
        }

        // Read count
        if ($hasReadAt) {
            $stats['read'] = (clone $query)->whereNotNull('read_at')->count();
        } elseif ($hasStatus) {
            $stats['read'] = (clone $query)->where('status', 'read')->count();
        } else {
            $stats['read'] = 0;
        }

        // By channel
        if ($hasChannel) {
            $stats['by_channel'] = DB::table('notifications')
                ->select('channel', DB::raw('count(*) as count'))
                ->groupBy('channel')
                ->pluck('count', 'channel')
                ->toArray();
        } else {
            $stats['by_channel'] = [];
        }

        return $stats;
    }
}
