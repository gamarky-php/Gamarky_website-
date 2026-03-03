<?php

namespace App\Services;

use App\Contracts\SmsSenderInterface;
use Illuminate\Support\Facades\Log;

/**
 * StubSmsSender
 * 
 * Placeholder SMS sender for development/testing
 * Replace with real implementation (TwilioSmsSender, NexmoSmsSender, etc.) in production
 */
class StubSmsSender implements SmsSenderInterface
{
    /**
     * Send SMS (stub - logs instead of sending)
     */
    public function send(string $to, string $message, array $options = []): array
    {
        Log::info('SMS Stub: Would send SMS', [
            'to' => $to,
            'message' => $message,
            'options' => $options,
        ]);

        return [
            'success' => true,
            'message_id' => 'stub-' . uniqid(),
            'error' => null,
        ];
    }

    /**
     * Check if enabled (stub always returns false)
     */
    public function isEnabled(): bool
    {
        return config('services.sms.enabled', false);
    }

    /**
     * Get provider name
     */
    public function getProvider(): string
    {
        return 'stub';
    }
}
