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
        Schema::create('agent_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            
            // نوع العملية
            $table->enum('operation', [
                'collect',              // تجميع - 1 نقطة
                'store',                // تخزين - 1 نقطة
                'load',                 // تحميل - 1 نقطة
                'docs',                 // مستندات - 2 نقطة
                'cargox',               // CargoX - 2 نقطة
                'send_to_importer'      // إرسال للمستورد - 1 نقطة
            ]);
            
            $table->timestamp('completed_at')->nullable();
            $table->tinyInteger('points')->default(0); // النقاط المكتسبة
            
            // مراجع إضافية (أرقام شحنات/مستندات)
            $table->json('refs')->nullable();
            
            $table->timestamps();
            
            // فهرس للبحث السريع
            $table->index(['agent_id', 'completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_operations');
    }
};
