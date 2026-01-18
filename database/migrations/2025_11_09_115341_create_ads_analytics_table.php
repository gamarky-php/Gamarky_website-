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
        Schema::create('ads_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id')->index(); // Will be linked later when dashboard_ads exists
            $table->date('date')->index();
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->decimal('revenue', 10, 2)->default(0); // Revenue generated from this ad
            $table->decimal('cost', 10, 2)->default(0); // Cost of running the ad
            $table->json('metadata')->nullable(); // Additional analytics data (CTR, ROI, etc.)
            $table->timestamps();
            
            $table->unique(['ad_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads_analytics');
    }
};
