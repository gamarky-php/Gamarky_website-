<?php

namespace App\Services\Notifications\Channels;

use App\Contracts\NotificationChannelInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

/**
 * WhatsApp Channel (WhatsApp Business API)
 * 
 * Sends notifications via WhatsApp using Business API
 * Supports: Twilio, Meta (Cloud API), and custom providers
 */
class WhatsAppChannel implements NotificationChannelInterface
{
    protected $config = [];
    protected $provider = 'twilio'; // twilio|meta|custom

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
            ->where('name', 'whatsapp')
            ->first();

        if ($channel) {
            $channelConfig = json_decode($channel->config, true) ?? [];
            
            $this->config = [
                'is_active' => (bool) $channel->is_active,
                'rate_limit' => $channel->rate_limit ?? 10,
                'daily_limit' => $channel->daily_limit ?? 1000,
                'provider' => $channelConfig['provider'] ?? 'twilio',
                
                // Twilio config
                'twilio_account_sid' => $channelConfig['twilio_account_sid'] ?? env('TWILIO_ACCOUNT_SID'),
                'twilio_auth_token' => $channelConfig['twilio_auth_token'] ?? env('TWILIO_AUTH_TOKEN'),
                'twilio_whatsapp_number' => $channelConfig['twilio_whatsapp_number'] ?? env('TWILIO_WHATSAPP_NUMBER'),
                
                // Meta Cloud API config
                'meta_access_token' => $channelConfig['meta_access_token'] ?? env('META_WHATSAPP_TOKEN'),
                'meta_phone_number_id' => $channelConfig['meta_phone_number_id'] ?? env('META_WHATSAPP_PHONE_ID'),
                'meta_business_account_id' => $channelConfig['meta_business_account_id'] ?? env('META_WHATSAPP_BUSINESS_ID'),
                
                // Custom API config
                'custom_api_url' => $channelConfig['custom_api_url'] ?? null,
                'custom_api_key' => $channelConfig['custom_api_key'] ?? null,
                'custom_headers' => $channelConfig['custom_headers'] ?? [],
            ];

            $this->provider = $this->config['provider'];
        }
    }

    /**
     * Send WhatsApp notification
     */
    public function send(array $recipient, string $subject, string $body, array $data = []): array
    {
        try {
            // Validate recipient
            if (!$this->validateRecipient($recipient)) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Invalid phone number for WhatsApp',
                ];
            }

            // Check if channel is available
            if (!$this->isAvailable()) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'WhatsApp channel is not configured',
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

            // Format message (strip HTML for WhatsApp)
            $message = $this->formatMessage($subject, $body);

            // Send via selected provider
            $result = match($this->provider) {
                'twilio' => $this->sendViaTwilio($recipient['phone'], $message),
                'meta' => $this->sendViaMeta($recipient['phone'], $message),
                'custom' => $this->sendViaCustomApi($recipient['phone'], $message),
                default => throw new \Exception('Invalid WhatsApp provider'),
            };

            if ($result['success']) {
                $this->updateStats(true);
            } else {
                $this->updateStats(false);
            }

            return $result;

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
     * Send via Twilio WhatsApp API
     */
    protected function sendViaTwilio(string $phone, string $message): array
    {
        try {
            $accountSid = $this->config['twilio_account_sid'];
            $authToken = $this->config['twilio_auth_token'];
            $from = $this->config['twilio_whatsapp_number'];

            if (!$accountSid || !$authToken || !$from) {
                throw new \Exception('Twilio WhatsApp credentials not configured');
            }

            // Format phone number for WhatsApp
            $to = 'whatsapp:' . $this->formatPhoneNumber($phone);
            $from = 'whatsapp:' . $from;

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message_id' => $data['sid'] ?? null,
                    'error' => null,
                ];
            }

            throw new \Exception($response->json()['message'] ?? 'Twilio API error');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message_id' => null,
                'error' => 'Twilio: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send via Meta (Facebook) Cloud API
     */
    protected function sendViaMeta(string $phone, string $message): array
    {
        try {
            $token = $this->config['meta_access_token'];
            $phoneNumberId = $this->config['meta_phone_number_id'];

            if (!$token || !$phoneNumberId) {
                throw new \Exception('Meta WhatsApp credentials not configured');
            }

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($phone),
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message_id' => $data['messages'][0]['id'] ?? null,
                    'error' => null,
                ];
            }

            throw new \Exception($response->json()['error']['message'] ?? 'Meta API error');

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message_id' => null,
                'error' => 'Meta: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send via Custom API
     */
    protected function sendViaCustomApi(string $phone, string $message): array
    {
        try {
            $url = $this->config['custom_api_url'];
            $apiKey = $this->config['custom_api_key'];
            $headers = $this->config['custom_headers'] ?? [];

            if (!$url) {
                throw new \Exception('Custom API URL not configured');
            }

            $request = Http::withHeaders($headers);
            
            if ($apiKey) {
                $request->withToken($apiKey);
            }

            $response = $request->post($url, [
                'phone' => $this->formatPhoneNumber($phone),
                'message' => $message,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message_id' => $response->json()['id'] ?? null,
                    'error' => null,
                ];
            }

            throw new \Exception($response->body());

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message_id' => null,
                'error' => 'Custom API: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send with template (for Business API)
     */
    public function sendTemplate(
        array $recipient,
        string $templateName,
        array $parameters = [],
        string $language = 'ar'
    ): array {
        try {
            if (!$this->validateRecipient($recipient)) {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Invalid phone number',
                ];
            }

            if ($this->provider !== 'meta') {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Template messages only supported with Meta provider',
                ];
            }

            $token = $this->config['meta_access_token'];
            $phoneNumberId = $this->config['meta_phone_number_id'];

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($recipient['phone']),
                    'type' => 'template',
                    'template' => [
                        'name' => $templateName,
                        'language' => [
                            'code' => $language,
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => array_map(fn($p) => ['type' => 'text', 'text' => $p], $parameters),
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $this->updateStats(true);
                $data = $response->json();
                
                return [
                    'success' => true,
                    'message_id' => $data['messages'][0]['id'] ?? null,
                    'error' => null,
                ];
            }

            throw new \Exception($response->json()['error']['message'] ?? 'Template send failed');

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
     * Send with media attachment
     */
    public function sendWithMedia(
        array $recipient,
        string $message,
        string $mediaType,
        string $mediaUrl
    ): array {
        try {
            if ($this->provider !== 'meta') {
                return [
                    'success' => false,
                    'message_id' => null,
                    'error' => 'Media messages only supported with Meta provider',
                ];
            }

            $token = $this->config['meta_access_token'];
            $phoneNumberId = $this->config['meta_phone_number_id'];

            $response = Http::withToken($token)
                ->post("https://graph.facebook.com/v18.0/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->formatPhoneNumber($recipient['phone']),
                    'type' => $mediaType, // image, document, video, audio
                    $mediaType => [
                        'link' => $mediaUrl,
                        'caption' => $message,
                    ],
                ]);

            if ($response->successful()) {
                $this->updateStats(true);
                $data = $response->json();
                
                return [
                    'success' => true,
                    'message_id' => $data['messages'][0]['id'] ?? null,
                    'error' => null,
                ];
            }

            throw new \Exception($response->json()['error']['message'] ?? 'Media send failed');

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
     * Format message (strip HTML, truncate if needed)
     */
    protected function formatMessage(string $subject, string $body): string
    {
        // Strip HTML tags
        $plainBody = strip_tags($body);
        
        // Replace common HTML entities
        $plainBody = html_entity_decode($plainBody, ENT_QUOTES, 'UTF-8');
        
        // Combine subject and body
        $message = "*{$subject}*\n\n{$plainBody}";
        
        // Truncate to WhatsApp limit (4096 characters)
        if (strlen($message) > 4096) {
            $message = substr($message, 0, 4093) . '...';
        }
        
        return $message;
    }

    /**
     * Format phone number (add country code if missing)
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remove any non-numeric characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Add Saudi Arabia country code if missing
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '966')) {
                $phone = '+' . $phone;
            } elseif (str_starts_with($phone, '0')) {
                $phone = '+966' . substr($phone, 1);
            } else {
                $phone = '+966' . $phone;
            }
        }
        
        return $phone;
    }

    /**
     * Check if channel is available
     */
    public function isAvailable(): bool
    {
        if (!($this->config['is_active'] ?? false)) {
            return false;
        }

        return match($this->provider) {
            'twilio' => !empty($this->config['twilio_account_sid']) && 
                       !empty($this->config['twilio_auth_token']),
            'meta' => !empty($this->config['meta_access_token']) && 
                     !empty($this->config['meta_phone_number_id']),
            'custom' => !empty($this->config['custom_api_url']),
            default => false,
        };
    }

    /**
     * Get channel name
     */
    public function getName(): string
    {
        return 'whatsapp';
    }

    /**
     * Get channel configuration
     */
    public function getConfig(): array
    {
        // Return config without sensitive data
        return [
            'is_active' => $this->config['is_active'] ?? false,
            'provider' => $this->provider,
            'rate_limit' => $this->config['rate_limit'] ?? null,
            'daily_limit' => $this->config['daily_limit'] ?? null,
        ];
    }

    /**
     * Validate recipient
     */
    public function validateRecipient(array $recipient): bool
    {
        if (!isset($recipient['phone']) || empty($recipient['phone'])) {
            return false;
        }

        // Basic phone validation
        $phone = preg_replace('/[^0-9+]/', '', $recipient['phone']);
        return strlen($phone) >= 10;
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
            ->where('channel', 'whatsapp')
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
            ->where('name', 'whatsapp')
            ->increment($success ? 'total_sent' : 'total_failed')
            ->update(['last_sent_at' => Carbon::now()]);
    }
}
