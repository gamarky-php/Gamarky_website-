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
        if (Schema::hasTable('notifications')) {
            // Add user_id column
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('notifiable_id');
                }
            });

            // Populate user_id from notifiable_id where notifiable_type is User
            DB::table('notifications')
                ->where('notifiable_type', 'App\\Models\\User')
                ->whereNull('user_id')
                ->update([
                    'user_id' => DB::raw('notifiable_id')
                ]);

            // Add index for better performance
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'user_id')) {
                    return; // Column check already done, this is for index
                }
                $table->index('user_id', 'notifications_user_id_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // Drop index first
                $table->dropIndex('notifications_user_id_index');
                
                // Drop column
                $table->dropColumn('user_id');
            });
        }
    }
};
