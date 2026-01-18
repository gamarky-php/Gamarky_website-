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
        Schema::table('users', function (Blueprint $table) {
            // Country field - stores user's country
            $table->string('country', 100)->nullable()->after('email');

            // Phone field - stores phone number for verification
            $table->string('phone', 20)->nullable()->after('country');

            // Activity type field - single selection from: import, export, manufacturing, broker, containers, agent
            $table->string('activity_type', 50)->nullable()->after('phone');

            // Business sector field - user's business domain
            $table->string('business_sector', 100)->nullable()->after('activity_type');

            // Newsletter subscription flag
            $table->boolean('newsletter')->default(false)->after('business_sector');

            // Phone verification timestamp
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');

            // Add indexes for better query performance
            $table->index('country');
            $table->index('activity_type');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['country']);
            $table->dropIndex(['activity_type']);
            $table->dropIndex(['phone']);

            $table->dropColumn([
                'country',
                'phone',
                'activity_type',
                'business_sector',
                'newsletter',
                'phone_verified_at',
            ]);
        });
    }
};

