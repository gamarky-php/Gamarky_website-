<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdsController extends Controller
{
    /**
     * Query active ads within time range with approved supplier and filtered by specialty
     * 
     * @param string|null $specialty
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function queryAds($specialty = null)
    {
        return Ad::with('supplier')
            ->where('is_active', true)
            ->where(function ($query) {
                $now = now();
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) {
                $now = now();
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->whereHas('supplier', function ($query) use ($specialty) {
                $query->where('status', 'approved'); // Using status field instead of approved
                if ($specialty) {
                    $query->where('specialty', $specialty);
                }
            });
    }

    /**
     * Return JSON list of ads for API consumers
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $specialty = $request->query('specialty') ?: (auth()->check() ? auth()->user()->specialty : null);
        
        $cacheKey = 'ads_api_' . ($specialty ?: 'all');
        
        $ads = Cache::remember($cacheKey, 60, function () use ($specialty) {
            return $this->queryAds($specialty)
                ->orderByDesc('priority')
                ->inRandomOrder()
                ->take(3)
                ->get()
                ->map(function ($ad) {
                    return [
                        'id' => $ad->id,
                        'title' => $ad->title,
                        'image_url' => $ad->image_path ? asset('storage/' . $ad->image_path) : null,
                        'link_url' => $ad->link_url,
                        'supplier' => $ad->supplier ? [
                            'id' => $ad->supplier->id,
                            'name' => $ad->supplier->name,
                            'specialty' => $ad->supplier->specialty
                        ] : null
                    ];
                });
        });

        return response()->json($ads);
    }

    /**
     * Render widget partial and increment impressions for shown ads
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function widget(Request $request)
    {
        $specialty = $request->query('specialty') ?: (auth()->check() ? auth()->user()->specialty : null);
        
        $cacheKey = 'ads_widget_' . ($specialty ?: 'all');
        
        // Get ads with caching
        $ads = Cache::remember($cacheKey, 60, function () use ($specialty) {
            return $this->queryAds($specialty)
                ->orderByDesc('priority')
                ->inRandomOrder()
                ->take(3)
                ->get();
        });

        // Increment impressions for each ad (outside cache)
        foreach ($ads as $ad) {
            $ad->increment('impressions');
        }

        return view('components.ads.widget', compact('ads'));
    }

    /**
     * Track a click and redirect to ad target
     * 
     * @param \App\Models\Ad $ad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function click(Ad $ad)
    {
        // Increment clicks
        $ad->increment('clicks');

        // Redirect to ad link or home page
        return redirect()->away($ad->link_url ?? url('/'));
    }
}

