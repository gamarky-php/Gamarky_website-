<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Payment Model
 * 
 * Represents a payment transaction for a journey
 * Provider: Paymob (EGP only)
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'journey_id',
        'user_id',
        'provider',
        'provider_reference',
        'provider_payment_key',
        'amount_egp',
        'currency',
        'status',
        'method',
        'method_details',
        'raw_payload',
        'paid_at',
        'failed_at',
        'failure_reason',
        'idempotency_key',
        'webhook_verified',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'amount_egp' => 'decimal:2',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
        'webhook_verified' => 'boolean',
    ];

    protected $attributes = [
        'provider' => 'paymob',
        'currency' => 'EGP',
        'status' => 'initiated',
        'webhook_verified' => false,
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    public function journey(): BelongsTo
    {
        return $this->belongsTo(Journey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeInitiated($query)
    {
        return $query->where('status', 'initiated');
    }

    public function scopeForProvider($query, string $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeVerified($query)
    {
        return $query->where('webhook_verified', true);
    }

    // ========================================
    // STATUS CHECKS
    // ========================================

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isInitiated(): bool
    {
        return $this->status === 'initiated';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isVerified(): bool
    {
        return $this->webhook_verified === true;
    }

    // ========================================
    // STATUS TRANSITIONS
    // ========================================

    public function markAsPaid(array $additionalData = []): bool
    {
        $this->status = 'paid';
        $this->paid_at = now();
        
        if (isset($additionalData['method'])) {
            $this->method = $additionalData['method'];
        }
        
        if (isset($additionalData['method_details'])) {
            $this->method_details = $additionalData['method_details'];
        }
        
        if (isset($additionalData['raw_payload'])) {
            $this->raw_payload = $additionalData['raw_payload'];
        }

        return $this->save();
    }

    public function markAsFailed(string $reason = null): bool
    {
        $this->status = 'failed';
        $this->failed_at = now();
        $this->failure_reason = $reason;

        return $this->save();
    }

    public function markAsPending(): bool
    {
        $this->status = 'pending';
        return $this->save();
    }

    public function markAsRefunded(): bool
    {
        $this->status = 'refunded';
        return $this->save();
    }

    public function markAsCancelled(): bool
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    public function markAsVerified(): bool
    {
        $this->webhook_verified = true;
        return $this->save();
    }

    // ========================================
    // IDEMPOTENCY
    // ========================================

    public static function findByProviderReference(string $reference): ?self
    {
        return self::where('provider_reference', $reference)->first();
    }

    public static function existsByProviderReference(string $reference): bool
    {
        return self::where('provider_reference', $reference)->exists();
    }

    public function isProcessed(): bool
    {
        return in_array($this->status, ['paid', 'failed', 'cancelled', 'refunded']);
    }

    // ========================================
    // PAYMENT KEY METHODS
    // ========================================

    public function hasPaymentKey(): bool
    {
        return !empty($this->provider_payment_key);
    }

    public function getPaymentUrl(): ?string
    {
        if (!$this->hasPaymentKey()) {
            return null;
        }

        // Paymob iframe URL
        $iframeId = config('services.paymob.iframe_id');
        
        if ($iframeId) {
            return "https://accept.paymobsolutions.com/api/acceptance/iframes/{$iframeId}?payment_token={$this->provider_payment_key}";
        }

        // Fallback to redirect URL
        return "https://accept.paymob.com/api/acceptance/post_pay?payment_token={$this->provider_payment_key}";
    }

    // ========================================
    // DISPLAY HELPERS
    // ========================================

    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount_egp, 2) . ' ' . $this->currency;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'initiated' => 'تم الإنشاء',
            'pending' => 'قيد الانتظار',
            'paid' => 'مدفوع',
            'failed' => 'فشل',
            'refunded' => 'مسترد',
            'cancelled' => 'ملغى',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'initiated' => 'gray',
            'pending' => 'yellow',
            'paid' => 'green',
            'failed' => 'red',
            'refunded' => 'orange',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'card' => 'بطاقة ائتمان',
            'wallet' => 'محفظة إلكترونية',
            'kiosk' => 'كشك',
            'bank_transfer' => 'تحويل بنكي',
            default => $this->method ?? 'غير محدد',
        };
    }
}
