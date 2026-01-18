<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Webhook Controller
 * 
 * Handles incoming webhook events from external systems
 * - Verifies webhook signature (HMAC SHA256)
 * - Stores event in database
 * - Triggers appropriate notification
 * 
 * Supported Events:
 * - booking.confirmed
 * - documents.completed
 * - tracking.updated
 */
class WebhookController extends Controller
{
    protected $notificationService;
    protected $secret;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->secret = config('services.webhook.secret', env('WEBHOOK_SECRET', 'gamarky-webhook-secret-2024'));
    }

    /**
     * Handle booking.confirmed webhook
     * 
     * Expected Payload:
     * {
     *   "event": "booking.confirmed",
     *   "timestamp": "2024-11-09T10:30:00Z",
     *   "data": {
     *     "booking_id": "BK-2024-001",
     *     "customer_name": "أحمد محمد",
     *     "customer_email": "ahmed@example.com",
     *     "customer_phone": "+966501234567",
     *     "booking_date": "2024-11-15",
     *     "service_type": "import",
     *     "total_amount": 15000,
     *     "currency": "SAR"
     *   }
     * }
     */
    public function bookingConfirmed(Request $request)
    {
        try {
            // 1. Verify webhook signature
            if (!$this->verifySignature($request)) {
                Log::warning('Webhook signature verification failed', [
                    'event' => 'booking.confirmed',
                    'ip' => $request->ip(),
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid signature',
                ], 401);
            }

            // 2. Validate payload
            $validated = $request->validate([
                'event' => 'required|string',
                'timestamp' => 'required|date',
                'data' => 'required|array',
                'data.booking_id' => 'required|string',
                'data.customer_name' => 'required|string',
                'data.customer_email' => 'required|email',
                'data.customer_phone' => 'nullable|string',
                'data.booking_date' => 'required|date',
                'data.service_type' => 'required|string',
                'data.total_amount' => 'required|numeric',
                'data.currency' => 'required|string',
            ]);

            // 3. Store webhook event
            $webhookLog = $this->storeWebhookEvent(
                'booking.confirmed',
                $validated,
                $request->ip()
            );

            // 4. Find or create user
            $user = \App\Models\User::where('email', $validated['data']['customer_email'])->first();
            
            if (!$user) {
                // For guest bookings, we can still send email notification
                Log::info('Booking webhook for non-registered user', [
                    'email' => $validated['data']['customer_email'],
                ]);
            }

            // 5. Send notification
            $notificationData = [
                'booking_id' => $validated['data']['booking_id'],
                'customer_name' => $validated['data']['customer_name'],
                'booking_date' => Carbon::parse($validated['data']['booking_date'])->format('Y-m-d'),
                'service_type' => $this->translateServiceType($validated['data']['service_type']),
                'total_amount' => number_format($validated['data']['total_amount'], 2),
                'currency' => $validated['data']['currency'],
                'booking_reference' => $validated['data']['booking_id'],
                'confirmation_link' => route('front.home') . '?booking=' . $validated['data']['booking_id'],
                'support_email' => config('mail.from.address', 'support@gamarky.com'),
            ];

            // Send via NotificationService
            $result = $this->notificationService->send(
                templateSlug: 'booking_confirmed',
                recipient: [
                    'user_id' => $user?->id,
                    'email' => $validated['data']['customer_email'],
                    'phone' => $validated['data']['customer_phone'] ?? null,
                    'name' => $validated['data']['customer_name'],
                ],
                data: $notificationData,
                channels: ['email', 'database'], // Send via email and in-app
                notifiableType: $user ? \App\Models\User::class : null,
                notifiableId: $user?->id
            );

            // 6. Update webhook log with notification result
            $this->updateWebhookLog($webhookLog, $result);

            Log::info('Booking confirmed webhook processed successfully', [
                'booking_id' => $validated['data']['booking_id'],
                'notification_sent' => $result['success'] ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'webhook_id' => $webhookLog->id,
                'notification_sent' => $result['success'] ?? false,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Booking webhook validation failed', [
                'errors' => $e->errors(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Invalid payload',
                'details' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Booking webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle documents.completed webhook
     * 
     * Expected Payload:
     * {
     *   "event": "documents.completed",
     *   "timestamp": "2024-11-09T14:20:00Z",
     *   "data": {
     *     "shipment_id": "SH-2024-042",
     *     "customer_name": "فاطمة علي",
     *     "customer_email": "fatima@example.com",
     *     "document_type": "customs_clearance",
     *     "document_url": "https://gamarky.com/docs/SH-2024-042-clearance.pdf",
     *     "completed_at": "2024-11-09T14:00:00Z"
     *   }
     * }
     */
    public function documentsCompleted(Request $request)
    {
        try {
            // 1. Verify signature
            if (!$this->verifySignature($request)) {
                return response()->json(['success' => false, 'error' => 'Invalid signature'], 401);
            }

            // 2. Validate payload
            $validated = $request->validate([
                'event' => 'required|string',
                'timestamp' => 'required|date',
                'data' => 'required|array',
                'data.shipment_id' => 'required|string',
                'data.customer_name' => 'required|string',
                'data.customer_email' => 'required|email',
                'data.document_type' => 'required|string',
                'data.document_url' => 'required|url',
                'data.completed_at' => 'required|date',
            ]);

            // 3. Store webhook event
            $webhookLog = $this->storeWebhookEvent(
                'documents.completed',
                $validated,
                $request->ip()
            );

            // 4. Find user
            $user = \App\Models\User::where('email', $validated['data']['customer_email'])->first();

            // 5. Send notification
            $notificationData = [
                'shipment_id' => $validated['data']['shipment_id'],
                'customer_name' => $validated['data']['customer_name'],
                'document_type' => $this->translateDocumentType($validated['data']['document_type']),
                'document_url' => $validated['data']['document_url'],
                'completed_at' => Carbon::parse($validated['data']['completed_at'])->format('Y-m-d H:i'),
                'download_link' => $validated['data']['document_url'],
            ];

            $result = $this->notificationService->send(
                templateSlug: 'documents_ready',
                recipient: [
                    'user_id' => $user?->id,
                    'email' => $validated['data']['customer_email'],
                    'name' => $validated['data']['customer_name'],
                ],
                data: $notificationData,
                channels: ['email', 'database'],
                notifiableType: $user ? \App\Models\User::class : null,
                notifiableId: $user?->id
            );

            // 6. Update webhook log
            $this->updateWebhookLog($webhookLog, $result);

            Log::info('Documents completed webhook processed', [
                'shipment_id' => $validated['data']['shipment_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'webhook_id' => $webhookLog->id,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Documents webhook failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle tracking.updated webhook
     * 
     * Expected Payload:
     * {
     *   "event": "tracking.updated",
     *   "timestamp": "2024-11-09T16:45:00Z",
     *   "data": {
     *     "tracking_number": "TRK-2024-789",
     *     "shipment_id": "SH-2024-042",
     *     "customer_name": "محمد سعيد",
     *     "customer_email": "mohammed@example.com",
     *     "customer_phone": "+966509876543",
     *     "status": "in_transit",
     *     "location": "جدة - الميناء",
     *     "estimated_arrival": "2024-11-12",
     *     "carrier": "DHL",
     *     "previous_status": "customs_clearance"
     *   }
     * }
     */
    public function trackingUpdated(Request $request)
    {
        try {
            // 1. Verify signature
            if (!$this->verifySignature($request)) {
                return response()->json(['success' => false, 'error' => 'Invalid signature'], 401);
            }

            // 2. Validate payload
            $validated = $request->validate([
                'event' => 'required|string',
                'timestamp' => 'required|date',
                'data' => 'required|array',
                'data.tracking_number' => 'required|string',
                'data.shipment_id' => 'required|string',
                'data.customer_name' => 'required|string',
                'data.customer_email' => 'required|email',
                'data.customer_phone' => 'nullable|string',
                'data.status' => 'required|string',
                'data.location' => 'required|string',
                'data.estimated_arrival' => 'nullable|date',
                'data.carrier' => 'nullable|string',
                'data.previous_status' => 'nullable|string',
            ]);

            // 3. Store webhook event
            $webhookLog = $this->storeWebhookEvent(
                'tracking.updated',
                $validated,
                $request->ip()
            );

            // 4. Find user
            $user = \App\Models\User::where('email', $validated['data']['customer_email'])->first();

            // 5. Send notification
            $notificationData = [
                'tracking_number' => $validated['data']['tracking_number'],
                'shipment_id' => $validated['data']['shipment_id'],
                'customer_name' => $validated['data']['customer_name'],
                'current_status' => $this->translateTrackingStatus($validated['data']['status']),
                'location' => $validated['data']['location'],
                'estimated_arrival' => $validated['data']['estimated_arrival'] 
                    ? Carbon::parse($validated['data']['estimated_arrival'])->format('Y-m-d')
                    : 'غير محدد',
                'carrier' => $validated['data']['carrier'] ?? 'N/A',
                'tracking_link' => route('front.home') . '?track=' . $validated['data']['tracking_number'],
                'status_update_time' => Carbon::parse($validated['timestamp'])->format('Y-m-d H:i'),
            ];

            $result = $this->notificationService->send(
                templateSlug: 'tracking_update',
                recipient: [
                    'user_id' => $user?->id,
                    'email' => $validated['data']['customer_email'],
                    'phone' => $validated['data']['customer_phone'] ?? null,
                    'name' => $validated['data']['customer_name'],
                ],
                data: $notificationData,
                channels: ['email', 'database', 'sms'], // Multi-channel for tracking updates
                notifiableType: $user ? \App\Models\User::class : null,
                notifiableId: $user?->id
            );

            // 6. Update webhook log
            $this->updateWebhookLog($webhookLog, $result);

            Log::info('Tracking updated webhook processed', [
                'tracking_number' => $validated['data']['tracking_number'],
                'status' => $validated['data']['status'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
                'webhook_id' => $webhookLog->id,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Tracking webhook failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify webhook signature using HMAC SHA256
     */
    protected function verifySignature(Request $request): bool
    {
        $signature = $request->header('X-Webhook-Signature');
        
        if (!$signature) {
            return false;
        }

        // Get raw body for signature verification
        $payload = $request->getContent();
        
        // Calculate expected signature
        $expectedSignature = hash_hmac('sha256', $payload, $this->secret);
        
        // Compare signatures (timing-safe comparison)
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Store webhook event in database
     */
    protected function storeWebhookEvent(string $eventType, array $payload, string $sourceIp)
    {
        return DB::table('incoming_webhook_logs')->insertGetId([
            'event_type' => $eventType,
            'payload' => json_encode($payload),
            'source_ip' => $sourceIp,
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Update webhook log with notification result
     */
    protected function updateWebhookLog($logId, array $result): void
    {
        DB::table('incoming_webhook_logs')
            ->where('id', $logId)
            ->update([
                'status' => ($result['success'] ?? false) ? 'processed' : 'failed',
                'response' => json_encode($result),
                'processed_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }

    /**
     * Translate service type to Arabic
     */
    protected function translateServiceType(string $type): string
    {
        return match($type) {
            'import' => 'استيراد',
            'export' => 'تصدير',
            'manufacturing' => 'تصنيع',
            'customs' => 'تخليص جمركي',
            'containers' => 'شحن حاويات',
            'agent' => 'وكيل تجاري',
            default => $type,
        };
    }

    /**
     * Translate document type to Arabic
     */
    protected function translateDocumentType(string $type): string
    {
        return match($type) {
            'customs_clearance' => 'تخليص جمركي',
            'bill_of_lading' => 'بوليصة شحن',
            'commercial_invoice' => 'فاتورة تجارية',
            'packing_list' => 'قائمة تعبئة',
            'certificate_of_origin' => 'شهادة منشأ',
            'inspection_certificate' => 'شهادة فحص',
            default => $type,
        };
    }

    /**
     * Translate tracking status to Arabic
     */
    protected function translateTrackingStatus(string $status): string
    {
        return match($status) {
            'pending' => 'قيد الانتظار',
            'in_transit' => 'في الطريق',
            'customs_clearance' => 'تخليص جمركي',
            'out_for_delivery' => 'خارج للتسليم',
            'delivered' => 'تم التسليم',
            'on_hold' => 'معلق',
            'returned' => 'مرتجع',
            default => $status,
        };
    }

    /**
     * Test webhook endpoint (for development)
     */
    public function test(Request $request)
    {
        if (!app()->environment('local')) {
            return response()->json(['error' => 'Only available in local environment'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook endpoint is working',
            'timestamp' => Carbon::now()->toIso8601String(),
            'signature_header' => $request->header('X-Webhook-Signature'),
            'payload' => $request->all(),
        ]);
    }
}
