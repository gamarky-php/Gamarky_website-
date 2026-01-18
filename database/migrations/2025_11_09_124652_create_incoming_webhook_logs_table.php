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
        Schema::create('incoming_webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_type'); // booking.confirmed, documents.completed, etc.
            $table->json('payload'); // Full request payload
            $table->string('source_ip')->nullable();
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->json('response')->nullable(); // Notification result
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index('event_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_webhook_logs');
    }
};
