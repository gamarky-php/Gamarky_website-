<?php

namespace App\Contracts;

/**
 * Notification Channel Interface
 * 
 * واجهة موحدة لجميع قنوات الإشعارات
 */
interface NotificationChannelInterface
{
    /**
     * Send notification
     * 
     * @param array $recipient Recipient details (email, phone, user_id, etc.)
     * @param string $subject Notification subject/title
     * @param string $body Notification body/message
     * @param array $data Additional data (template_id, variables, etc.)
     * @return array ['success' => bool, 'message_id' => string|null, 'error' => string|null]
     */
    public function send(array $recipient, string $subject, string $body, array $data = []): array;

    /**
     * Check if channel is available and configured
     * 
     * @return bool
     */
    public function isAvailable(): bool;

    /**
     * Get channel name
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Get channel configuration
     * 
     * @return array
     */
    public function getConfig(): array;

    /**
     * Validate recipient data
     * 
     * @param array $recipient
     * @return bool
     */
    public function validateRecipient(array $recipient): bool;
}
