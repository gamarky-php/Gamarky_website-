<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
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
            ->subject(__('ux.notifications.new_message'))
            ->greeting(__('مرحباً') . ' ' . $notifiable->name)
            ->line(__('نرحب بك في منصة قمرقي للشحن والتخليص الجمركي.'))
            ->line(__('يمكنك الآن البدء في استخدام جميع خدماتنا.'))
            ->action(__('ux.cta.start_now'), url('/dashboard'))
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
            'type' => 'welcome',
            'title' => __('مرحباً بك'),
            'message' => __('نرحب بك في منصة قمرقي'),
            'action_url' => url('/dashboard'),
            'action_text' => __('ux.cta.start_now'),
            'data' => $this->data,
        ];
    }

    /**
     * Get the WhatsApp representation (stub)
     */
    public function toWhatsApp(object $notifiable): array
    {
        return [
            'to' => $notifiable->phone,
            'message' => __('مرحباً') . ' ' . $notifiable->name . "\n" .
                        __('نرحب بك في منصة قمرقي للشحن والتخليص الجمركي.'),
        ];
    }
}
