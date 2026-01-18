<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates import_requests table if it doesn't exist.
     * Safe to run multiple times.
     */
    public function up(): void
    {
        if (!Schema::hasTable('import_requests')) {
            Schema::create('import_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index()->comment('User who created the request');
                $table->string('status', 50)->nullable()->index()->comment('Request status (pending, approved, rejected, completed)');
                $table->string('origin_country')->nullable()->comment('Country of origin');
                $table->string('destination_country')->nullable()->comment('Destination country');
                $table->text('product_description')->nullable()->comment('Product details');
                $table->decimal('estimated_value', 15, 2)->nullable()->comment('Estimated import value');
                $table->timestamps();
                
                // Foreign key if users table exists
                if (Schema::hasTable('users')) {
                    $table->foreign('user_id')
                          ->references('id')
                          ->on('users')
                          ->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_requests');
    }
};
