<?php

namespace App\Services;

use App\Contracts\SmsSenderInterface;
use App\Models\Journey;
use Illuminate\Support\Facades\Log;

/**
 * JourneyNotificationService
 * 
 * Handles sending journey-related notifications
 * - Email with operation code
 * - SMS with operation code
 */
class JourneyNotificationService
{
    protected $smsSender;

    public function __construct(SmsSenderInterface $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    /**
     * Send payment success notification
     * 
     * @param Journey $journey
     * @return array ['email' => array, 'sms' => array]
     */
    public function sendPaymentSuccessNotification(Journey $journey): array
    {
        $results = [
            'email' => ['sent' => false],
            'sms' => ['sent' => false],
        ];

        try {
            // Send email
            if ($journey->shouldNotifyViaEmail()) {
                $results['email'] = $this->sendEmailNotification($journey);
            }

            // Send SMS
            if ($journey->shouldNotifyViaSms()) {
                $results['sms'] = $this->sendSmsNotification($journey);
            }

            return $results;

        } catch (\Exception $e) {
            Log::error('Failed to send journey notification', [
                'journey_id' => $journey->id,
                'error' => $e->getMessage(),
            ]);

            return $results;
        }
    }

    /**
     * Send email notification
     */
    protected function sendEmailNotification(Journey $journey): array
    {
        try {
            $email = $journey->getNotificationEmail();
            
            if (!$email) {
                return ['sent' => false, 'error' => 'No email address'];
            }

            // Send via Laravel Notification
            if ($journey->user) {
                $journey->user->notify(new \App\Notifications\JourneyPaymentSuccessNotification($journey));
            } else {
                // Guest user - send via Mail
                \Illuminate\Support\Facades\Mail::to($email)
                    ->send(new \App\Mail\JourneyPaymentSuccessMail($journey));
            }

            Log::info('Journey email notification sent', [
                'journey_id' => $journey->id,
                'email' => $email,
                'operation_code' => $journey->operation_code,
            ]);

            return ['sent' => true, 'email' => $email];

        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'journey_id' => $journey->id,
                'error' => $e->getMessage(),
            ]);

            return ['sent' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send SMS notification
     */
    protected function sendSmsNotification(Journey $journey): array
    {
        try {
            $phone = $journey->getNotificationPhone();
            
            if (!$phone) {
                return ['sent' => false, 'error' => 'No phone number'];
            }

            if (!$this->smsSender->isEnabled()) {
                return ['sent' => false, 'error' => 'SMS service not enabled'];
            }

            $message = $this->buildSmsMessage($journey);
            $result = $this->smsSender->send($phone, $message);

            if ($result['success']) {
                Log::info('Journey SMS notification sent', [
                    'journey_id' => $journey->id,
                    'phone' => $phone,
                    'operation_code' => $journey->operation_code,
                    'message_id' => $result['message_id'],
                ]);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('Failed to send SMS notification', [
                'journey_id' => $journey->id,
                'error' => $e->getMessage(),
            ]);

            return ['sent' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Build SMS message content
     */
    protected function buildSmsMessage(Journey $journey): string
    {
        $locale = app()->getLocale();
        $code = $journey->operation_code;
        
        return match($locale) {
            'ar' => "جماركي: تم تفعيل خدمتك بنجاح. رمز العملية: {$code}",
            'zh' => "Gamarky: 您的服务已激活。操作代码: {$code}",
            default => "Gamarky: Your service is now active. Operation code: {$code}",
        };
    }
}
