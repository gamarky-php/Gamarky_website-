<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Subscriptions API Controller
 * 
 * Features:
 * - Subscription plans (free, pro, enterprise)
 * - User subscriptions
 * - Simple billing (stub for payment gateway)
 * - Access restrictions by plan
 * - Plan upgrades/downgrades
 */
class SubscriptionsController extends Controller
{
    /**
     * Get all plans
     * 
     * GET /api/subscriptions/plans
     */
    public function getPlans()
    {
        $plans = DB::table('subscription_plans')
            ->where('active', true)
            ->orderBy('price')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $plans->map(function($plan) {
                $plan->features = json_decode($plan->features ?? '[]', true);
                $plan->limits = json_decode($plan->limits ?? '{}', true);
                return $plan;
            })
        ]);
    }

    /**
     * Get user's subscription
     * 
     * GET /api/subscriptions/my-subscription
     */
    public function getMySubscription()
    {
        $subscription = DB::table('user_subscriptions')
            ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
            ->where('user_subscriptions.user_id', auth()->id())
            ->where('user_subscriptions.status', 'active')
            ->select('user_subscriptions.*', 'subscription_plans.name as plan_name', 'subscription_plans.slug as plan_slug')
            ->first();
        
        if (!$subscription) {
            // Return free plan as default
            return response()->json([
                'success' => true,
                'data' => [
                    'plan_name' => 'Free',
                    'plan_slug' => 'free',
                    'status' => 'active',
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => $subscription
        ]);
    }

    /**
     * Subscribe to plan
     * 
     * POST /api/subscriptions/subscribe
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|integer|exists:subscription_plans,id',
            'payment_method' => 'nullable|string',
        ]);
        
        $plan = DB::table('subscription_plans')->where('id', $validated['plan_id'])->first();
        
        if (!$plan || !$plan->active) {
            return response()->json([
                'success' => false,
                'error' => 'Plan not available'
            ], 400);
        }
        
        // Cancel existing subscription
        DB::table('user_subscriptions')
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
            ]);
        
        // Create new subscription
        $subscriptionId = DB::table('user_subscriptions')->insertGetId([
            'user_id' => auth()->id(),
            'plan_id' => $validated['plan_id'],
            'status' => $plan->price > 0 ? 'pending' : 'active',
            'started_at' => Carbon::now(),
            'expires_at' => $plan->billing_cycle === 'monthly' 
                ? Carbon::now()->addMonth() 
                : Carbon::now()->addYear(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // If free plan, activate immediately
        if ($plan->price == 0) {
            return response()->json([
                'success' => true,
                'message' => 'Subscribed to ' . $plan->name . ' plan',
                'data' => ['subscription_id' => $subscriptionId]
            ], 201);
        }
        
        // For paid plans, create payment intent (stub)
        $paymentIntent = [
            'payment_url' => '/payment/' . $subscriptionId,
            'amount' => $plan->price,
            'currency' => $plan->currency,
        ];
        
        return response()->json([
            'success' => true,
            'message' => 'Payment required',
            'data' => [
                'subscription_id' => $subscriptionId,
                'payment_intent' => $paymentIntent
            ]
        ], 201);
    }

    /**
     * Cancel subscription
     * 
     * POST /api/subscriptions/cancel
     */
    public function cancel()
    {
        $updated = DB::table('user_subscriptions')
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
            ]);
        
        if (!$updated) {
            return response()->json([
                'success' => false,
                'error' => 'No active subscription found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled'
        ]);
    }

    /**
     * Check feature access
     * 
     * POST /api/subscriptions/check-access
     */
    public function checkAccess(Request $request)
    {
        $validated = $request->validate([
            'feature' => 'required|string',
        ]);
        
        $subscription = DB::table('user_subscriptions')
            ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
            ->where('user_subscriptions.user_id', auth()->id())
            ->where('user_subscriptions.status', 'active')
            ->select('subscription_plans.features', 'subscription_plans.limits')
            ->first();
        
        if (!$subscription) {
            // Free plan limits
            $limits = [
                'api_calls_per_day' => 100,
                'storage_mb' => 100,
                'users' => 1,
            ];
            $features = ['basic_shipping'];
        } else {
            $features = json_decode($subscription->features ?? '[]', true);
            $limits = json_decode($subscription->limits ?? '{}', true);
        }
        
        $hasAccess = in_array($validated['feature'], $features);
        
        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
            'limits' => $limits
        ]);
    }
}
