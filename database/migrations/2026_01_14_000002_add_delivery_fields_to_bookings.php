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
        // Add columns to container_bookings
        if (Schema::hasTable('container_bookings')) {
            Schema::table('container_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('container_bookings', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable();
                }
                if (!Schema::hasColumn('container_bookings', 'delivery_date')) {
                    $table->dateTime('delivery_date')->nullable();
                }
                if (!Schema::hasColumn('container_bookings', 'status')) {
                    $table->string('status')->nullable();
                }
            });
        }

        // Add columns to truck_bookings
        if (Schema::hasTable('truck_bookings')) {
            Schema::table('truck_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('truck_bookings', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable();
                }
                if (!Schema::hasColumn('truck_bookings', 'delivery_date')) {
                    $table->dateTime('delivery_date')->nullable();
                }
                if (!Schema::hasColumn('truck_bookings', 'status')) {
                    $table->string('status')->nullable();
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
                $table->dropColumn(['expected_delivery', 'delivery_date']);
            });
        }

        if (Schema::hasTable('truck_bookings')) {
            Schema::table('truck_bookings', function (Blueprint $table) {
                $table->dropColumn(['expected_delivery', 'delivery_date']);
            });
        }
    }
};
