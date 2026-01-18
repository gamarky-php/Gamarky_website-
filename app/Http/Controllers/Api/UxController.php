<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UxHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UxController extends Controller
{
    /**
     * Get all UX copy for current locale
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        
        return response()->json([
            'locale' => $locale,
            'ux' => [
                'cta' => __('ux.cta', [], $locale),
                'empty' => __('ux.empty', [], $locale),
                'errors' => __('ux.errors', [], $locale),
                'success' => __('ux.success', [], $locale),
                'booking' => __('ux.booking', [], $locale),
                'customs' => __('ux.customs', [], $locale),
                'shipment' => __('ux.shipment', [], $locale),
                'form' => __('ux.form', [], $locale),
                'confirm' => __('ux.confirm', [], $locale),
                'time' => __('ux.time', [], $locale),
                'notifications' => __('ux.notifications', [], $locale),
                'hints' => __('ux.hints', [], $locale),
                'pagination' => __('ux.pagination', [], $locale),
            ],
        ]);
    }

    /**
     * Get specific UX copy category
     * 
     * @param Request $request
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function category(Request $request, string $category)
    {
        $locale = app()->getLocale();
        $validCategories = ['cta', 'empty', 'errors', 'success', 'booking', 'customs', 'shipment', 'form', 'confirm', 'time', 'notifications', 'hints', 'pagination'];
        
        if (!in_array($category, $validCategories)) {
            return UxHelper::errorResponse('not_found', [], 404);
        }
        
        return response()->json([
            'locale' => $locale,
            'category' => $category,
            'data' => __("ux.{$category}", [], $locale),
        ]);
    }

    /**
     * Switch locale
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchLocale(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:ar,en',
        ]);
        
        $locale = $request->input('locale');
        
        // Set locale
        app()->setLocale($locale);
        session(['locale' => $locale]);
        
        // Update user preference if authenticated
        if ($request->user()) {
            $request->user()->update([
                'preferred_locale' => $locale,
            ]);
        }
        
        return response()->json([
            'message' => UxHelper::success('saved'),
            'locale' => $locale,
        ]);
    }

    /**
     * Test booking confirmation message
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testBookingConfirmed(Request $request)
    {
        $ref = 'GK' . strtoupper(uniqid());
        
        return UxHelper::bookingConfirmed($ref, [
            'booking_number' => $ref,
            'status' => 'confirmed',
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Test customs appointment message
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testCustomsAppointment(Request $request)
    {
        $date = now()->addDays(3)->format('Y-m-d');
        $time = '10:00';
        
        return UxHelper::customsAppointment($date, $time, [
            'location' => 'Jeddah Port',
            'inspector' => 'Inspector #1234',
        ]);
    }

    /**
     * Test empty state message
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testEmptyState(Request $request)
    {
        $type = $request->get('type', 'no_results');
        
        return UxHelper::emptyStateResponse($type);
    }

    /**
     * Test error message
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testError(Request $request)
    {
        $errorType = $request->get('type', 'generic');
        
        return UxHelper::errorResponse($errorType, [], 500);
    }

    /**
     * Test time ago formatting
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testTimeAgo(Request $request)
    {
        $times = [
            'just_now' => now(),
            '5_minutes' => now()->subMinutes(5),
            '2_hours' => now()->subHours(2),
            '3_days' => now()->subDays(3),
            '2_weeks' => now()->subWeeks(2),
            '1_month' => now()->subMonth(),
            '1_year' => now()->subYear(),
        ];
        
        $results = [];
        foreach ($times as $key => $time) {
            $results[$key] = [
                'time' => $time->toISOString(),
                'formatted' => UxHelper::timeAgo($time),
            ];
        }
        
        return response()->json([
            'locale' => app()->getLocale(),
            'times' => $results,
        ]);
    }
}
