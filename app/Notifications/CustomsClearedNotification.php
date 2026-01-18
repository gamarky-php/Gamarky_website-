<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomsClearedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $customs;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $customs)
    {
        $this->customs = $customs;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('ux.notifications.customs_cleared'))
            ->greeting(__('مرحباً') . ' ' . $notifiable->name)
            ->line(__('ux.customs.clearance_approved'))
            ->line(__('رقم المرجع: ') . ($this->customs['ref'] ?? 'N/A'))
            ->action(__('ux.cta.view_details'), url('/customs/' . ($this->customs['id'] ?? '')))
            ->line(__('شكراً لاستخدامك منصتنا!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'customs_cleared',
            'title' => __('ux.notifications.customs_cleared'),
            'message' => __('ux.customs.clearance_approved'),
            'customs' => $this->customs,
            'action_url' => url('/customs/' . ($this->customs['id'] ?? '')),
            'action_text' => __('ux.cta.view_details'),
        ];
    }
}
