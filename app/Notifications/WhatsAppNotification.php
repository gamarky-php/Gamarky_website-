<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WhatsAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $message;
    protected array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $message, array $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): array
    {
        return [
            'to' => $notifiable->phone ?? $this->data['phone'] ?? null,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'whatsapp',
            'message' => $this->message,
            'to' => $notifiable->phone ?? $this->data['phone'] ?? null,
            'data' => $this->data,
        ];
    }
}
