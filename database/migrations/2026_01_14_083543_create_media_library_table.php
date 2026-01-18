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
        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index()->comment('User who uploaded the file');
            $table->string('disk', 50)->default('public')->comment('Storage disk');
            $table->string('path')->comment('Storage path');
            $table->string('filename')->index()->comment('File name with extension');
            $table->string('original_name')->nullable()->comment('Original uploaded file name');
            $table->string('mime_type', 100)->nullable()->index()->comment('MIME type (image/jpeg, video/mp4, etc.)');
            $table->string('extension', 10)->nullable()->index()->comment('File extension (jpg, png, pdf, etc.)');
            $table->unsignedBigInteger('size_bytes')->default(0)->comment('File size in bytes');
            $table->unsignedInteger('width')->nullable()->comment('Image/Video width in pixels');
            $table->unsignedInteger('height')->nullable()->comment('Image/Video height in pixels');
            $table->string('alt')->nullable()->comment('Alt text for images');
            $table->text('caption')->nullable()->comment('Media caption/description');
            $table->json('tags')->nullable()->comment('Tags for categorization');
            $table->boolean('is_public')->default(true)->index()->comment('Public accessibility');
            $table->json('meta')->nullable()->comment('Additional metadata');
            $table->timestamps();

            // Foreign key constraint
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->nullOnDelete();
            }

            // Additional indexes for performance
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_library');
    }
};
