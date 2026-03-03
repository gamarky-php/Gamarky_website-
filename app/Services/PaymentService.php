<?php

namespace App\Services;

use App\Models\Journey;
use App\Models\Payment;
use App\Models\JourneyItem;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * PaymentService - Paymob Integration for Journey-based Payments
 * 
 * Handles:
 * - Creating payment intents with Paymob (EGP only)
 * - Processing webhook callbacks (HMAC verification)
 * - Updating journey status on successful payment
 * - Triggering notifications (email/SMS with operation code)
 */
class PaymentService
{
    protected $apiKey;
    protected $apiUrl;
    protected $hmacSecret;
    protected $integrationIdCard;
    protected $iframeId;
    protected $notificationService;

    public function __construct(NotificationService $notificationService = null)
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->apiUrl = config('services.paymob.api_url');
        $this->hmacSecret = config('services.paymob.hmac_secret');
        $this->integrationIdCard = config('services.paymob.integration_id_card');
        $this->iframeId = config('services.paymob.iframe_id');
        $this->notificationService = $notificationService ?? app(NotificationService::class);
    }

    // ========================================
    // PAYMENT INTENT CREATION
    // ========================================

    /**
     * Create Paymob payment intent for a journey
     * 
     * @param Journey $journey
     * @param array $options ['method' => 'card|wallet|kiosk', 'integration_id' => override]
     * @return array ['success' => bool, 'payment' => Payment, 'payment_url' => string, 'error' => string]
     */
    public function createPaymentIntent(Journey $journey, array $options = []): array
    {
        try {
            // Validate journey is ready for payment
            if (!$this->canCreatePayment($journey)) {
                return ['success' => false, 'error' => 'Journey not ready for payment'];
            }

            // Calculate totals
            $journey->recalculateAndSave();

            if ($journey->grand_total <= 0) {
                return ['success' => false, 'error' => 'Journey total must be greater than 0'];
            }

            // Step 1: Authenticate with Paymob
            $authToken = $this->authenticate();
            if (!$authToken) {
                return ['success' => false, 'error' => 'Failed to authenticate with Paymob'];
            }

            // Step 2: Create order
            $orderData = $this->createOrder($authToken, $journey);
            if (!$orderData || !isset($orderData['id'])) {
                return ['success' => false, 'error' => 'Failed to create Paymob order'];
            }

            // Step 3: Create payment key
            $integrationId = $options['integration_id'] ?? $this->integrationIdCard;
            $paymentKey = $this->createPaymentKey($authToken, $journey, $orderData['id'], $integrationId);
            
            if (!$paymentKey) {
                return ['success' => false, 'error' => 'Failed to create payment key'];
            }

            // Step 4: Create Payment record
            $payment = $this->storePayment($journey, [
                'provider_reference' => (string)$orderData['id'],
                'provider_payment_key' => $paymentKey,
                'amount_egp' => $journey->grand_total,
                'status' => 'pending',
                'method' => $options['method'] ?? 'card',
                'idempotency_key' => Str::uuid(),
            ]);

            // Step 5: Update journey status
            $journey->markAsPendingPayment();

            // Step 6: Generate payment URL
            $paymentUrl = $this->generatePaymentUrl($paymentKey);

            Log::info('Payment intent created', [
                'journey_id' => $journey->id,
                'payment_id' => $payment->id,
                'order_id' => $orderData['id'],
            ]);

            return [
                'success' => true,
                'payment' => $payment,
                'payment_url' => $paymentUrl,
                'order_id' => $orderData['id'],
            ];

        } catch (\Exception $e) {
            Log::error('Payment intent creation failed', [
                'journey_id' => $journey->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Step 1: Authenticate with Paymob
     */
    protected function authenticate(): ?string
    {
        try {
            $response = Http::post("{$this->apiUrl}/auth/tokens", [
                'api_key' => $this->apiKey,
            ]);

            if ($response->successful() && isset($response['token'])) {
                return $response['token'];
            }

            Log::error('Paymob authentication failed', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Paymob authentication exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Step 2: Create order in Paymob
     */
    protected function createOrder(string $authToken, Journey $journey): ?array
    {
        try {
            $amountCents = $this->convertToPaymobCents($journey->grand_total);

            $response = Http::post("{$this->apiUrl}/ecommerce/orders", [
                'auth_token' => $authToken,
                'delivery_needed' => 'false',
                'amount_cents' => $amountCents,
                'currency' => 'EGP',
                'merchant_order_id' => $journey->id,
                'items' => $this->prepareOrderItems($journey),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Paymob order creation failed', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Paymob order creation exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Step 3: Create payment key
     */
    protected function createPaymentKey(string $authToken, Journey $journey, int $orderId, int $integrationId): ?string
    {
        try {
            $amountCents = $this->convertToPaymobCents($journey->grand_total);

            $billingData = $this->prepareBillingData($journey);

            $response = Http::post("{$this->apiUrl}/acceptance/payment_keys", [
                'auth_token' => $authToken,
                'amount_cents' => $amountCents,
                'expiration' => 3600, // 1 hour
                'order_id' => $orderId,
                'billing_data' => $billingData,
                'currency' => 'EGP',
                'integration_id' => $integrationId,
            ]);

            if ($response->successful() && isset($response['token'])) {
                return $response['token'];
            }

            Log::error('Paymob payment key creation failed', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Paymob payment key creation exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    // ========================================
    // WEBHOOK PROCESSING
    // ========================================

    /**
     * Process Paymob webhook callback
     * 
     * @param array $payload Webhook data from Paymob
     * @param string|null $hmacHeader HMAC signature from request header
     * @return array ['success' => bool, 'message' => string]
     */
    public function processWebhook(array $payload, ?string $hmacHeader = null): array
    {
        try {
            // Step 1: Verify HMAC
            if (!$this->verifyHmac($payload, $hmacHeader)) {
                Log::warning('Paymob webhook HMAC verification failed');
                return ['success' => false, 'message' => 'HMAC verification failed'];
            }

            // Step 2: Extract data
            $obj = $payload['obj'] ?? null;
            if (!$obj) {
                return ['success' => false, 'message' => 'Invalid payload structure'];
            }

            $orderId = $obj['order']['id'] ?? null;
            $transactionId = $obj['id'] ?? null;
            $success = $obj['success'] ?? false;
            $pending = $obj['pending'] ?? false;
            $amountCents = $obj['amount_cents'] ?? 0;
            $currency = $obj['currency'] ?? 'EGP';
            $paymentMethod = $obj['source_data']['type'] ?? null;

            // Step 3: Find payment record
            $payment = Payment::where('provider_reference', (string)$orderId)->first();

            if (!$payment) {
                Log::warning('Payment not found for Paymob webhook', ['order_id' => $orderId]);
                return ['success' => false, 'message' => 'Payment not found'];
            }

            // Step 4: Idempotency check
            if ($payment->isProcessed()) {
                Log::info('Payment already processed', ['payment_id' => $payment->id]);
                return ['success' => true, 'message' => 'Already processed'];
            }

            // Step 5: Process payment status
            DB::transaction(function () use ($payment, $obj, $success, $pending, $transactionId, $paymentMethod) {
                if ($success && !$pending) {
                    // Payment successful
                    $this->markPaid($payment, $obj, $transactionId, $paymentMethod);
                } elseif (!$success && !$pending) {
                    // Payment failed
                    $this->markFailed($payment, $obj);
                } else {
                    // Still pending
                    $payment->markAsPending();
                }

                // Mark as verified
                $payment->markAsVerified();
            });

            Log::info('Paymob webhook processed successfully', [
                'payment_id' => $payment->id,
                'transaction_id' => $transactionId,
                'success' => $success,
            ]);

            return ['success' => true, 'message' => 'Webhook processed'];

        } catch (\Exception $e) {
            Log::error('Paymob webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Verify HMAC signature from Paymob
     */
    public function verifyHmac(array $payload, ?string $providedHmac = null): bool
    {
        if (!$this->hmacSecret) {
            Log::warning('HMAC secret not configured, skipping verification');
            return true; // Allow in development
        }

        if (!$providedHmac) {
            return false;
        }

        // Extract relevant fields for HMAC calculation (Paymob specific order)
        $obj = $payload['obj'] ?? [];
        
        $concatenatedString = 
            ($obj['amount_cents'] ?? '') .
            ($obj['created_at'] ?? '') .
            ($obj['currency'] ?? '') .
            ($obj['error_occured'] ?? '') .
            ($obj['has_parent_transaction'] ?? '') .
            ($obj['id'] ?? '') .
            ($obj['integration_id'] ?? '') .
            ($obj['is_3d_secure'] ?? '') .
            ($obj['is_auth'] ?? '') .
            ($obj['is_capture'] ?? '') .
            ($obj['is_refunded'] ?? '') .
            ($obj['is_standalone_payment'] ?? '') .
            ($obj['is_voided'] ?? '') .
            ($obj['order']['id'] ?? '') .
            ($obj['owner'] ?? '') .
            ($obj['pending'] ?? '') .
            ($obj['source_data']['pan'] ?? '') .
            ($obj['source_data']['sub_type'] ?? '') .
            ($obj['source_data']['type'] ?? '') .
            ($obj['success'] ?? '');

        $calculatedHmac = hash_hmac('sha512', $concatenatedString, $this->hmacSecret);

        return hash_equals($calculatedHmac, $providedHmac);
    }

    /**
     * Mark payment as paid and activate journey
     */
    protected function markPaid(Payment $payment, array $obj, $transactionId, $paymentMethod): void
    {
        $payment->markAsPaid([
            'method' => $paymentMethod,
            'method_details' => $obj['source_data']['pan'] ?? null,
            'raw_payload' => $obj,
        ]);

        // Activate journey
        $journey = $payment->journey;
        $journey->markAsActive();

        // Mark all items as paid
        $journey->items()->where('status', 'selected')->update(['status' => 'paid']);

        // Send notification with operation code
        $this->sendPaymentSuccessNotification($journey);

        Log::info('Payment marked as paid and journey activated', [
            'payment_id' => $payment->id,
            'journey_id' => $journey->id,
            'operation_code' => $journey->operation_code,
        ]);
    }

    /**
     * Mark payment as failed
     */
    protected function markFailed(Payment $payment, array $obj): void
    {
        $failureReason = $obj['data']['message'] ?? 'Payment failed';
        $payment->markAsFailed($failureReason);

        Log::info('Payment marked as failed', [
            'payment_id' => $payment->id,
            'reason' => $failureReason,
        ]);
    }

    // ========================================
    // NOTIFICATIONS
    // ========================================

    /**
     * Send payment success notification with operation code
     */
    /**
     * Send payment success notification with operation code
     */
    protected function sendPaymentSuccessNotification(Journey $journey): void
    {
        try {
            $notificationService = app(JourneyNotificationService::class);
            $results = $notificationService->sendPaymentSuccessNotification($journey);
            
            Log::info('Journey payment success notifications sent', [
                'journey_id' => $journey->id,
                'operation_code' => $journey->operation_code,
                'email_sent' => $results['email']['sent'] ?? false,
                'sms_sent' => $results['sms']['sent'] ?? false,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment success notification', [
                'journey_id' => $journey->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    protected function canCreatePayment(Journey $journey): bool
    {
        return $journey->isDraft() || $journey->isPendingPayment();
    }

    protected function storePayment(Journey $journey, array $data): Payment
    {
        return Payment::create(array_merge([
            'journey_id' => $journey->id,
            'user_id' => $journey->user_id,
            'provider' => 'paymob',
            'currency' => 'EGP',
        ], $data));
    }

    protected function convertToPaymobCents(float $amount): int
    {
        return (int)round($amount * 100);
    }

    protected function prepareOrderItems(Journey $journey): array
    {
        return $journey->items()
            ->where('is_free', false)
            ->get()
            ->map(function (JourneyItem $item) {
                return [
                    'name' => $item->service_name,
                    'amount_cents' => $this->convertToPaymobCents($item->item_total),
                    'description' => $item->service_description ?? '',
                    'quantity' => 1,
                ];
            })
            ->toArray();
    }

    protected function prepareBillingData(Journey $journey): array
    {
        $user = $journey->user;

        return [
            'email' => $journey->getNotificationEmail() ?? 'customer@gamarky.com',
            'first_name' => $user->name ?? 'Customer',
            'last_name' => $user->last_name ?? '',
            'phone_number' => $journey->getNotificationPhone() ?? '+20xxxxxxxxxx',
            'country' => 'EG',
            'city' => 'Cairo',
            'street' => 'N/A',
            'building' => 'N/A',
            'floor' => 'N/A',
            'apartment' => 'N/A',
            'shipping_method' => 'N/A',
        ];
    }

    protected function generatePaymentUrl(string $paymentKey): string
    {
        if ($this->iframeId) {
            return "https://accept.paymobsolutions.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentKey}";
        }

        return "https://accept.paymob.com/api/acceptance/post_pay?payment_token={$paymentKey}";
    }
}
