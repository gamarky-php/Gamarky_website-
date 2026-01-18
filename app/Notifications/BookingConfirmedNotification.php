<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $ref;
    protected array $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $ref, array $booking = [])
    {
        $this->ref = $ref;
        $this->booking = $booking;
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
            ->subject(__('ux.notifications.booking_confirmed'))
            ->greeting(__('مرحباً') . ' ' . $notifiable->name)
            ->line(__('ux.booking.confirmed_with_ref', ['ref' => $this->ref]))
            ->line(__('تم تأكيد حجزك بنجاح'))
            ->action(__('ux.cta.view_details'), url('/bookings/' . $this->ref))
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
            'type' => 'booking_confirmed',
            'title' => __('ux.notifications.booking_confirmed'),
            'message' => __('ux.booking.confirmed_with_ref', ['ref' => $this->ref]),
            'ref' => $this->ref,
            'booking' => $this->booking,
            'action_url' => url('/bookings/' . $this->ref),
            'action_text' => __('ux.cta.view_details'),
        ];
    }
}
