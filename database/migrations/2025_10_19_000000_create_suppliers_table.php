<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('province')->nullable();
                $table->string('city')->nullable();
                $table->string('contact_person')->nullable();
                $table->string('mr_ms',10)->nullable();
                $table->string('mobile_phone',50)->nullable();
                $table->string('tel',50)->nullable();
                $table->string('fax',50)->nullable();
                $table->string('address')->nullable();
                $table->string('post_code',20)->nullable();
                $table->string('website')->nullable();
                $table->text('introduction')->nullable();
                $table->text('main_products')->nullable();
                $table->string('company_name_cn')->nullable();
                $table->string('country_code',2)->nullable()->default('CN');
                $table->enum('status',['pending','approved'])->default('pending');
                $table->string('source')->nullable();
                $table->string('external_id')->nullable();
                $table->timestamps();
                $table->index(['province','city']);
                $table->index('status');
                $table->unique(['company_name','province','city'],'uniq_supplier_name_area');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
