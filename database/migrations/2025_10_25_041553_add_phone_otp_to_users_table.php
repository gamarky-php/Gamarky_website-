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
            // OTP code storage (6-8 digits)
            $table->string('phone_otp', 8)->nullable()->after('phone_verified_at');
            
            // OTP expiration timestamp
            $table->timestamp('phone_otp_expires_at')->nullable()->after('phone_otp');
            
            // Index for faster lookups
            $table->index('phone_otp_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['phone_otp_expires_at']);
            $table->dropColumn(['phone_otp', 'phone_otp_expires_at']);
        });
    }
};
