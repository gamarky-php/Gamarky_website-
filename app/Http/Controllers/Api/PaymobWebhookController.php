<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PaymobWebhookController
 * 
 * Handles incoming webhooks from Paymob payment gateway
 * - HMAC verification
 * - Payment status updates
 * - Journey activation
 * - Idempotent processing
 */
class PaymobWebhookController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Paymob transaction callback (webhook)
     * 
     * POST /api/webhooks/paymob/transaction
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleTransaction(Request $request)
    {
        try {
            // Get HMAC from query parameter (Paymob sends it in URL)
            $hmac = $request->query('hmac') ?? $request->header('X-Paymob-HMAC');
            
            // Get payload
            $payload = $request->all();

            // Log incoming webhook
            $this->logIncomingWebhook($request, $payload, $hmac);

            // Process webhook
            $result = $this->paymentService->processWebhook($payload, $hmac);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'] ?? 'Webhook processed successfully',
                ], 200);
            }

            // Even on failure, return 200 to prevent retries
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Webhook processing failed',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Paymob webhook exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Always return 200 OK to Paymob
            return response()->json([
                'success' => false,
                'message' => 'Internal error',
            ], 200);
        }
    }

    /**
     * Handle Paymob transaction response (redirect after payment)
     * 
     * GET /api/webhooks/paymob/response
     * This is called when customer is redirected back after payment
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleResponse(Request $request)
    {
        try {
            $success = filter_var($request->query('success'), FILTER_VALIDATE_BOOLEAN);
            $orderId = $request->query('order');
            $transactionId = $request->query('id');
            $hmac = $request->query('hmac');

            // Log response
            Log::info('Paymob payment response received', [
                'success' => $success,
                'order_id' => $orderId,
                'transaction_id' => $transactionId,
            ]);

            // Process the response (similar to webhook)
            $payload = $request->all();
            $this->paymentService->processWebhook(['obj' => $payload], $hmac);

            // Redirect to appropriate page
            if ($success) {
                return redirect()->route('front.journey.success', ['order' => $orderId])
                    ->with('success', __('Payment completed successfully'));
            } else {
                return redirect()->route('front.journey.failed', ['order' => $orderId])
                    ->with('error', __('Payment failed. Please try again.'));
            }

        } catch (\Exception $e) {
            Log::error('Paymob response handling exception', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('front.journey.index')
                ->with('error', __('An error occurred processing your payment.'));
        }
    }

    /**
     * Health check endpoint
     * 
     * GET /api/webhooks/paymob/health
     */
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'paymob-webhook',
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Log incoming webhook to database
     */
    protected function logIncomingWebhook(Request $request, array $payload, ?string $hmac): void
    {
        try {
            DB::table('incoming_webhook_logs')->insert([
                'source' => 'paymob',
                'event_type' => $payload['type'] ?? 'transaction',
                'payload' => json_encode($payload),
                'signature' => $hmac,
                'verified' => false, // Will be updated by service
                'processed' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log incoming webhook', ['error' => $e->getMessage()]);
        }
    }
}
