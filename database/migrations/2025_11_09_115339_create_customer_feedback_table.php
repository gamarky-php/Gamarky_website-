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
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('service_type'); // 'container', 'truck', 'import', 'export', 'clearance'
            $table->morphs('related'); // Polymorphic relation to bookings, clearances, etc.
            $table->tinyInteger('csat_score')->comment('1-5 scale'); // Customer Satisfaction Score
            $table->tinyInteger('nps_score')->comment('0-10 scale'); // Net Promoter Score
            $table->enum('sentiment', ['positive', 'neutral', 'negative'])->nullable();
            $table->text('comments')->nullable();
            $table->json('metadata')->nullable(); // Additional feedback data
            $table->timestamps();
            
            $table->index(['service_type', 'created_at']);
            $table->index(['csat_score', 'created_at']);
            $table->index(['nps_score', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
    }
};
