<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Journey;
use App\Models\JourneyItem;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * JourneyController - API for Pay-per-Journey System
 * 
 * For mobile app & frontend integration
 * Handles journey creation, checkout, and status tracking
 */
class JourneyController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * List user's journeys
     * 
     * GET /api/v1/journeys
     * 
     * Query params:
     * - status: filter by status
     * - per_page: pagination (default 15)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Journey::where('user_id', $user->id)
            ->with(['items', 'payments', 'entitlement'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 15);
        $journeys = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $journeys,
        ]);
    }

    /**
     * Get user's active journeys
     * 
     * GET /api/v1/me/journeys
     */
    public function myJourneys()
    {
        $user = Auth::user();
        
        $journeys = Journey::where('user_id', $user->id)
            ->whereIn('status', ['active', 'pending_payment'])
            ->with(['items', 'payments', 'entitlement'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $journeys,
        ]);
    }

    /**
     * Get journey details
     * 
     * GET /api/v1/journeys/{id}
     */
    public function show(Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $journey->load(['items', 'payments', 'entitlement']);

        return response()->json([
            'success' => true,
            'data' => [
                'journey' => $journey,
                'summary' => [
                    'operation_code' => $journey->operation_code,
                    'status' => $journey->status,
                    'status_label' => $journey->status_label,
                    'service_total' => $journey->service_total,
                    'platform_total' => $journey->platform_total,
                    'grand_total' => $journey->grand_total,
                    'currency' => $journey->currency,
                    'notify_via' => $journey->notify_via,
                    'is_paid' => $journey->isPaid(),
                    'is_active' => $journey->isActive(),
                    'items_count' => $journey->items->count(),
                    'paid_services' => $journey->items->where('is_free', false)->count(),
                    'free_services' => $journey->items->where('is_free', true)->count(),
                ],
                'payment_status' => $this->getPaymentStatus($journey),
            ],
        ]);
    }

    /**
     * Create new journey (draft)
     * 
     * POST /api/v1/journeys
     * 
     * Body:
     * {
     *   "journey_type": "customs",
     *   "notify_via": "email",
     *   "contact_email": "user@example.com",
     *   "contact_phone": "+201234567890",
     *   "items": [
     *     {
     *       "service_key": "customs_clearance",
     *       "service_name": "تخليص جمركي",
     *       "provider_fee": 500,
     *       "platform_fee": 100,
     *       "is_free": false
     *     }
     *   ]
     * }
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'journey_type' => 'nullable|string|max:50',
            'notify_via' => ['nullable', Rule::in(['email', 'sms', 'both'])],
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_key' => 'required|string|max:100',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.service_description' => 'nullable|string',
            'items.*.provider_fee' => 'required|numeric|min:0',
            'items.*.platform_fee' => 'required|numeric|min:0',
            'items.*.is_free' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Create journey
            $journey = Journey::create([
                'user_id' => Auth::id(),
                'journey_type' => $request->journey_type,
                'notify_via' => $request->notify_via ?? 'email',
                'contact_email' => $request->contact_email ?? Auth::user()->email,
                'contact_phone' => $request->contact_phone,
                'notes' => $request->notes,
                'status' => 'draft',
            ]);

            // Create items
            foreach ($request->items as $itemData) {
                JourneyItem::create([
                    'journey_id' => $journey->id,
                    'service_key' => $itemData['service_key'],
                    'service_name' => $itemData['service_name'],
                    'service_description' => $itemData['service_description'] ?? null,
                    'provider_fee' => $itemData['provider_fee'],
                    'platform_fee' => $itemData['platform_fee'],
                    'is_free' => $itemData['is_free'] ?? false,
                    'status' => 'selected',
                ]);
            }

            // Calculate totals
            $journey->recalculateAndSave();

            DB::commit();

            $journey->load('items');

            return response()->json([
                'success' => true,
                'message' => 'Journey created successfully',
                'data' => $journey,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create journey',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update journey (draft only)
     * 
     * PUT /api/v1/journeys/{id}
     */
    public function update(Request $request, Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Only draft journeys can be updated
        if (!$journey->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft journeys can be updated',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'journey_type' => 'nullable|string|max:50',
            'notify_via' => ['nullable', Rule::in(['email', 'sms', 'both'])],
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $journey->update($request->only([
            'journey_type',
            'notify_via',
            'contact_email',
            'contact_phone',
            'notes',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Journey updated successfully',
            'data' => $journey->load('items'),
        ]);
    }

    /**
     * Checkout journey - Create payment intent
     * 
     * POST /api/v1/journeys/{id}/checkout
     * 
     * Body:
     * {
     *   "method": "card"  // card, wallet, kiosk, bank_transfer
     * }
     * 
     * Response:
     * {
     *   "success": true,
     *   "payment_url": "https://accept.paymob.com/...",
     *   "operation_code": "GMKY-20260215-XXXXX"
     * }
     */
    public function checkout(Request $request, Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Validate payment method
        $validator = Validator::make($request->all(), [
            'method' => ['nullable', Rule::in(['card', 'wallet', 'kiosk', 'bank_transfer'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if already paid
        if ($journey->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Journey already paid',
            ], 422);
        }

        // Create payment intent
        $result = $this->paymentService->createPaymentIntent($journey, [
            'method' => $request->method ?? 'card',
        ]);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['error'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment intent created successfully',
            'data' => [
                'payment_url' => $result['payment_url'],
                'operation_code' => $journey->operation_code,
                'amount' => $journey->grand_total,
                'currency' => $journey->currency,
                'payment_id' => $result['payment']->id,
            ],
        ]);
    }

    /**
     * Cancel journey
     * 
     * POST /api/v1/journeys/{id}/cancel
     */
    public function cancel(Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Can only cancel draft or pending payment
        if (!in_array($journey->status, ['draft', 'pending_payment'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this journey',
            ], 422);
        }

        $journey->markAsCancelled();

        return response()->json([
            'success' => true,
            'message' => 'Journey cancelled successfully',
        ]);
    }

    /**
     * Add item to journey
     * 
     * POST /api/v1/journeys/{id}/items
     */
    public function addItem(Request $request, Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Only draft journeys
        if (!$journey->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Can only add items to draft journeys',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'service_key' => 'required|string|max:100',
            'service_name' => 'required|string|max:255',
            'service_description' => 'nullable|string',
            'provider_fee' => 'required|numeric|min:0',
            'platform_fee' => 'required|numeric|min:0',
            'is_free' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = JourneyItem::create([
            'journey_id' => $journey->id,
            'service_key' => $request->service_key,
            'service_name' => $request->service_name,
            'service_description' => $request->service_description,
            'provider_fee' => $request->provider_fee,
            'platform_fee' => $request->platform_fee,
            'is_free' => $request->is_free ?? false,
            'status' => 'selected',
        ]);

        // Recalculate totals
        $journey->recalculateAndSave();

        return response()->json([
            'success' => true,
            'message' => 'Item added successfully',
            'data' => $item,
        ], 201);
    }

    /**
     * Remove item from journey
     * 
     * DELETE /api/v1/journeys/{id}/items/{item_id}
     */
    public function removeItem(Journey $journey, JourneyItem $item)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check item belongs to journey
        if ($item->journey_id !== $journey->id) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in this journey',
            ], 404);
        }

        // Only draft journeys
        if (!$journey->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Can only remove items from draft journeys',
            ], 422);
        }

        $item->delete();

        // Recalculate totals
        $journey->recalculateAndSave();

        return response()->json([
            'success' => true,
            'message' => 'Item removed successfully',
        ]);
    }

    /**
     * Get payment status for journey
     */
    protected function getPaymentStatus(Journey $journey): array
    {
        $latestPayment = $journey->getLatestPayment();
        $successfulPayment = $journey->getSuccessfulPayment();

        return [
            'has_payment' => $latestPayment !== null,
            'is_paid' => $successfulPayment !== null,
            'latest_payment' => $latestPayment ? [
                'id' => $latestPayment->id,
                'status' => $latestPayment->status,
                'status_label' => $latestPayment->status_label,
                'amount' => $latestPayment->amount_egp,
                'method' => $latestPayment->method,
                'method_label' => $latestPayment->method_label,
                'created_at' => $latestPayment->created_at,
                'paid_at' => $latestPayment->paid_at,
            ] : null,
        ];
    }
}
