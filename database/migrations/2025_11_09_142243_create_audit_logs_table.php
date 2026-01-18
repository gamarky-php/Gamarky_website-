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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 50); // create, update, delete, login, logout, etc.
            $table->string('model_type')->nullable(); // Model class name
            $table->unsignedBigInteger('model_id')->nullable(); // Model ID
            $table->string('before_hash', 64)->nullable(); // SHA256 hash of before state
            $table->string('after_hash', 64)->nullable(); // SHA256 hash of after state
            $table->json('changes')->nullable(); // Detailed changes
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->string('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Additional context
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('action');
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
