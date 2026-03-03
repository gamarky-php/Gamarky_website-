<?php

namespace App\Notifications;

use App\Models\Journey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * JourneyPaymentSuccessNotification
 * 
 * Sent when payment is successful and journey is activated
 * Contains operation code for customer reference
 */
class JourneyPaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $journey;
    protected $payment;

    public function __construct(Journey $journey, $payment = null)
    {
        $this->journey = $journey;
        $this->payment = $payment;
    }

    /**
     * Notification delivery channels
     */
    public function via($notifiable): array
    {
        $channels = ['database']; // Always store in database

        if ($this->journey->shouldNotifyViaEmail()) {
            $channels[] = 'mail';
        }

        // SMS channel would be added here
        // if ($this->journey->shouldNotifyViaSms()) {
        //     $channels[] = 'sms';
        // }

        return $channels;
    }

    /**
     * Email notification
     */
    public function toMail($notifiable): MailMessage
    {
        $locale = app()->getLocale();
        
        return (new MailMessage)
            ->subject($this->getSubject($locale))
            ->greeting($this->getGreeting($locale))
            ->line($this->getSuccessMessage($locale))
            ->line($this->getOperationCodeLine($locale))
            ->line($this->getTagline($locale))
            ->action($this->getActionText($locale), route('front.journey.show', $this->journey))
            ->line($this->getClosing($locale));
    }

    /**
     * Database notification payload
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'journey_payment_success',
            'journey_id' => $this->journey->id,
            'operation_code' => $this->journey->operation_code,
            'amount' => $this->journey->grand_total,
            'currency' => $this->journey->currency,
            'payment_id' => $this->payment?->id,
            'message' => $this->getSuccessMessage(app()->getLocale()),
        ];
    }

    /**
     * Get translated subject
     */
    protected function getSubject(string $locale): string
    {
        return match($locale) {
            'ar' => 'تم تفعيل الخدمة - رمز العملية: ' . $this->journey->operation_code,
            'zh' => '服务已激活 - 操作代码: ' . $this->journey->operation_code,
            default => 'Service Activated - Operation Code: ' . $this->journey->operation_code,
        };
    }

    /**
     * Get translated greeting
     */
    protected function getGreeting(string $locale): string
    {
        $name = $this->journey->user?->name ?? 'العميل';
        
        return match($locale) {
            'ar' => 'مرحباً ' . $name,
            'zh' => '您好 ' . $name,
            default => 'Hello ' . $name,
        };
    }

    /**
     * Get success message
     */
    protected function getSuccessMessage(string $locale): string
    {
        return match($locale) {
            'ar' => 'تم إتمام عملية الدفع بنجاح وتفعيل خدمتك.',
            'zh' => '支付成功，您的服务已激活。',
            default => 'Your payment was successful and your service has been activated.',
        };
    }

    /**
     * Get operation code line
     */
    protected function getOperationCodeLine(string $locale): string
    {
        $code = $this->journey->operation_code;
        
        return match($locale) {
            'ar' => "رمز العملية الخاص بك هو: **{$code}**",
            'zh' => "您的操作代码是: **{$code}**",
            default => "Your operation code is: **{$code}**",
        };
    }

    /**
     * Get Gamarky innovation tagline
     */
    protected function getTagline(string $locale): string
    {
        return match($locale) {
            'ar' => '🚀 جماركي منصة صاحبة ابتكار الاشتراك مقابل الوظيفة — انتفع بالخدمة حتى تصل لنهايتها، بدون اشتراك شهري.',
            'zh' => '🚀 Gamarky 首创按功能付费模式——服务直至完成，无需按月订阅。',
            default => '🚀 Gamarky pioneers function-based access — use the service until it\'s completed, with no monthly subscription.',
        };
    }

    /**
     * Get action button text
     */
    protected function getActionText(string $locale): string
    {
        return match($locale) {
            'ar' => 'عرض تفاصيل العملية',
            'zh' => '查看操作详情',
            default => 'View Operation Details',
        };
    }

    /**
     * Get closing message
     */
    protected function getClosing(string $locale): string
    {
        return match($locale) {
            'ar' => 'شكراً لاستخدامك خدمات جماركي.',
            'zh' => '感谢您使用 Gamarky 服务。',
            default => 'Thank you for using Gamarky services.',
        };
    }
}
