<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('sku')->unique();
            $t->string('name');
            $t->string('uom')->default('unit');
            $t->unsignedInteger('default_batch')->default(100);
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('mfg_cost_runs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('batch_size');
            $t->decimal('scrap_pct', 5, 2)->default(0);
            $t->string('currency')->default('USD');
            $t->decimal('fx_rate', 12, 6)->default(1);
            $t->decimal('total_cost', 14, 2)->default(0);
            $t->decimal('unit_cost', 14, 4)->default(0);
            $t->decimal('margin_pct', 5, 2)->default(20);
            $t->decimal('target_price', 14, 2)->nullable();
            $t->json('snapshot_json')->nullable();
            $t->string('status')->default('draft');
            $t->foreignId('created_by')->constrained('users');
            $t->timestamps();
        });

        Schema::create('bom_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('mfg_cost_run_id')->constrained()->cascadeOnDelete();
            $t->string('material');
            $t->string('uom')->default('kg');
            $t->decimal('qty_per_batch', 14, 4);
            $t->decimal('unit_price', 14, 4);
            $t->decimal('scrap_pct', 5, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('routing_ops', function (Blueprint $t) {
            $t->id();
            $t->foreignId('mfg_cost_run_id')->constrained()->cascadeOnDelete();
            $t->unsignedInteger('op_seq');
            $t->string('operation');
            $t->decimal('setup_time_hr', 8, 3)->default(0);
            $t->decimal('run_time_hr', 8, 3)->default(0);
            $t->decimal('labor_rate', 10, 2)->default(0);
            $t->decimal('machine_rate', 10, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('overhead_pools', function (Blueprint $t) {
            $t->id();
            $t->foreignId('mfg_cost_run_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->enum('basis', ['machine_hour', 'labor_hour', 'material_pct'])->default('machine_hour');
            $t->decimal('rate', 10, 4)->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overhead_pools');
        Schema::dropIfExists('routing_ops');
        Schema::dropIfExists('bom_items');
        Schema::dropIfExists('mfg_cost_runs');
        Schema::dropIfExists('products');
    }
};
