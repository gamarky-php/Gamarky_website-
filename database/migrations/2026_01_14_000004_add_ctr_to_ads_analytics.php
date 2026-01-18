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
        if (Schema::hasTable('ads_analytics')) {
            Schema::table('ads_analytics', function (Blueprint $table) {
                if (!Schema::hasColumn('ads_analytics', 'ctr')) {
                    $table->decimal('ctr', 5, 2)->nullable();
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
                $table->dropColumn(['ctr']);
            });
        }
    }
};
