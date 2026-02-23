<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar_url ?? null,

            'country' => $this->whenLoaded('country', function () {
                return [
                    'id' => $this->country->id,
                    'name' => $this->country->name,
                    'code' => $this->country->code,
                    'flag' => $this->country->flag_url ?? null,
                ];
            }),

            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),

            'permissions' => $this->when($this->relationLoaded('roles'), function () {
                return $this->getAllPermissions()->pluck('name');
            }),

            'subscription' => $this->whenLoaded('subscription', function () {
                return [
                    'plan' => $this->subscription->plan_name ?? null,
                    'status' => $this->subscription->status ?? 'free',
                    'expires_at' => $this->subscription->expires_at ?? null,
                    'is_active' => method_exists($this->subscription, 'isActive')
                        ? (bool) $this->subscription->isActive()
                        : false,
                ];
            }),

            'preferences' => [
                'language' => $this->preferred_language ?? 'ar',
                'notifications' => [
                    'email' => $this->email_notifications ?? true,
                    'sms' => $this->sms_notifications ?? false,
                    'push' => $this->push_notifications ?? true,
                ],
                'theme' => $this->theme_preference ?? 'light',
            ],

            'stats' => [
                'calculations_count' => $this->cost_calculations_count ?? 0,
                'articles_read' => $this->articles_read_count ?? 0,
                'last_activity' => $this->last_seen_at?->toISOString(),
            ],

            'verification' => [
                'email_verified' => !is_null($this->email_verified_at),
                'phone_verified' => !is_null($this->phone_verified_at),
                'profile_completed' => $this->profileCompletedSafe(),
            ],

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Safe profile completion flag.
     * - If User model has isProfileCompleted() => use it
     * - else fallback to common fields if they exist
     */
    protected function profileCompletedSafe(): bool
    {
        // Prefer model method if present
        if (method_exists($this->resource, 'isProfileCompleted')) {
            try {
                return (bool) $this->resource->isProfileCompleted();
            } catch (\Throwable $e) {
                // fall through to fallback checks
            }
        }

        // Fallback heuristics (do NOT throw)
        $hasName = !empty($this->resource->name);
        $hasEmail = !empty($this->resource->email);

        // phone might be optional in some flows
        $hasPhone = !empty($this->resource->phone);

        // if you have a dedicated boolean column, honor it if present
        if (isset($this->resource->profile_completed)) {
            return (bool) $this->resource->profile_completed;
        }

        // Minimal safe default: require name + email (and phone if you want)
        // Keep it lenient to avoid breaking login.
        return $hasName && $hasEmail;
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'version' => 'v1',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
}