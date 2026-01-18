<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mfg_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('mfg_cost_run_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();

            $table->decimal('unit_cost', 14, 4)->default(0);
            $table->decimal('margin_pct', 5, 2)->default(0);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('qty', 14, 2)->default(1);
            $table->decimal('total_amount', 14, 2)->default(0);

            $table->string('currency', 10)->default('USD');
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('draft');

            $table->json('meta')->nullable();
            $table->unsignedBigInteger('created_by');

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mfg_quotes');
    }
};
