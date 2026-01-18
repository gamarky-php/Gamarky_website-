<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'operation',
        'completed_at',
        'points',
        'refs',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'points' => 'integer',
        'refs' => 'array',
    ];

    /**
     * العلاقات
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * الحصول على النقاط حسب نوع العملية
     */
    public static function getPointsForOperation(string $operation): int
    {
        return match ($operation) {
            'collect' => 1,              // تجميع
            'store' => 1,                // تخزين
            'load' => 1,                 // تحميل
            'docs' => 2,                 // مستندات
            'cargox' => 2,               // CargoX
            'send_to_importer' => 1,     // إرسال للمستورد
            default => 0,
        };
    }

    /**
     * Event: عند إنشاء عملية جديدة، نحسب النقاط تلقائيًا
     */
    protected static function booted()
    {
        static::creating(function ($operation) {
            if (!$operation->points) {
                $operation->points = self::getPointsForOperation($operation->operation);
            }
        });
    }

    /**
     * إكمال العملية
     */
    public function complete(): void
    {
        $this->update([
            'completed_at' => now(),
        ]);
    }

    /**
     * فحص إذا كانت مكتملة
     */
    public function isCompleted(): bool
    {
        return !is_null($this->completed_at);
    }

    /**
     * الحصول على اسم العملية بالعربية
     */
    public function getOperationNameAttribute(): string
    {
        return match ($this->operation) {
            'collect' => 'تجميع البضائع',
            'store' => 'تخزين',
            'load' => 'تحميل',
            'docs' => 'استكمال المستندات',
            'cargox' => 'رفع CargoX',
            'send_to_importer' => 'إرسال للمستورد',
            default => $this->operation,
        };
    }
}
