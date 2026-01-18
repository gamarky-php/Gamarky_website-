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
        // Truck Quotes table
        Schema::create('truck_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('request_ref')->unique();
            $table->foreignId('requester_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('carrier')->nullable(); // Transport company name
            $table->enum('vehicle_type', [
                'flatbed',
                'box_truck',
                'refrigerated',
                'tanker',
                'lowboy',
                'container_chassis'
            ])->default('box_truck');
            $table->json('pickup')->nullable(); // {address, city, postal_code, country, coordinates}
            $table->json('delivery')->nullable(); // {address, city, postal_code, country, coordinates}
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('total_price', 12, 2);
            $table->string('currency', 3)->default('SAR');
            $table->unsignedSmallInteger('transit_hours')->nullable();
            $table->json('breakdown')->nullable(); // {base_rate, fuel_surcharge, tolls, loading_fees}
            $table->json('inclusions')->nullable(); // ['Loading', 'Unloading']
            $table->timestamp('valid_until');
            $table->enum('status', ['active', 'expired', 'accepted', 'declined'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('request_ref');
            $table->index(['status', 'valid_until']);
            $table->index('carrier');
            $table->index('vehicle_type');
        });

        // Truck Bookings table
        Schema::create('truck_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->nullable()->constrained('truck_quotes')->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('booking_ref')->unique();
            $table->json('pickup')->nullable(); // {address, datetime, contact_person, phone}
            $table->json('delivery')->nullable(); // {address, datetime, contact_person, phone}
            $table->json('driver')->nullable(); // {name, phone, license_no, photo}
            $table->string('vehicle_registration')->nullable();
            $table->enum('vehicle_type', [
                'flatbed',
                'box_truck',
                'refrigerated',
                'tanker',
                'lowboy',
                'container_chassis'
            ])->default('box_truck');
            $table->json('cargo_details')->nullable(); // {description, weight, volume, special_requirements}
            $table->json('docs')->nullable(); // {waybill, pod, insurance}
            $table->enum('status', [
                'pending',
                'confirmed',
                'driver_assigned',
                'en_route_pickup',
                'loaded',
                'in_transit',
                'arrived',
                'delivered',
                'cancelled'
            ])->default('pending');
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->string('currency', 3)->default('SAR');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('booking_ref');
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('vehicle_registration');
        });

        // Truck Tracking table
        Schema::create('truck_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('truck_bookings')->onDelete('cascade');
            $table->enum('status', [
                'waiting',
                'en_route_pickup',
                'loading',
                'in_transit',
                'unloading',
                'delivered',
                'returning'
            ])->default('waiting');
            $table->timestamp('eta')->nullable();
            $table->timestamp('actual_delivery')->nullable();
            $table->json('last_position')->nullable(); // {lat, lng, address, timestamp}
            $table->json('route')->nullable(); // array of waypoints
            $table->json('events')->nullable(); // [{event, timestamp, location, notes}]
            $table->decimal('distance_traveled_km', 8, 2)->nullable();
            $table->timestamps();
            
            $table->index('booking_id');
            $table->index('status');
            $table->index('eta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_tracking');
        Schema::dropIfExists('truck_bookings');
        Schema::dropIfExists('truck_quotes');
    }
};
