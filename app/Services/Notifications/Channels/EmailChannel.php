<?php

namespace App\Services\Notifications\Channels;

use App\Contracts\NotificationChannelInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

/**
 * Email Channel (SMTP)
 * 
 * Sends notifications via email using Laravel's Mail system
 */
class EmailChannel implements NotificationChannelInterface
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
            ->where('name', 'email')
            ->first();

        if ($channel) {
            $channelConfig = json_decode($channel->config, true) ?? [];
            
            $this->config = [
                'is_active' => (bool) $channel->is_active,
                'rate_limit' => $channel->rate_limit,
                'daily_limit' => $channel->daily_limit,
                'from_email' => $channelConfig['from_email'] ?? config('mail.from.address'),
                'from_name' => $channelConfig['from_name'] ?? config('mail.from.name'),
                'reply_to' => $channelConfig['reply_to'] ?? null,
            ];
        }
    }

    /**
     * Send email notification
     */
    public function send(array $recipient, string $subject, string $body, array $data = []): array
    {
        try {
            // Validate recipient
            if (!$this->validateRecipient($recipient)) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'No valid email address provided',
                ];
            }

            // Check if channel is available
            if (!$this->isAvailable()) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Email channel is not available',
                ];
            }

            // Check rate limit
            if (!$this->checkRateLimit()) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Rate limit exceeded',
                ];
            }

            // Send email
            Mail::send([], [], function ($message) use ($recipient, $subject, $body) {
                $message->to($recipient['email'], $recipient['name'] ?? '')
                    ->subject($subject)
                    ->from($this->config['from_email'], $this->config['from_name'])
                    ->html($body);

                if (isset($this->config['reply_to'])) {
                    $message->replyTo($this->config['reply_to']);
                }
            });

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
        return ($this->config['is_active'] ?? false) && 
               config('mail.default') !== null;
    }

    /**
     * Get channel name
     */
    public function getName(): string
    {
        return 'email';
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
        return isset($recipient['email']) && 
               filter_var($recipient['email'], FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check rate limit
     */
    protected function checkRateLimit(): bool
    {
        if (!isset($this->config['rate_limit'])) {
            return true;
        }

        $count = DB::table('notifications')
            ->where('channel', 'email')
            ->where('created_at', '>=', Carbon::now()->subMinute())
            ->count();

        return $count < $this->config['rate_limit'];
    }

    /**
     * Update channel statistics
     */
    protected function updateStats(bool $success): void
    {
        DB::table('notification_channels')
            ->where('name', 'email')
            ->increment($success ? 'total_sent' : 'total_failed')
            ->update(['last_sent_at' => Carbon::now()]);
    }

    /**
     * Send bulk emails (batch sending)
     */
    public function sendBulk(array $recipients, string $subject, string $body, array $data = []): array
    {
        $results = [
            'total' => count($recipients),
            'sent' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($recipients as $recipient) {
            $result = $this->send($recipient, $subject, $body, $data);
            
            if ($result['success']) {
                $results['sent']++;
            } else {
                $results['failed']++;
                $results['errors'][] = [
                    'recipient' => $recipient['email'] ?? 'unknown',
                    'error' => $result['error'],
                ];
            }
        }

        return $results;
    }

    /**
     * Send with attachment
     */
    public function sendWithAttachment(
        array $recipient,
        string $subject,
        string $body,
        array $attachments = [],
        array $data = []
    ): array {
        try {
            if (!$this->validateRecipient($recipient)) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Invalid recipient',
                ];
            }

            Mail::send([], [], function ($message) use ($recipient, $subject, $body, $attachments) {
                $message->to($recipient['email'], $recipient['name'] ?? '')
                    ->subject($subject)
                    ->from($this->config['from_email'], $this->config['from_name'])
                    ->html($body);

                foreach ($attachments as $attachment) {
                    if (isset($attachment['path'])) {
                        $message->attach($attachment['path'], [
                            'as' => $attachment['name'] ?? basename($attachment['path']),
                            'mime' => $attachment['mime'] ?? 'application/pdf',
                        ]);
                    }
                }
            });

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
}
