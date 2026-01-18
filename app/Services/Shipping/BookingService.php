<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

/**
 * خدمة إدارة الحجوزات
 * Booking Management Service
 */
class BookingService
{
    /**
     * إنشاء حجز جديد
     *
     * @param array $bookingData
     * @return array
     */
    public function createBooking(array $bookingData): array
    {
        try {
            // في الإنتاج، هنا سيتم الحفظ في قاعدة البيانات
            // Booking::create($bookingData);
            
            // تخزين مؤقت في الجلسة
            session()->put('booking_' . $bookingData['reference'], $bookingData);
            
            Log::info('Booking created successfully', [
                'reference' => $bookingData['reference'],
                'user_id' => $bookingData['user_id'],
            ]);
            
            return $bookingData;
            
        } catch (\Exception $e) {
            Log::error('Failed to create booking: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * إرسال إشعارات الحجز (بريد، واتساب، موقع)
     *
     * @param array $booking
     * @return void
     */
    public function sendNotifications(array $booking): void
    {
        // 1. إشعار البريد الإلكتروني
        $this->sendEmailNotification($booking);
        
        // 2. إشعار واتساب
        $this->sendWhatsAppNotification($booking);
        
        // 3. إشعار الموقع (داخلي)
        $this->sendSiteNotification($booking);
        
        // 4. إشعار الإدارة
        $this->notifyAdmins($booking);
    }

    /**
     * إرسال إشعار بريد إلكتروني
     *
     * @param array $booking
     * @return void
     */
    private function sendEmailNotification(array $booking): void
    {
        try {
            // في الإنتاج، استخدم Mailable class
            $emailData = [
                'subject' => 'تأكيد حجز الحاوية - ' . $booking['reference'],
                'booking' => $booking,
                'message' => $this->generateEmailMessage($booking),
            ];
            
            // Send to shipper
            // Mail::to($booking['shipper_email'])->send(new BookingConfirmation($emailData));
            
            Log::info('Email notification sent', [
                'reference' => $booking['reference'],
                'email' => $booking['shipper_email'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send email notification: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار واتساب
     *
     * @param array $booking
     * @return void
     */
    private function sendWhatsAppNotification(array $booking): void
    {
        try {
            // استخدام Twilio أو WhatsApp Business API
            $message = $this->generateWhatsAppMessage($booking);
            
            // مثال باستخدام Twilio
            if (config('services.twilio.sid')) {
                $phone = $this->formatPhoneNumber($booking['shipper_phone']);
                
                // Http::post('https://api.twilio.com/2010-04-01/Accounts/' . config('services.twilio.sid') . '/Messages.json', [
                //     'From' => 'whatsapp:' . config('services.twilio.from'),
                //     'To' => 'whatsapp:' . $phone,
                //     'Body' => $message,
                // ])->throw();
            }
            
            Log::info('WhatsApp notification sent', [
                'reference' => $booking['reference'],
                'phone' => $booking['shipper_phone'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    /**
     * إرسال إشعار داخل الموقع
     *
     * @param array $booking
     * @return void
     */
    private function sendSiteNotification(array $booking): void
    {
        try {
            // في الإنتاج، استخدم Laravel Notifications
            if ($booking['user_id']) {
                // $user = User::find($booking['user_id']);
                // $user->notify(new BookingCreatedNotification($booking));
            }
            
            Log::info('Site notification sent', [
                'reference' => $booking['reference'],
                'user_id' => $booking['user_id'],
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send site notification: ' . $e->getMessage());
        }
    }

    /**
     * إشعار فريق الإدارة
     *
     * @param array $booking
     * @return void
     */
    private function notifyAdmins(array $booking): void
    {
        try {
            // إرسال إشعار للإدارة
            Log::info('New booking requires admin attention', [
                'reference' => $booking['reference'],
                'total_price' => $booking['total_price'],
                'shipper' => $booking['shipper_company'],
            ]);
            
            // في الإنتاج، إرسال بريد للإدارة
            // Mail::to(config('mail.admin_email'))->send(new NewBookingAdminNotification($booking));
            
        } catch (\Exception $e) {
            Log::error('Failed to notify admins: ' . $e->getMessage());
        }
    }

    /**
     * إضافة نقاط أداء للوكيل
     *
     * @param string $provider
     * @param int $points
     * @return void
     */
    public function addAgentPoints(string $provider, int $points = 10): void
    {
        try {
            // في الإنتاج، تحديث نقاط الوكيل في قاعدة البيانات
            // Agent::where('provider_key', $provider)->increment('performance_points', $points);
            
            Log::info('Performance points added to agent', [
                'provider' => $provider,
                'points' => $points,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to add agent points: ' . $e->getMessage());
        }
    }

    /**
     * توليد رسالة البريد الإلكتروني
     *
     * @param array $booking
     * @return string
     */
    private function generateEmailMessage(array $booking): string
    {
        return "
            عزيزنا {$booking['shipper_name']},
            
            نشكركم على ثقتكم في خدماتنا. تم تأكيد حجز الحاوية الخاص بكم بنجاح.
            
            **تفاصيل الحجز:**
            - رقم المرجع: {$booking['reference']}
            - نوع الحاوية: {$booking['container_type']}
            - العدد: {$booking['container_quantity']}
            - تاريخ التحميل: {$booking['preferred_loading_date']}
            - المبلغ الإجمالي: \${$booking['total_price']}
            
            سيتم التواصل معكم قريباً لتأكيد التفاصيل النهائية.
            
            مع تحيات فريق جماركي
        ";
    }

    /**
     * توليد رسالة واتساب
     *
     * @param array $booking
     * @return string
     */
    private function generateWhatsAppMessage(array $booking): string
    {
        return "🎉 *تأكيد حجز الحاوية*\n\n"
            . "مرحباً {$booking['shipper_name']},\n\n"
            . "✅ تم تأكيد حجزك بنجاح!\n\n"
            . "📋 *التفاصيل:*\n"
            . "• المرجع: {$booking['reference']}\n"
            . "• الحاوية: {$booking['container_type']} × {$booking['container_quantity']}\n"
            . "• التاريخ: {$booking['preferred_loading_date']}\n"
            . "• المبلغ: \${$booking['total_price']}\n\n"
            . "📞 سنتواصل معك قريباً لتأكيد التفاصيل.\n\n"
            . "شكراً لثقتك 🙏\n"
            . "فريق جماركي";
    }

    /**
     * تنسيق رقم الهاتف للواتساب
     *
     * @param string $phone
     * @return string
     */
    private function formatPhoneNumber(string $phone): string
    {
        // إزالة المسافات والرموز
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // إضافة رمز الدولة إذا لم يكن موجوداً
        if (!str_starts_with($phone, '+')) {
            $phone = '+966' . ltrim($phone, '0'); // افتراض السعودية
        }
        
        return $phone;
    }

    /**
     * توليد ملف PDF لتأكيد الحجز
     *
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function generateConfirmationPDF(string $reference)
    {
        try {
            $booking = session('booking_' . $reference);
            
            if (!$booking) {
                abort(404, 'Booking not found');
            }
            
            // في الإنتاج، استخدم مكتبة PDF مثل DomPDF أو TCPDF
            // $pdf = PDF::loadView('pdf.booking-confirmation', compact('booking'));
            // return $pdf->download('booking-' . $reference . '.pdf');
            
            // مؤقتاً، إرجاع JSON
            return response()->json($booking)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="booking-' . $reference . '.json"');
            
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }

    /**
     * الحصول على حالة الحجز
     *
     * @param string $reference
     * @return array|null
     */
    public function getBookingStatus(string $reference): ?array
    {
        try {
            // في الإنتاج، جلب من قاعدة البيانات
            // return Booking::where('reference', $reference)->first();
            
            return session('booking_' . $reference);
            
        } catch (\Exception $e) {
            Log::error('Failed to get booking status: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * تحديث حالة الحجز
     *
     * @param string $reference
     * @param string $status
     * @return bool
     */
    public function updateBookingStatus(string $reference, string $status): bool
    {
        try {
            // في الإنتاج، تحديث في قاعدة البيانات
            // Booking::where('reference', $reference)->update(['status' => $status]);
            
            $booking = session('booking_' . $reference);
            if ($booking) {
                $booking['status'] = $status;
                session()->put('booking_' . $reference, $booking);
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('Failed to update booking status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * إلغاء الحجز
     *
     * @param string $reference
     * @param string $reason
     * @return bool
     */
    public function cancelBooking(string $reference, string $reason = ''): bool
    {
        try {
            // في الإنتاج، تحديث في قاعدة البيانات
            // Booking::where('reference', $reference)->update([
            //     'status' => 'cancelled',
            //     'cancellation_reason' => $reason,
            //     'cancelled_at' => now(),
            // ]);
            
            $booking = session('booking_' . $reference);
            if ($booking) {
                $booking['status'] = 'cancelled';
                $booking['cancellation_reason'] = $reason;
                session()->put('booking_' . $reference, $booking);
                
                // إرسال إشعار بالإلغاء
                $this->sendCancellationNotification($booking);
                
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('Failed to cancel booking: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * إرسال إشعار بالإلغاء
     *
     * @param array $booking
     * @return void
     */
    private function sendCancellationNotification(array $booking): void
    {
        try {
            Log::info('Booking cancelled notification sent', [
                'reference' => $booking['reference'],
                'reason' => $booking['cancellation_reason'] ?? 'No reason provided',
            ]);
            
            // إرسال بريد وواتساب بالإلغاء
            
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation notification: ' . $e->getMessage());
        }
    }

    /**
     * حساب الرسوم الإضافية (إن وُجدت)
     *
     * @param array $booking
     * @return float
     */
    public function calculateAdditionalFees(array $booking): float
    {
        $fees = 0.0;
        
        // رسوم المستندات الإضافية
        if (!empty($booking['certificate_of_origin_path'])) {
            $fees += 50; // رسوم شهادة المنشأ
        }
        
        // رسوم الخدمات الخاصة
        if (!empty($booking['special_requirements'])) {
            $fees += count($booking['special_requirements']) * 25;
        }
        
        // رسوم التسليم للباب (Door-to-Door)
        if ($booking['is_door_to_door'] ?? false) {
            $fees += 200;
        }
        
        return $fees;
    }
}
