<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Analytics API Controller
 * 
 * Provides REST API endpoints for all analytics data
 * All responses return Chart.js-ready format
 * 
 * Base URL: /api/analytics
 */
class AnalyticsController extends Controller
{
    protected $analytics;

    public function __construct(AnalyticsService $analytics)
    {
        $this->analytics = $analytics;
        $this->middleware('auth:sanctum')->except(['index']);
    }

    /**
     * GET /api/analytics
     * List available analytics endpoints
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Analytics API v1.0',
            'endpoints' => [
                'GET /api/analytics/funnel' => 'Conversion funnel data',
                'GET /api/analytics/clearance' => 'Clearance time by port',
                'GET /api/analytics/errors' => 'Document error rates',
                'GET /api/analytics/sla' => 'Broker SLA compliance',
                'GET /api/analytics/shipping' => 'Shipping performance',
                'GET /api/analytics/ads' => 'Ads performance metrics',
                'GET /api/analytics/satisfaction' => 'Customer satisfaction scores',
                'GET /api/analytics/kpis' => 'All KPIs summary',
                'GET /api/analytics/export/{type}' => 'Export data as JSON',
            ],
            'documentation' => url('/docs/analytics-api'),
        ]);
    }

    /**
     * GET /api/analytics/funnel
     * 
     * Parameters:
     * - period: daily|weekly|monthly (default: daily)
     * - units: number of time units (default: 30)
     * - section: container|truck|import|export (default: container)
     */
    public function funnel(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'period' => 'string|in:daily,weekly,monthly',
            'units' => 'integer|min:1|max:365',
            'section' => 'string|in:container,truck,import,export',
        ]);

        $period = $validated['period'] ?? 'daily';
        $units = $validated['units'] ?? 30;
        $section = $validated['section'] ?? 'container';

        $data = $this->analytics->getFunnelData($period, $units, $section);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('period', 'units', 'section'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/clearance
     * 
     * Parameters:
     * - limit: number of ports to return (default: 10)
     */
    public function clearance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'integer|min:1|max:50',
        ]);

        $limit = $validated['limit'] ?? 10;
        $data = $this->analytics->getClearanceTimeByPort($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('limit'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/errors
     * 
     * Parameters:
     * - groupBy: port|broker (default: port)
     */
    public function errors(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'groupBy' => 'string|in:port,broker',
        ]);

        $groupBy = $validated['groupBy'] ?? 'port';
        $data = $this->analytics->getDocumentErrorRate($groupBy);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('groupBy'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/sla
     * 
     * Parameters:
     * - limit: number of brokers to return (default: 10)
     */
    public function sla(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'limit' => 'integer|min:1|max:50',
        ]);

        $limit = $validated['limit'] ?? 10;
        $data = $this->analytics->getBrokerSLACompliance($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('limit'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/shipping
     * 
     * Parameters:
     * - type: container|truck (default: container)
     */
    public function shipping(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'string|in:container,truck',
        ]);

        $type = $validated['type'] ?? 'container';
        $data = $this->analytics->getShippingPerformance($type);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('type'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/ads
     * 
     * Parameters:
     * - days: time window in days (default: 30)
     */
    public function ads(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'days' => 'integer|min:1|max:365',
        ]);

        $days = $validated['days'] ?? 30;
        $data = $this->analytics->getAdsPerformance($days);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('days'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/satisfaction
     * 
     * Parameters:
     * - days: time window in days (default: 30)
     */
    public function satisfaction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'days' => 'integer|min:1|max:365',
        ]);

        $days = $validated['days'] ?? 30;
        $data = $this->analytics->getCustomerSatisfaction($days);

        return response()->json([
            'success' => true,
            'data' => $data,
            'params' => compact('days'),
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/kpis
     * Get all KPIs in one call
     */
    public function kpis(): JsonResponse
    {
        $clearance = $this->analytics->getClearanceTimeByPort(1);
        $sla = $this->analytics->getBrokerSLACompliance(1);
        $shipping = $this->analytics->getShippingPerformance('container');
        $satisfaction = $this->analytics->getCustomerSatisfaction(30);

        return response()->json([
            'success' => true,
            'kpis' => [
                'clearance' => [
                    'label' => 'متوسط زمن التخليص',
                    'value' => $clearance['overall_avg'] ?? 0,
                    'unit' => 'يوم',
                ],
                'sla' => [
                    'label' => 'الالتزام بـ SLA',
                    'value' => $sla['overall_compliance'] ?? 0,
                    'unit' => '%',
                ],
                'shipping' => [
                    'label' => 'التسليم في الموعد',
                    'value' => $shipping['overall_on_time'] ?? 0,
                    'unit' => '%',
                ],
                'satisfaction' => [
                    'label' => 'رضا العملاء',
                    'value' => $satisfaction['summary']['avg_csat'] ?? 0,
                    'unit' => '/10',
                    'nps' => $satisfaction['summary']['overall_nps'] ?? 0,
                ],
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * GET /api/analytics/export/{type}
     * 
     * Export analytics data for specific type
     * Types: funnel, clearance, errors, sla, shipping, ads, satisfaction
     */
    public function export(Request $request, string $type): JsonResponse
    {
        $data = match ($type) {
            'funnel' => $this->analytics->getFunnelData('daily', 30, 'container'),
            'clearance' => $this->analytics->getClearanceTimeByPort(20),
            'errors' => $this->analytics->getDocumentErrorRate('port'),
            'sla' => $this->analytics->getBrokerSLACompliance(20),
            'shipping' => $this->analytics->getShippingPerformance('container'),
            'ads' => $this->analytics->getAdsPerformance(30),
            'satisfaction' => $this->analytics->getCustomerSatisfaction(30),
            default => null,
        };

        if (!$data) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid analytics type',
                'valid_types' => ['funnel', 'clearance', 'errors', 'sla', 'shipping', 'ads', 'satisfaction'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'type' => $type,
            'data' => $data,
            'exported_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * POST /api/analytics/compare
     * 
     * Compare two time periods
     */
    public function compare(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:funnel,clearance,shipping,satisfaction',
            'period1' => 'required|array',
            'period2' => 'required|array',
        ]);

        // This would be extended based on analytics type
        // For now, return structure example
        return response()->json([
            'success' => true,
            'comparison' => [
                'type' => $validated['type'],
                'period1' => ['data' => []],
                'period2' => ['data' => []],
                'change' => [
                    'percentage' => 0,
                    'direction' => 'neutral',
                ],
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
