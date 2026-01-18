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
        Schema::table('ads', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->index('is_active');
            $table->index(['starts_at', 'ends_at']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['starts_at', 'ends_at']);
            $table->dropIndex(['priority']);
        });
    }
};
