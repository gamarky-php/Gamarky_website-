<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds missing columns to clearance_requests table if table exists.
     * Safe to run multiple times - checks before adding.
     */
    public function up(): void
    {
        if (!Schema::hasTable('clearance_requests')) {
            return; // Table doesn't exist, skip
        }

        Schema::table('clearance_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('clearance_requests', 'actual_clearance_date')) {
                $table->dateTime('actual_clearance_date')->nullable()->after('status')->comment('Actual clearance completion date');
            }
            
            if (!Schema::hasColumn('clearance_requests', 'started_at')) {
                $table->dateTime('started_at')->nullable()->after('created_at')->comment('When clearance process started');
            }
            
            if (!Schema::hasColumn('clearance_requests', 'status')) {
                $table->string('status', 50)->default('pending')->after('id')->index()->comment('Clearance status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('clearance_requests')) {
            return;
        }

        Schema::table('clearance_requests', function (Blueprint $table) {
            if (Schema::hasColumn('clearance_requests', 'actual_clearance_date')) {
                $table->dropColumn('actual_clearance_date');
            }
            if (Schema::hasColumn('clearance_requests', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};
