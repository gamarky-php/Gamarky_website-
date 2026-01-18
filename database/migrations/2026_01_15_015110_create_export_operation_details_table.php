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
        Schema::create('export_operation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('operation_type')->nullable();
            $table->string('status')->default('pending')->index(); // pending, processing, in_transit, completed, cancelled
            $table->string('reference_number')->nullable()->unique();
            $table->string('product_name')->nullable();
            $table->text('product_description')->nullable();
            $table->string('hs_code')->nullable();
            $table->decimal('quantity', 14, 2)->default(0);
            $table->string('unit')->nullable();
            $table->string('target_country')->nullable();
            $table->string('destination_country')->nullable()->index();
            $table->string('target_market')->nullable();
            $table->json('market_requirements')->nullable();
            $table->decimal('fob_price', 14, 2)->default(0);
            $table->decimal('shipping_cost', 14, 2)->default(0);
            $table->decimal('insurance_cost', 14, 2)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('total_value', 14, 2)->default(0)->index();
            $table->date('shipment_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->json('export_documents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_operation_details');
    }
};
