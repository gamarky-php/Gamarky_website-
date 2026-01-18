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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('contact_name')->nullable();
            $table->string('country');
            $table->string('city')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            
            // القدرات التقنية
            $table->boolean('has_cargox')->default(false);
            $table->boolean('has_einvoice')->default(false);
            
            // المخازن المتاحة
            $table->json('warehouses')->nullable(); // ["خاصة","عامة","مبردة","جافة"]
            
            // مؤشرات الأداء
            $table->unsignedSmallInteger('avg_response_hours')->default(24);
            $table->unsignedTinyInteger('on_time_ratio')->default(90); // %
            $table->unsignedTinyInteger('doc_accuracy_ratio')->default(95); // %
            
            // التقييمات
            $table->unsignedTinyInteger('rating_auto')->default(0); // يُحسب آليًا
            $table->decimal('rating_client', 3, 2)->default(0.00); // من العملاء
            
            // الشارات والخدمات
            $table->json('badges')->nullable(); // ["موثوق","ذهبي","سريع"]
            $table->json('services')->nullable(); // ["تجميع","تخزين","تحميل","مستندات","CargoX","فاتورة"]
            
            // ملاحظات
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
