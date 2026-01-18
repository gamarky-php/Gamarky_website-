<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds delivery_date and expected_delivery to container_bookings and truck_bookings.
     * Safe to run multiple times - checks before adding.
     */
    public function up(): void
    {
        // Container bookings
        if (Schema::hasTable('container_bookings')) {
            Schema::table('container_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('container_bookings', 'delivery_date')) {
                    $table->dateTime('delivery_date')->nullable()->after('status')->comment('Actual delivery date');
                }
                
                if (!Schema::hasColumn('container_bookings', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable()->after('delivery_date')->comment('Expected delivery date');
                }
            });
        }

        // Truck bookings
        if (Schema::hasTable('truck_bookings')) {
            Schema::table('truck_bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('truck_bookings', 'delivery_date')) {
                    $table->dateTime('delivery_date')->nullable()->after('status')->comment('Actual delivery date');
                }
                
                if (!Schema::hasColumn('truck_bookings', 'expected_delivery')) {
                    $table->dateTime('expected_delivery')->nullable()->after('delivery_date')->comment('Expected delivery date');
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
                if (Schema::hasColumn('container_bookings', 'delivery_date')) {
                    $table->dropColumn('delivery_date');
                }
                if (Schema::hasColumn('container_bookings', 'expected_delivery')) {
                    $table->dropColumn('expected_delivery');
                }
            });
        }

        if (Schema::hasTable('truck_bookings')) {
            Schema::table('truck_bookings', function (Blueprint $table) {
                if (Schema::hasColumn('truck_bookings', 'delivery_date')) {
                    $table->dropColumn('delivery_date');
                }
                if (Schema::hasColumn('truck_bookings', 'expected_delivery')) {
                    $table->dropColumn('expected_delivery');
                }
            });
        }
    }
};
