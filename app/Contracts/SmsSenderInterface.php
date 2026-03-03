<?php

namespace App\Contracts;

/**
 * SmsSenderInterface
 * 
 * Contract for SMS sending services
 * Allows easy swapping between SMS providers (Twilio, Nexmo, local providers, etc.)
 */
interface SmsSenderInterface
{
    /**
     * Send SMS message
     * 
     * @param string $to Phone number in international format (e.g., +201234567890)
     * @param string $message SMS message content
     * @param array $options Additional options (priority, sender_id, etc.)
     * @return array ['success' => bool, 'message_id' => string|null, 'error' => string|null]
     */
    public function send(string $to, string $message, array $options = []): array;

    /**
     * Check if SMS service is enabled
     * 
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get service provider name
     * 
     * @return string
     */
    public function getProvider(): string;
}
