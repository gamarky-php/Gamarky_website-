<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandAgencyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'company_name',
        'country',
        'city',
        'sector',
        'experience_years',
        'current_channels',
        'expansion_plan',
        'licenses',
        'attachments',
        'phone',
        'whatsapp',
        'email',
        'website',
        'score_total',
        'decision',
        'decision_notes',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'current_channels' => 'array',
        'licenses' => 'array',
        'attachments' => 'array',
        'score_total' => 'integer',
    ];

    /**
     * حساب السكور الإجمالي (من 100)
     * 
     * المعايير:
     * - الوثائق الأساسية (20 نقطة)
     * - سنوات الخبرة (20 نقطة)
     * - القدرات التشغيلية (20 نقطة)
     * - الأداء المتوقع (20 نقطة)
     * - الجاهزية التقنية (10 نقاط)
     * - المرفقات (10 نقاط)
     */
    public function calculateScore(): int
    {
        $score = 0;

        // 1. الوثائق الأساسية (20 نقطة)
        $licenses = $this->licenses ?? [];
        $licenseScore = min(count($licenses) * 5, 20);
        $score += $licenseScore;

        // 2. سنوات الخبرة (20 نقطة)
        $experienceScore = match (true) {
            $this->experience_years >= 10 => 20,
            $this->experience_years >= 6 => 16,
            $this->experience_years >= 2 => 12,
            $this->experience_years >= 1 => 5,
            default => 0,
        };
        $score += $experienceScore;

        // 3. القدرات التشغيلية (20 نقطة)
        $channels = $this->current_channels ?? [];
        $channelsScore = min(count($channels) * 5, 15);
        $planScore = !empty($this->expansion_plan) && strlen($this->expansion_plan) > 50 ? 5 : 0;
        $score += $channelsScore + $planScore;

        // 4. الأداء المتوقع (20 نقطة) - بناءً على اكتمال البيانات
        $completenessScore = 0;
        if (!empty($this->company_name)) $completenessScore += 5;
        if (!empty($this->city)) $completenessScore += 5;
        if (!empty($this->expansion_plan)) $completenessScore += 5;
        if (!empty($this->website)) $completenessScore += 5;
        $score += $completenessScore;

        // 5. الجاهزية التقنية (10 نقاط)
        $techScore = 0;
        if (count($channels) > 0) $techScore += 5;
        if (!empty($this->website)) $techScore += 5;
        $score += $techScore;

        // 6. المرفقات (10 نقاط)
        $attachments = $this->attachments ?? [];
        $attachmentsScore = min(count($attachments) * 3, 10);
        $score += $attachmentsScore;

        return min($score, 100);
    }

    /**
     * تحديد القرار بناءً على السكور
     */
    public function determineDecision(): string
    {
        $score = $this->score_total;

        return match (true) {
            $score >= 85 => 'accepted',
            $score >= 70 => 'conditional',
            default => 'rejected',
        };
    }

    /**
     * الحصول على رسالة القرار
     */
    public function getDecisionMessageAttribute(): string
    {
        return match ($this->decision) {
            'accepted' => '🎉 تهانينا! تم قبول طلبك',
            'conditional' => '⚠️ قبول مشروط - يحتاج لمراجعة إضافية',
            'rejected' => '❌ عذرًا، لم يتم قبول الطلب',
            'pending' => '⏳ قيد المراجعة',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على لون الشارة حسب القرار
     */
    public function getDecisionColorAttribute(): string
    {
        return match ($this->decision) {
            'accepted' => 'green',
            'conditional' => 'yellow',
            'rejected' => 'red',
            'pending' => 'gray',
            default => 'gray',
        };
    }

    /**
     * الحصول على توصيات بناءً على السكور
     */
    public function getRecommendationsAttribute(): array
    {
        $recommendations = [];
        $score = $this->score_total;

        if ($score < 20) {
            $recommendations[] = 'يرجى إرفاق المزيد من الوثائق الرسمية (رخصة تجارية، شهادة ضريبية)';
        }

        if ($this->experience_years < 2) {
            $recommendations[] = 'يُنصح باكتساب المزيد من الخبرة في المجال قبل التقديم';
        }

        if (empty($this->current_channels) || count($this->current_channels) == 0) {
            $recommendations[] = 'يجب توضيح قنوات التوزيع الحالية (متاجر، منصات إلكترونية، إلخ)';
        }

        if (empty($this->expansion_plan) || strlen($this->expansion_plan) < 50) {
            $recommendations[] = 'يرجى تقديم خطة توسع واضحة ومفصلة';
        }

        if (empty($this->website)) {
            $recommendations[] = 'وجود موقع إلكتروني يعزز فرص القبول';
        }

        if (empty($this->attachments) || count($this->attachments) == 0) {
            $recommendations[] = 'إرفاق مراجع أو صور للأعمال السابقة يحسّن التقييم';
        }

        if ($score >= 85) {
            $recommendations[] = '✅ ملفك الشخصي متميز! نتطلع للتعاون معك';
        }

        return $recommendations;
    }

    /**
     * Scopes للفلترة
     */
    public function scopeByCountry($query, ?string $country)
    {
        if (empty($country)) {
            return $query;
        }

        return $query->where('country', $country);
    }

    public function scopeBySector($query, ?string $sector)
    {
        if (empty($sector)) {
            return $query;
        }

        return $query->where('sector', $sector);
    }

    public function scopeByDecision($query, ?string $decision)
    {
        if (empty($decision)) {
            return $query;
        }

        return $query->where('decision', $decision);
    }

    public function scopePending($query)
    {
        return $query->where('decision', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('decision', 'accepted');
    }
}
