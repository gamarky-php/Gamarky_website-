<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected array $shipment;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $shipment)
    {
        $this->shipment = $shipment;
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
        $status = $this->shipment['status'] ?? 'updated';
        
        return (new MailMessage)
            ->subject(__('ux.notifications.shipment_update'))
            ->greeting(__('مرحباً') . ' ' . $notifiable->name)
            ->line(__('تم تحديث حالة شحنتك'))
            ->line(__('رقم المرجع: ') . ($this->shipment['ref'] ?? 'N/A'))
            ->line(__('الحالة: ') . __("ux.shipment.status_{$status}"))
            ->action(__('ux.cta.view_details'), url('/shipments/' . ($this->shipment['id'] ?? '')))
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
            'type' => 'shipment_update',
            'title' => __('ux.notifications.shipment_update'),
            'message' => __('تم تحديث حالة شحنتك'),
            'shipment' => $this->shipment,
            'action_url' => url('/shipments/' . ($this->shipment['id'] ?? '')),
            'action_text' => __('ux.cta.view_details'),
        ];
    }
}
