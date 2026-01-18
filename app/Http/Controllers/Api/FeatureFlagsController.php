<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeatureFlag;
use App\Services\FeatureFlagService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeatureFlagsController extends Controller
{
    protected $featureService;

    public function __construct(FeatureFlagService $featureService)
    {
        $this->featureService = $featureService;
    }

    /**
     * Get all feature flags (admin only)
     * GET /api/feature-flags
     */
    public function index(): JsonResponse
    {
        $features = FeatureFlag::orderBy('name')->get()->map(function ($flag) {
            return [
                'id' => $flag->id,
                'key' => $flag->key,
                'name' => $flag->name,
                'description' => $flag->description,
                'is_active' => $flag->is_active,
                'has_rules' => !empty($flag->rules),
                'activated_at' => $flag->activated_at?->toDateTimeString(),
                'deactivated_at' => $flag->deactivated_at?->toDateTimeString(),
            ];
        });

        return response()->json($features);
    }

    /**
     * Get enabled features for current user
     * GET /api/feature-flags/my-features
     */
    public function myFeatures(): JsonResponse
    {
        $userId = auth()->id();
        $allFlags = FeatureFlag::all();
        
        $enabledFeatures = $allFlags->filter(function ($flag) use ($userId) {
            return FeatureFlag::isEnabled($flag->key, $userId);
        })->pluck('key');

        return response()->json([
            'enabled_features' => $enabledFeatures,
        ]);
    }

    /**
     * Check if specific feature is enabled
     * GET /api/feature-flags/check/{key}
     */
    public function check(string $key): JsonResponse
    {
        $isEnabled = $this->featureService->isEnabled($key);

        return response()->json([
            'key' => $key,
            'enabled' => $isEnabled,
        ]);
    }

    /**
     * Toggle feature flag (admin only)
     * POST /api/feature-flags/{id}/toggle
     */
    public function toggle(int $id): JsonResponse
    {
        $flag = FeatureFlag::findOrFail($id);

        if ($flag->is_active) {
            $flag->disable();
            $message = "Feature '{$flag->name}' disabled";
        } else {
            $flag->enable();
            $message = "Feature '{$flag->name}' enabled";
        }

        return response()->json([
            'message' => $message,
            'feature' => [
                'key' => $flag->key,
                'is_active' => $flag->is_active,
            ],
        ]);
    }

    /**
     * Update feature flag rules (admin only)
     * PUT /api/feature-flags/{id}/rules
     */
    public function updateRules(Request $request, int $id): JsonResponse
    {
        $flag = FeatureFlag::findOrFail($id);

        $validated = $request->validate([
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
            'roles' => 'nullable|array',
            'roles.*' => 'string',
            'percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $flag->update(['rules' => $validated]);

        return response()->json([
            'message' => 'Feature rules updated',
            'rules' => $flag->rules,
        ]);
    }

    /**
     * Gradual rollout (admin only)
     * POST /api/feature-flags/{id}/rollout
     */
    public function rollout(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        $flag = FeatureFlag::findOrFail($id);
        $this->featureService->rolloutPercentage($flag->key, $validated['percentage']);

        return response()->json([
            'message' => "Feature rolled out to {$validated['percentage']}% of users",
            'feature' => $flag->fresh(),
        ]);
    }

    /**
     * Clear feature flag cache (admin only)
     * POST /api/feature-flags/clear-cache
     */
    public function clearCache(Request $request): JsonResponse
    {
        $key = $request->input('key');
        $this->featureService->clearCache($key);

        return response()->json([
            'message' => $key ? "Cache cleared for feature: {$key}" : 'All feature cache cleared',
        ]);
    }
}
