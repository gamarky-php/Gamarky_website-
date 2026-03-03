<?php

namespace App\Http\Controllers;

use App\Models\Journey;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * FrontJourneyController - Web UI for Pay-per-Journey
 * 
 * Handles journey management for website frontend
 */
class JourneyController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show journey pricing page
     * GET /journey
     */
    public function index()
    {
        return view('front.journey.index');
    }

    /**
     * Show my journeys (requires auth)
     * GET /journey/my
     */
    public function myJourneys()
    {
        $user = Auth::user();
        
        $journeys = Journey::where('user_id', $user->id)
            ->with(['items', 'payments', 'entitlement'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('front.journey.my-journeys', compact('journeys'));
    }

    /**
     * Create new journey
     * GET /journey/create
     */
    public function create()
    {
        return view('front.journey.create');
    }

    /**
     * Store new journey
     * POST /journey
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'journey_type' => 'required|string',
            'notify_via' => 'required|in:email,sms,both',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_key' => 'required|string',
            'items.*.service_name' => 'required|string',
            'items.*.provider_fee' => 'required|numeric|min:0',
            'items.*.platform_fee' => 'required|numeric|min:0',
            'items.*.is_free' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Create journey
            $journey = Journey::create([
                'user_id' => Auth::id(),
                'journey_type' => $validated['journey_type'],
                'notify_via' => $validated['notify_via'],
                'contact_email' => $validated['contact_email'] ?? Auth::user()->email,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'status' => 'draft',
            ]);

            // Create items
            foreach ($validated['items'] as $itemData) {
                $journey->items()->create($itemData);
            }

            // Calculate totals
            $journey->recalculateAndSave();

            DB::commit();

            return redirect()->route('front.journey.show', $journey)
                ->with('success', __('Journey created successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', __('Failed to create journey: ') . $e->getMessage());
        }
    }

    /**
     * Show journey details
     * GET /journey/{id}
     */
    public function show(Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $journey->load(['items', 'payments', 'entitlement']);

        return view('front.journey.show', compact('journey'));
    }

    /**
     * Proceed to checkout
     * POST /journey/{id}/checkout
     */
    public function checkout(Journey $journey)
    {
        // Authorization check
        if ($journey->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if already paid
        if ($journey->isPaid()) {
            return redirect()->route('front.journey.show', $journey)
                ->with('error', __('Journey already paid'));
        }

        // Create payment intent
        $result = $this->paymentService->createPaymentIntent($journey, [
            'method' => 'card',
        ]);

        if (!$result['success']) {
            return back()->with('error', $result['error']);
        }

        // Redirect to Paymob payment page
        return redirect($result['payment_url']);
    }

    /**
     * Payment success callback
     * GET /journey/success
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order');
        
        if (!$orderId) {
            return redirect()->route('front.journey.index')
                ->with('error', __('Invalid payment reference'));
        }

        // Find payment
        $payment = \App\Models\Payment::where('provider_reference', $orderId)->first();
        
        if (!$payment) {
            return redirect()->route('front.journey.index')
                ->with('error', __('Payment not found'));
        }

        $journey = $payment->journey;

        return view('front.journey.success', compact('journey', 'payment'));
    }

    /**
     * Payment failed callback
     * GET /journey/failed
     */
    public function failed(Request $request)
    {
        $orderId = $request->query('order');
        
        if (!$orderId) {
            return redirect()->route('front.journey.index')
                ->with('error', __('Invalid payment reference'));
        }

        // Find payment
        $payment = \App\Models\Payment::where('provider_reference', $orderId)->first();
        
        if ($payment) {
            $journey = $payment->journey;
            return view('front.journey.failed', compact('journey', 'payment'));
        }

        return view('front.journey.failed');
    }
}
