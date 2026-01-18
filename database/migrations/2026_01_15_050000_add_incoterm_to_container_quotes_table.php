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
        if (!Schema::hasColumn('container_quotes', 'incoterm')) {
            Schema::table('container_quotes', function (Blueprint $table) {
                $table->string('incoterm', 10)->nullable()->after('quote_number')->index();
            });

            // Backfill من الأعمدة الموجودة
            if (Schema::hasColumn('container_quotes', 'trade_term')) {
                DB::table('container_quotes')
                    ->whereNull('incoterm')
                    ->whereNotNull('trade_term')
                    ->update(['incoterm' => DB::raw('trade_term')]);
            } elseif (Schema::hasColumn('container_quotes', 'incoterms')) {
                DB::table('container_quotes')
                    ->whereNull('incoterm')
                    ->whereNotNull('incoterms')
                    ->update(['incoterm' => DB::raw('incoterms')]);
            } elseif (Schema::hasColumn('container_quotes', 'terms')) {
                DB::table('container_quotes')
                    ->whereNull('incoterm')
                    ->whereNotNull('terms')
                    ->update(['incoterm' => DB::raw('terms')]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('container_quotes', 'incoterm')) {
            Schema::table('container_quotes', function (Blueprint $table) {
                $table->dropColumn('incoterm');
            });
        }
    }
};
