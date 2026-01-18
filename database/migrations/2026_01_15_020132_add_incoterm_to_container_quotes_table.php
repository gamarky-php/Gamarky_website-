<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('container_quotes', function (Blueprint $table) {
            // إضافة incoterm بعد destination_port
            $table->string('incoterm')->nullable()->after('destination_port')->index();
        });

        // ✅ Backfill ذكي: نسخ من trade_term إذا كان موجوداً
        if (Schema::hasColumn('container_quotes', 'trade_term')) {
            DB::table('container_quotes')
                ->whereNotNull('trade_term')
                ->whereNull('incoterm')
                ->update(['incoterm' => DB::raw('trade_term')]);
        } elseif (Schema::hasColumn('container_quotes', 'incoterms')) {
            DB::table('container_quotes')
                ->whereNotNull('incoterms')
                ->whereNull('incoterm')
                ->update(['incoterm' => DB::raw('incoterms')]);
        } elseif (Schema::hasColumn('container_quotes', 'terms')) {
            DB::table('container_quotes')
                ->whereNotNull('terms')
                ->whereNull('incoterm')
                ->update(['incoterm' => DB::raw('terms')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_quotes', function (Blueprint $table) {
            $table->dropColumn('incoterm');
        });
    }
};
