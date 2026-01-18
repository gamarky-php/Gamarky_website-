<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuoteReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $quote;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $quote)
    {
        $this->quote = $quote;
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
            ->subject(__('عرض السعر جاهز'))
            ->greeting(__('مرحباً') . ' ' . $notifiable->name)
            ->line(__('عرض السعر الخاص بك جاهز للمراجعة'))
            ->line(__('رقم المرجع: ') . ($this->quote['ref'] ?? 'N/A'))
            ->line(__('السعر الإجمالي: ') . ($this->quote['total'] ?? 0) . ' ' . ($this->quote['currency'] ?? 'SAR'))
            ->action(__('ux.cta.view_details'), url('/quotes/' . ($this->quote['id'] ?? '')))
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
            'type' => 'quote_ready',
            'title' => __('عرض السعر جاهز'),
            'message' => __('عرض السعر الخاص بك جاهز للمراجعة'),
            'quote' => $this->quote,
            'action_url' => url('/quotes/' . ($this->quote['id'] ?? '')),
            'action_text' => __('ux.cta.view_details'),
        ];
    }
}
