<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class CleanExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clean 
                            {--days=7 : حذف tokens غير المستخدمة لأكثر من هذا العدد من الأيام}
                            {--expired : حذف tokens المنتهية الصلاحية فقط}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تنظيف Sanctum tokens المنتهية الصلاحية أو غير المستخدمة';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $expiredOnly = $this->option('expired');

        if ($expiredOnly) {
            $this->cleanExpiredTokens();
        } else {
            $this->cleanUnusedTokens($days);
        }

        return Command::SUCCESS;
    }

    /**
     * حذف tokens المنتهية الصلاحية
     */
    private function cleanExpiredTokens()
    {
        $this->info('البحث عن tokens منتهية الصلاحية...');

        $expiredTokens = PersonalAccessToken::where('expires_at', '<', now())
            ->get();

        if ($expiredTokens->isEmpty()) {
            $this->info('✅ لا توجد tokens منتهية الصلاحية');
            return;
        }

        $count = $expiredTokens->count();
        
        if ($this->confirm("هل تريد حذف {$count} token منتهي الصلاحية؟")) {
            PersonalAccessToken::where('expires_at', '<', now())->delete();
            $this->info("✅ تم حذف {$count} token منتهي الصلاحية");
        } else {
            $this->info('تم إلغاء العملية');
        }
    }

    /**
     * حذف tokens غير المستخدمة
     */
    private function cleanUnusedTokens($days)
    {
        $this->info("البحث عن tokens غير مستخدمة لأكثر من {$days} أيام...");

        $cutoffDate = now()->subDays($days);
        
        $unusedTokens = PersonalAccessToken::where(function ($query) use ($cutoffDate) {
            $query->where('last_used_at', '<', $cutoffDate)
                  ->orWhereNull('last_used_at');
        })->where('created_at', '<', $cutoffDate)
          ->get();

        if ($unusedTokens->isEmpty()) {
            $this->info('✅ لا توجد tokens غير مستخدمة');
            return;
        }

        $count = $unusedTokens->count();

        // عرض تفاصيل tokens
        $this->table(
            ['ID', 'اسم الجهاز', 'آخر استخدام', 'تاريخ الإنشاء'],
            $unusedTokens->map(function ($token) {
                return [
                    $token->id,
                    $token->name,
                    $token->last_used_at ? $token->last_used_at->diffForHumans() : 'لم يستخدم أبداً',
                    $token->created_at->diffForHumans()
                ];
            })
        );

        if ($this->confirm("هل تريد حذف {$count} token غير مستخدم؟")) {
            PersonalAccessToken::where(function ($query) use ($cutoffDate) {
                $query->where('last_used_at', '<', $cutoffDate)
                      ->orWhereNull('last_used_at');
            })->where('created_at', '<', $cutoffDate)
              ->delete();
              
            $this->info("✅ تم حذف {$count} token غير مستخدم");
        } else {
            $this->info('تم إلغاء العملية');
        }
    }
}
