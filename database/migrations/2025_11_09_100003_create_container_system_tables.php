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
        // Container Quotes table
        if (!Schema::hasTable('container_quotes')) {
            Schema::create('container_quotes', function (Blueprint $table) {
                $table->id();
                $table->string('request_ref')->unique();
                $table->foreignId('requester_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('carrier')->nullable(); // Shipping line name
                $table->string('origin_port', 10);
                $table->string('destination_port', 10);
                $table->enum('container_type', ['20ft', '40ft', '40hc', '45hc', 'reefer_20', 'reefer_40'])->default('20ft');
                $table->decimal('price', 12, 2);
                $table->string('currency', 3)->default('USD');
                $table->unsignedSmallInteger('transit_days')->nullable();
                $table->json('breakdown')->nullable(); // ['ocean_freight' => 1200, 'baf' => 150, 'caf' => 80]
                $table->json('inclusions')->nullable(); // ['THC', 'Documentation']
                $table->json('exclusions')->nullable(); // ['Customs', 'Delivery']
                $table->timestamp('valid_until'); // Quote expiry (TTL)
                $table->enum('status', ['active', 'expired', 'accepted', 'declined'])->default('active');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index('request_ref');
                $table->index(['origin_port', 'destination_port']);
                $table->index(['status', 'valid_until']);
                $table->index('carrier');
            });
        }

        // Container Bookings table
        if (!Schema::hasTable('container_bookings')) {
            Schema::create('container_bookings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quote_id')->nullable()->constrained('container_quotes')->onDelete('set null');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('booking_ref')->unique();
                $table->string('container_no')->nullable();
                $table->string('seal_no')->nullable();
                $table->enum('container_type', ['20ft', '40ft', '40hc', '45hc', 'reefer_20', 'reefer_40'])->default('20ft');
                $table->json('schedule')->nullable(); // {etd, eta, vessel, voyage}
                $table->json('docs')->nullable(); // {bl, invoice, packing_list, coo}
                $table->json('cargo_details')->nullable(); // {description, weight, volume, hs_code}
                $table->json('payment')->nullable(); // {method, status, amount, transaction_id}
                $table->enum('status', [
                    'pending',
                    'confirmed',
                    'loading',
                    'in_transit',
                    'arrived',
                    'customs',
                    'released',
                    'delivered',
                    'cancelled'
                ])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                $table->index('booking_ref');
                $table->index('container_no');
                $table->index(['user_id', 'status']);
                $table->index('status');
            });
        }

        // Container Tracking table
        if (!Schema::hasTable('container_tracking')) {
            Schema::create('container_tracking', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->nullable()->constrained('container_bookings')->onDelete('cascade');
                $table->string('container_no');
                $table->string('bol')->nullable(); // Bill of Lading
                $table->enum('status', [
                    'empty_gate_out',
                    'loaded',
                    'gate_in',
                    'vessel_loaded',
                    'in_transit',
                    'discharged',
                    'gate_out',
                    'delivered',
                    'empty_returned'
                ])->default('empty_gate_out');
                $table->json('progress')->nullable(); // [{location, timestamp, event}]
                $table->json('position')->nullable(); // {lat, lng, location_name}
                $table->timestamp('eta')->nullable();
                $table->timestamp('actual_arrival')->nullable();
                $table->string('current_location')->nullable();
                $table->string('vessel_name')->nullable();
                $table->string('voyage_number')->nullable();
                $table->timestamps();
                
                $table->index('bol');
                $table->index('status');
                $table->index('eta');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_tracking');
        Schema::dropIfExists('container_bookings');
        Schema::dropIfExists('container_quotes');
    }
};
