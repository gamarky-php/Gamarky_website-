<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds amount column to subscriptions table if missing.
     * Safe to run multiple times - checks before adding.
     */
    public function up(): void
    {
        if (!Schema::hasTable('subscriptions')) {
            return; // Table doesn't exist, skip
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'amount')) {
                $table->decimal('amount', 12, 2)->nullable()->after('id')->comment('Subscription amount');
            }
            
            if (!Schema::hasColumn('subscriptions', 'status')) {
                $table->string('status', 50)->default('active')->after('amount')->index()->comment('Subscription status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('subscriptions')) {
            return;
        }

        Schema::table('subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('subscriptions', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }
};
