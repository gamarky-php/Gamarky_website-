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
        // Add delivery columns to container_bookings if table exists and columns missing
        if (Schema::hasTable('container_bookings')) {
            Schema::table('container_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('container_bookings', 'delivery_date')) {
                    $table->dateTime('delivery_date')->nullable()->after('status');
                }
                if (!Schema::hasColumn('container_bookings', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable()->after('delivery_date');
                }
            });
        }

        // Add delivery columns to truck_bookings if table exists and columns missing
        if (Schema::hasTable('truck_bookings')) {
            Schema::table('truck_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('truck_bookings', 'delivery_date')) {
                    $table->dateTime('delivery_date')->nullable()->after('status');
                }
                if (!Schema::hasColumn('truck_bookings', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable()->after('delivery_date');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('container_bookings')) {
            Schema::table('container_bookings', function (Blueprint $table) {
                $table->dropColumn(['delivery_date', 'expected_delivery']);
            });
        }

        if (Schema::hasTable('truck_bookings')) {
            Schema::table('truck_bookings', function (Blueprint $table) {
                $table->dropColumn(['delivery_date', 'expected_delivery']);
            });
        }
    }
};
