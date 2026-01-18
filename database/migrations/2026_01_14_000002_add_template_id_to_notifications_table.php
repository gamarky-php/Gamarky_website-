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
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('notifications', 'template_id')) {
                    // Add after user_id if exists, otherwise after notifiable_id
                    if (Schema::hasColumn('notifications', 'user_id')) {
                        $table->unsignedBigInteger('template_id')->nullable()->after('user_id');
                    } else {
                        $table->unsignedBigInteger('template_id')->nullable()->after('notifiable_id');
                    }
                    
                    // Add index for better join performance
                    $table->index('template_id', 'notifications_template_id_index');
                }
            });

            // Add foreign key only if notification_templates table exists
            if (Schema::hasTable('notification_templates')) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->foreign('template_id', 'notifications_template_id_foreign')
                          ->references('id')
                          ->on('notification_templates')
                          ->onDelete('set null');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // Drop foreign key if exists
                if (Schema::hasTable('notification_templates')) {
                    $table->dropForeign('notifications_template_id_foreign');
                }
                
                // Drop index
                $table->dropIndex('notifications_template_id_index');
                
                // Drop column
                $table->dropColumn('template_id');
            });
        }
    }
};
