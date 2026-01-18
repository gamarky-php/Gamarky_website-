<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;

    protected $guard_name = 'web'; // مهم لو عندك أكثر من guard

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasApiTokens;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'google_id',
        'apple_id',
        'email_verified_at',
        'country',
        'phone',
        'phone_verified_at',
        'phone_otp',
        'phone_otp_expires_at',
        'activity_type',
        'business_sector',
        'newsletter',
        'is_admin',
        'role_id',
        'status',
        'locale',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'phone_otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'newsletter' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the role of the user
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get user subscriptions
     */
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active subscription
     */
    public function activeSubscription(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'active')
            ->where('end_at', '>', now());
    }

    /**
     * Get user media
     */
    public function media(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Media::class, 'owner_id');
    }

    /**
     * Get user articles
     */
    public function articles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Get clearance jobs as client
     */
    public function clearanceJobs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ClearanceJob::class, 'client_id');
    }

    /**
     * Get container quotes
     */
    public function containerQuotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContainerQuote::class, 'requester_id');
    }

    /**
     * Get container bookings
     */
    public function containerBookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContainerBooking::class);
    }

    /**
     * Get truck quotes
     */
    public function truckQuotes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TruckQuote::class, 'requester_id');
    }

    /**
     * Get truck bookings
     */
    public function truckBookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TruckBooking::class);
    }

    /**
     * Get cost calculations
     */
    public function costCalculations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CostCalculation::class);
    }

    /**
     * Get broker reviews by this user
     */
    public function brokerReviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BrokerReview::class, 'reviewer_id');
    }

    /**
     * Check if user has active subscription
     */
    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Check if user subscription has feature
     */
    public function hasFeature(string $feature): bool
    {
        $subscription = $this->activeSubscription;
        
        if (!$subscription) {
            return false;
        }

        return $subscription->hasFeature($feature);
    }
}

