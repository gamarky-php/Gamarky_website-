<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Ads Manager API Controller
 * 
 * Features:
 * - CRUD operations for ads
 * - Placements management (header, sidebar, footer, popup)
 * - Analytics (impressions, clicks, CTR)
 * - Active/Inactive status
 * - Scheduling (start_date, end_date)
 */
class AdsController extends Controller
{
    /**
     * Get all ads with analytics
     * 
     * GET /api/ads
     */
    public function index(Request $request)
    {
        $query = DB::table('ads')
            ->select([
                'ads.*',
                DB::raw('COALESCE(ad_analytics.impressions, 0) as impressions'),
                DB::raw('COALESCE(ad_analytics.clicks, 0) as clicks'),
                DB::raw('CASE WHEN ad_analytics.impressions > 0 THEN (ad_analytics.clicks / ad_analytics.impressions * 100) ELSE 0 END as ctr')
            ])
            ->leftJoin('ad_analytics', 'ads.id', '=', 'ad_analytics.ad_id')
            ->orderBy('created_at', 'desc');
        
        // Filter by status
        if ($request->has('status')) {
            $query->where('ads.status', $request->status);
        }
        
        // Filter by placement
        if ($request->has('placement')) {
            $query->where('ads.placement', $request->placement);
        }
        
        // Search
        if ($request->has('search')) {
            $query->where('ads.title', 'like', '%' . $request->search . '%');
        }
        
        $ads = $query->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $ads->items(),
            'pagination' => [
                'total' => $ads->total(),
                'per_page' => $ads->perPage(),
                'current_page' => $ads->currentPage(),
                'last_page' => $ads->lastPage(),
            ]
        ]);
    }

    /**
     * Get single ad
     * 
     * GET /api/ads/{id}
     */
    public function show(int $id)
    {
        $ad = DB::table('ads')
            ->select([
                'ads.*',
                DB::raw('COALESCE(ad_analytics.impressions, 0) as impressions'),
                DB::raw('COALESCE(ad_analytics.clicks, 0) as clicks'),
                DB::raw('CASE WHEN ad_analytics.impressions > 0 THEN (ad_analytics.clicks / ad_analytics.impressions * 100) ELSE 0 END as ctr')
            ])
            ->leftJoin('ad_analytics', 'ads.id', '=', 'ad_analytics.ad_id')
            ->where('ads.id', $id)
            ->first();
        
        if (!$ad) {
            return response()->json([
                'success' => false,
                'error' => 'Ad not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $ad
        ]);
    }

    /**
     * Create new ad
     * 
     * POST /api/ads
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,html,video',
            'placement' => 'required|in:header,sidebar,footer,popup,inline',
            'content' => 'nullable|string',
            'image_url' => 'nullable|url',
            'link_url' => 'nullable|url',
            'target_url' => 'nullable|url',
            'status' => 'required|in:active,inactive,scheduled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'priority' => 'nullable|integer|min:0|max:100',
            'max_impressions' => 'nullable|integer|min:0',
            'max_clicks' => 'nullable|integer|min:0',
        ]);
        
        $adId = DB::table('ads')->insertGetId([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'placement' => $validated['placement'],
            'content' => $validated['content'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'target_url' => $validated['target_url'] ?? null,
            'status' => $validated['status'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'priority' => $validated['priority'] ?? 50,
            'max_impressions' => $validated['max_impressions'] ?? null,
            'max_clicks' => $validated['max_clicks'] ?? null,
            'created_by' => auth()->id(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Initialize analytics
        DB::table('ad_analytics')->insert([
            'ad_id' => $adId,
            'impressions' => 0,
            'clicks' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ad created successfully',
            'data' => ['id' => $adId]
        ], 201);
    }

    /**
     * Update ad
     * 
     * PUT /api/ads/{id}
     */
    public function update(Request $request, int $id)
    {
        $ad = DB::table('ads')->where('id', $id)->first();
        
        if (!$ad) {
            return response()->json([
                'success' => false,
                'error' => 'Ad not found'
            ], 404);
        }
        
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:image,html,video',
            'placement' => 'sometimes|in:header,sidebar,footer,popup,inline',
            'content' => 'nullable|string',
            'image_url' => 'nullable|url',
            'link_url' => 'nullable|url',
            'target_url' => 'nullable|url',
            'status' => 'sometimes|in:active,inactive,scheduled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'priority' => 'nullable|integer|min:0|max:100',
            'max_impressions' => 'nullable|integer|min:0',
            'max_clicks' => 'nullable|integer|min:0',
        ]);
        
        $validated['updated_at'] = Carbon::now();
        
        DB::table('ads')->where('id', $id)->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Ad updated successfully'
        ]);
    }

    /**
     * Delete ad
     * 
     * DELETE /api/ads/{id}
     */
    public function destroy(int $id)
    {
        $deleted = DB::table('ads')->where('id', $id)->delete();
        
        if (!$deleted) {
            return response()->json([
                'success' => false,
                'error' => 'Ad not found'
            ], 404);
        }
        
        // Delete analytics
        DB::table('ad_analytics')->where('ad_id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully'
        ]);
    }

    /**
     * Record impression
     * 
     * POST /api/ads/{id}/impression
     */
    public function recordImpression(int $id)
    {
        $ad = DB::table('ads')->where('id', $id)->where('status', 'active')->first();
        
        if (!$ad) {
            return response()->json([
                'success' => false,
                'error' => 'Ad not found or inactive'
            ], 404);
        }
        
        // Check max impressions
        $analytics = DB::table('ad_analytics')->where('ad_id', $id)->first();
        if ($ad->max_impressions && $analytics && $analytics->impressions >= $ad->max_impressions) {
            return response()->json([
                'success' => false,
                'error' => 'Max impressions reached'
            ], 400);
        }
        
        DB::table('ad_analytics')
            ->where('ad_id', $id)
            ->increment('impressions');
        
        DB::table('ad_analytics')
            ->where('ad_id', $id)
            ->update(['updated_at' => Carbon::now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Impression recorded'
        ]);
    }

    /**
     * Record click
     * 
     * POST /api/ads/{id}/click
     */
    public function recordClick(int $id)
    {
        $ad = DB::table('ads')->where('id', $id)->where('status', 'active')->first();
        
        if (!$ad) {
            return response()->json([
                'success' => false,
                'error' => 'Ad not found or inactive'
            ], 404);
        }
        
        // Check max clicks
        $analytics = DB::table('ad_analytics')->where('ad_id', $id)->first();
        if ($ad->max_clicks && $analytics && $analytics->clicks >= $ad->max_clicks) {
            return response()->json([
                'success' => false,
                'error' => 'Max clicks reached'
            ], 400);
        }
        
        DB::table('ad_analytics')
            ->where('ad_id', $id)
            ->increment('clicks');
        
        DB::table('ad_analytics')
            ->where('ad_id', $id)
            ->update(['updated_at' => Carbon::now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Click recorded',
            'redirect_url' => $ad->target_url
        ]);
    }

    /**
     * Get analytics report
     * 
     * GET /api/ads/analytics
     */
    public function analytics(Request $request)
    {
        $query = DB::table('ad_analytics')
            ->join('ads', 'ad_analytics.ad_id', '=', 'ads.id')
            ->select([
                'ads.id',
                'ads.title',
                'ads.placement',
                'ad_analytics.impressions',
                'ad_analytics.clicks',
                DB::raw('CASE WHEN ad_analytics.impressions > 0 THEN (ad_analytics.clicks / ad_analytics.impressions * 100) ELSE 0 END as ctr')
            ]);
        
        // Filter by date range
        if ($request->has('start_date')) {
            $query->where('ad_analytics.updated_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('ad_analytics.updated_at', '<=', $request->end_date);
        }
        
        // Filter by placement
        if ($request->has('placement')) {
            $query->where('ads.placement', $request->placement);
        }
        
        $analytics = $query->get();
        
        // Calculate totals
        $totals = [
            'total_impressions' => $analytics->sum('impressions'),
            'total_clicks' => $analytics->sum('clicks'),
            'average_ctr' => $analytics->count() > 0 
                ? $analytics->avg('ctr') 
                : 0,
        ];
        
        // Group by placement
        $byPlacement = $analytics->groupBy('placement')->map(function($items, $placement) {
            return [
                'placement' => $placement,
                'impressions' => $items->sum('impressions'),
                'clicks' => $items->sum('clicks'),
                'ctr' => $items->sum('impressions') > 0 
                    ? ($items->sum('clicks') / $items->sum('impressions') * 100) 
                    : 0,
            ];
        })->values();
        
        return response()->json([
            'success' => true,
            'totals' => $totals,
            'by_placement' => $byPlacement,
            'ads' => $analytics
        ]);
    }

    /**
     * Get active ads by placement (for frontend display)
     * 
     * GET /api/ads/active/{placement}
     */
    public function getActiveByPlacement(string $placement)
    {
        $now = Carbon::now();
        
        $ads = DB::table('ads')
            ->where('placement', $placement)
            ->where('status', 'active')
            ->where(function($query) use ($now) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', $now);
            })
            ->where(function($query) use ($now) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $now);
            })
            ->orderBy('priority', 'desc')
            ->get();
        
        // Filter by max impressions/clicks
        $activeAds = [];
        foreach ($ads as $ad) {
            $analytics = DB::table('ad_analytics')->where('ad_id', $ad->id)->first();
            
            if ($ad->max_impressions && $analytics && $analytics->impressions >= $ad->max_impressions) {
                continue;
            }
            if ($ad->max_clicks && $analytics && $analytics->clicks >= $ad->max_clicks) {
                continue;
            }
            
            $activeAds[] = $ad;
        }
        
        return response()->json([
            'success' => true,
            'data' => $activeAds
        ]);
    }
}
