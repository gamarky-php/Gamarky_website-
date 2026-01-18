<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add CTR and analytics columns to ads_analytics if table exists
        if (Schema::hasTable('ads_analytics')) {
            Schema::table('ads_analytics', function (Blueprint $table) {
                if (!Schema::hasColumn('ads_analytics', 'ctr')) {
                    $table->decimal('ctr', 8, 4)->nullable()->after('clicks');
                }
                if (!Schema::hasColumn('ads_analytics', 'impressions')) {
                    $table->bigInteger('impressions')->default(0)->after('id');
                }
                if (!Schema::hasColumn('ads_analytics', 'clicks')) {
                    $table->bigInteger('clicks')->default(0)->after('impressions');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ads_analytics')) {
            Schema::table('ads_analytics', function (Blueprint $table) {
                $table->dropColumn(['ctr', 'impressions', 'clicks']);
            });
        }
    }
};
