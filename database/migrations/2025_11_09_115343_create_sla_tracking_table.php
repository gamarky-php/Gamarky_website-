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
        Schema::create('sla_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clearance_request_id')->index(); // Will be linked when clearance_requests exists
            $table->unsignedBigInteger('broker_id')->index(); // Will be linked when customs_brokers exists
            $table->integer('sla_hours'); // Expected SLA in hours
            $table->integer('actual_hours'); // Actual hours taken
            $table->boolean('met_sla')->default(false);
            $table->integer('variance_hours')->comment('Difference between actual and SLA');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['broker_id', 'met_sla']);
            $table->index(['clearance_request_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_tracking');
    }
};
