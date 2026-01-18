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
        // Roles table (if not using Spatie Permission)
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->text('description')->nullable();
                $table->json('permissions')->nullable();
                $table->boolean('is_system')->default(false);
                $table->timestamps();
                
                $table->index('name');
            });
        }

        // Subscriptions table
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->enum('plan', ['free', 'basic', 'premium', 'enterprise'])->default('free');
                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();
                $table->enum('status', ['active', 'expired', 'cancelled', 'suspended'])->default('active');
                $table->json('features')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->string('currency', 3)->default('SAR');
                $table->string('payment_method')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'status']);
                $table->index('plan');
                $table->index('end_at');
            });
        }

        // Notifications table (custom implementation)
        // Laravel already has notifications table, skip if exists
        
        // Ads table
        if (!Schema::hasTable('ads')) {
            Schema::create('ads', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->enum('placement', ['header', 'sidebar', 'footer', 'modal', 'banner', 'inline'])->default('sidebar');
                $table->string('image_url')->nullable();
                $table->string('link_url')->nullable();
                $table->timestamp('start_at')->nullable();
                $table->timestamp('end_at')->nullable();
                $table->enum('status', ['draft', 'active', 'paused', 'expired'])->default('draft');
                $table->json('metrics')->nullable(); // impressions, clicks, conversions
                $table->json('targeting')->nullable(); // user segments, geo, etc.
                $table->integer('priority')->default(0);
                $table->timestamps();
                
                $table->index(['status', 'start_at', 'end_at']);
                $table->index('placement');
            });
        }

        // Media table
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('owner_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('type', ['image', 'video', 'document', 'audio', 'archive'])->default('image');
                $table->string('path');
                $table->string('filename');
                $table->string('mime_type')->nullable();
                $table->unsignedBigInteger('size')->default(0); // in bytes
                $table->enum('visibility', ['public', 'private', 'restricted'])->default('private');
                $table->json('tags')->nullable();
                $table->json('meta')->nullable(); // dimensions, duration, alt_text, etc.
                $table->string('disk')->default('public');
                $table->timestamps();
                
                $table->index(['owner_id', 'type']);
                $table->index('visibility');
                $table->index('created_at');
            });
        }

        // Articles table
        if (!Schema::hasTable('articles')) {
            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->longText('content');
                $table->text('excerpt')->nullable();
                $table->string('category')->nullable();
                $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
                $table->json('media_ids')->nullable();
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->json('seo')->nullable(); // meta_title, meta_description, keywords
                $table->timestamp('published_at')->nullable();
                $table->unsignedInteger('views_count')->default(0);
                $table->timestamps();
                
                $table->index('slug');
                $table->index(['status', 'published_at']);
                $table->index('category');
                $table->index('author_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('media');
        Schema::dropIfExists('ads');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('roles');
    }
};
