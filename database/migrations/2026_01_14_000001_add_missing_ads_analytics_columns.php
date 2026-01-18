<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds missing columns to ads_analytics table if table exists.
     * Safe to run multiple times - checks before adding.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ads_analytics')) {
            return; // Table doesn't exist, skip
        }

        Schema::table('ads_analytics', function (Blueprint $table) {
            if (!Schema::hasColumn('ads_analytics', 'ctr')) {
                $table->decimal('ctr', 8, 4)->nullable()->after('clicks')->comment('Click-through rate percentage');
            }
            
            if (!Schema::hasColumn('ads_analytics', 'date')) {
                $table->date('date')->nullable()->after('id')->index()->comment('Analytics date');
            }
            
            if (!Schema::hasColumn('ads_analytics', 'impressions')) {
                $table->bigInteger('impressions')->default(0)->after('id')->comment('Total impressions');
            }
            
            if (!Schema::hasColumn('ads_analytics', 'clicks')) {
                $table->bigInteger('clicks')->default(0)->after('impressions')->comment('Total clicks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('ads_analytics')) {
            return;
        }

        Schema::table('ads_analytics', function (Blueprint $table) {
            if (Schema::hasColumn('ads_analytics', 'ctr')) {
                $table->dropColumn('ctr');
            }
            if (Schema::hasColumn('ads_analytics', 'date')) {
                $table->dropColumn('date');
            }
        });
    }
};
