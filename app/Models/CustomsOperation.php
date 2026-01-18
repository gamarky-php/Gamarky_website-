<?php

namespace App\Models;

/**
 * Customs Operation Model
 * 
 * Purpose: إدارة عمليات التخليص الجمركي
 * Relations: User, Broker (User)
 * Policies: CustomsOperationPolicy
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomsOperation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'broker_id',
        'operation_type',
        'status',
        'declaration_number',
        'bill_of_lading',
        'declaration_date',
        'hs_code',
        'product_description',
        'declared_value',
        'customs_value',
        'customs_duty',
        'vat',
        'other_fees',
        'total_fees',
        'required_documents',
        'uploaded_documents',
        'broker_notes',
        'customs_notes',
        'submission_date',
        'approval_date',
        'clearance_date',
    ];

    protected $casts = [
        'declared_value' => 'decimal:2',
        'customs_value' => 'decimal:2',
        'customs_duty' => 'decimal:2',
        'vat' => 'decimal:2',
        'other_fees' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'declaration_date' => 'date',
        'submission_date' => 'date',
        'approval_date' => 'date',
        'clearance_date' => 'date',
        'required_documents' => 'array',
        'uploaded_documents' => 'array',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'broker_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Methods
    public function calculateTotalFees(): void
    {
        $this->total_fees = ($this->customs_duty ?? 0)
                          + ($this->vat ?? 0)
                          + ($this->other_fees ?? 0);
        $this->save();
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approval_date' => now(),
        ]);
    }

    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'customs_notes' => $reason ?? $this->customs_notes,
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'قيد الانتظار',
            'under_review' => 'قيد المراجعة',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
            default => 'غير معروف',
        };
    }
}
