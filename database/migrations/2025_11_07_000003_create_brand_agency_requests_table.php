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
        Schema::create('brand_agency_requests', function (Blueprint $table) {
            $table->id();
            
            // معلومات مقدم الطلب
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('country');
            $table->string('city')->nullable();
            
            // معلومات النشاط
            $table->string('sector'); // "أغذية","إلكترونيات","أزياء"
            $table->tinyInteger('experience_years')->default(0);
            $table->json('current_channels')->nullable(); // ["متاجر","منصات إلكترونية","نقاط بيع"]
            $table->text('expansion_plan')->nullable();
            
            // الوثائق والمرفقات
            $table->json('licenses')->nullable(); // قائمة التراخيص
            $table->json('attachments')->nullable(); // مرفقات إضافية
            
            // معلومات الاتصال
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            
            // التقييم والقرار
            $table->unsignedTinyInteger('score_total')->default(0); // من 100
            $table->enum('decision', ['accepted', 'conditional', 'rejected', 'pending'])->default('pending');
            $table->text('decision_notes')->nullable();
            
            $table->timestamps();
            
            // فهارس للبحث
            $table->index(['country', 'sector']);
            $table->index('decision');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_agency_requests');
    }
};
