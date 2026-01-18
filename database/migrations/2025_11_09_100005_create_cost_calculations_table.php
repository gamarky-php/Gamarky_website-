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
        Schema::create('cost_calculations', function (Blueprint $table) {
            $table->id();
            $table->enum('module', [
                'import',
                'export',
                'manufacturing',
                'customs',
                'transport',
                'agency'
            ])->index();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ref_code')->unique();
            $table->string('title')->nullable();
            
            // Input parameters (varies by module)
            $table->json('inputs')->nullable(); 
            // Example for import: {origin_country, incoterm, product_category, weight, volume}
            // Example for transport: {origin, destination, vehicle_type, distance}
            
            // Line items
            $table->json('items')->nullable();
            // [{description, quantity, unit_price, total, category}]
            
            // Calculated totals
            $table->json('totals')->nullable();
            // {subtotal, taxes, fees, shipping, insurance, grand_total}
            
            $table->string('currency', 3)->default('SAR');
            $table->decimal('margin_percent', 5, 2)->default(0.00);
            $table->decimal('final_total', 15, 2)->nullable();
            
            // Save type
            $table->enum('saved_as', ['quote', 'invoice', 'scenario', 'draft'])->default('scenario');
            
            // Metadata
            $table->json('metadata')->nullable(); 
            // {client_name, client_email, valid_until, payment_terms}
            
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'module']);
            $table->index('ref_code');
            $table->index(['module', 'status']);
            $table->index('saved_as');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_calculations');
    }
};
