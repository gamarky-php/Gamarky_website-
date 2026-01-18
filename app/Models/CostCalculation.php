<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CostCalculation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'module',
        'user_id',
        'ref_code',
        'title',
        'inputs',
        'items',
        'totals',
        'currency',
        'margin_percent',
        'final_total',
        'saved_as',
        'metadata',
        'status',
        'sent_at',
        'accepted_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'inputs' => 'array',
        'items' => 'array',
        'totals' => 'array',
        'metadata' => 'array',
        'margin_percent' => 'decimal:2',
        'final_total' => 'decimal:2',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($calculation) {
            if (empty($calculation->ref_code)) {
                $calculation->ref_code = static::generateRefCode($calculation->module);
            }
        });
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique reference code
     */
    public static function generateRefCode(string $module): string
    {
        $prefix = strtoupper(substr($module, 0, 3));
        $timestamp = now()->format('ymd');
        $random = strtoupper(Str::random(6));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Calculate grand total from items
     */
    public function calculateGrandTotal(): float
    {
        if (!$this->items) {
            return 0.0;
        }

        $subtotal = array_sum(array_column($this->items, 'total'));
        
        // Apply margin
        $margin = $subtotal * ($this->margin_percent / 100);
        
        // Get taxes and fees from totals
        $taxes = $this->totals['taxes'] ?? 0;
        $fees = $this->totals['fees'] ?? 0;
        
        return $subtotal + $margin + $taxes + $fees;
    }

    /**
     * Get subtotal
     */
    public function getSubtotalAttribute(): float
    {
        return $this->totals['subtotal'] ?? 0.0;
    }

    /**
     * Get taxes
     */
    public function getTaxesAttribute(): float
    {
        return $this->totals['taxes'] ?? 0.0;
    }

    /**
     * Get fees
     */
    public function getFeesAttribute(): float
    {
        return $this->totals['fees'] ?? 0.0;
    }

    /**
     * Check if calculation is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if calculation is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Mark as sent
     */
    public function markAsSent(): void
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }

    /**
     * Mark as accepted
     */
    public function markAsAccepted(): void
    {
        $this->status = 'accepted';
        $this->accepted_at = now();
        $this->save();
    }

    /**
     * Scope by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope quotes
     */
    public function scopeQuotes($query)
    {
        return $query->where('saved_as', 'quote');
    }

    /**
     * Scope invoices
     */
    public function scopeInvoices($query)
    {
        return $query->where('saved_as', 'invoice');
    }

    /**
     * Scope scenarios
     */
    public function scopeScenarios($query)
    {
        return $query->where('saved_as', 'scenario');
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope sent calculations
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope accepted calculations
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }
}
