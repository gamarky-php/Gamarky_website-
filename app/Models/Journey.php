<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Journey Model - Pay-per-Journey System
 * 
 * جماركي - رحلة العميل (Journey/Operation)
 * Represents a single operation that customer pays for
 * Innovation: Payment per journey completion, not time-based subscription
 */
class Journey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'operation_code',
        'guest_id',
        'status',
        'currency',
        'service_total',
        'platform_total',
        'grand_total',
        'notify_via',
        'contact_email',
        'contact_phone',
        'journey_type',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'service_total' => 'decimal:2',
        'platform_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    protected $attributes = [
        'currency' => 'EGP',
        'status' => 'draft',
        'notify_via' => 'email',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(JourneyItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function entitlement(): HasOne
    {
        return $this->hasOne(JourneyEntitlement::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGuest($query, $guestId)
    {
        return $query->where('guest_id', $guestId);
    }

    // ========================================
    // STATUS CHECKS
    // ========================================

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPendingPayment(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['active', 'completed']);
    }

    // ========================================
    // PAYMENT METHODS
    // ========================================

    public function getSuccessfulPayment()
    {
        return $this->payments()->where('status', 'paid')->first();
    }

    public function hasSuccessfulPayment(): bool
    {
        return $this->payments()->where('status', 'paid')->exists();
    }

    public function getLatestPayment()
    {
        return $this->payments()->latest()->first();
    }

    // ========================================
    // TOTALS CALCULATION
    // ========================================

    public function calculateTotals(): void
    {
        $paidItems = $this->items()->whereIn('status', ['selected', 'paid'])->get();
        
        $serviceTotal = $paidItems->where('is_free', false)->sum('provider_fee');
        $platformTotal = $paidItems->where('is_free', false)->sum('platform_fee');
        
        $this->service_total = $serviceTotal;
        $this->platform_total = $platformTotal;
        $this->grand_total = $serviceTotal + $platformTotal;
    }

    public function recalculateAndSave(): bool
    {
        $this->calculateTotals();
        return $this->save();
    }

    // ========================================
    // OPERATION CODE GENERATION
    // ========================================

    public static function generateOperationCode(): string
    {
        do {
            // Format: GMKY-YYYYMMDD-XXXXX
            $code = sprintf(
                'GMKY-%s-%s',
                date('Ymd'),
                strtoupper(Str::random(5))
            );
        } while (self::where('operation_code', $code)->exists());

        return $code;
    }

    public function ensureOperationCode(): void
    {
        if (empty($this->operation_code)) {
            $this->operation_code = self::generateOperationCode();
        }
    }

    // ========================================
    // STATUS TRANSITIONS
    // ========================================

    public function markAsPendingPayment(): bool
    {
        if (!$this->isDraft() && !$this->isPendingPayment()) {
            return false;
        }

        $this->ensureOperationCode();
        $this->status = 'pending_payment';
        return $this->save();
    }

    public function markAsActive(): bool
    {
        if (!$this->isPendingPayment() && !$this->isDraft()) {
            return false;
        }

        $this->ensureOperationCode();
        $this->status = 'active';
        
        // Activate entitlement
        $entitlement = $this->entitlement ?? new JourneyEntitlement();
        $entitlement->journey_id = $this->id;
        $entitlement->user_id = $this->user_id;
        $entitlement->active = true;
        $entitlement->activated_at = now();
        $entitlement->save();

        return $this->save();
    }

    public function markAsCompleted(): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $this->status = 'completed';
        
        // Deactivate entitlement
        if ($this->entitlement) {
            $this->entitlement->active = false;
            $this->entitlement->expires_at = now();
            $this->entitlement->save();
        }

        return $this->save();
    }

    public function markAsCancelled(): bool
    {
        $this->status = 'cancelled';
        
        // Deactivate entitlement
        if ($this->entitlement) {
            $this->entitlement->active = false;
            $this->entitlement->expires_at = now();
            $this->entitlement->save();
        }

        return $this->save();
    }

    // ========================================
    // CONTACT INFO
    // ========================================

    public function getNotificationEmail(): ?string
    {
        return $this->contact_email ?? $this->user?->email;
    }

    public function getNotificationPhone(): ?string
    {
        return $this->contact_phone ?? $this->user?->phone;
    }

    public function shouldNotifyViaEmail(): bool
    {
        return in_array($this->notify_via, ['email', 'both']);
    }

    public function shouldNotifyViaSms(): bool
    {
        return in_array($this->notify_via, ['sms', 'both']);
    }

    // ========================================
    // GUEST TO USER CONVERSION
    // ========================================

    public function attachToUser(User $user): bool
    {
        $this->user_id = $user->id;
        $this->guest_id = null;
        
        if ($this->entitlement) {
            $this->entitlement->user_id = $user->id;
            $this->entitlement->save();
        }

        return $this->save();
    }

    // ========================================
    // DISPLAY HELPERS
    // ========================================

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'pending_payment' => 'انتظار الدفع',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغى',
            'refunded' => 'مسترد',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'pending_payment' => 'yellow',
            'active' => 'green',
            'completed' => 'blue',
            'cancelled' => 'red',
            'refunded' => 'orange',
            default => 'gray',
        };
    }
}
