<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // clients table (إن لم يكن موجودًا)
        if (! Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $t) {
                $t->id();
                $t->string('name');
                $t->string('email')->nullable();
                $t->string('phone')->nullable();
                $t->string('country')->nullable();
                $t->timestamps();
            });
        }

        // export_shipments (رأس الشحنة)
        Schema::create('export_shipments', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('client_id')->nullable();
            $t->string('origin_country')->nullable();
            $t->string('pol')->nullable(); // port of loading
            $t->string('pod')->nullable(); // port of destination
            $t->enum('incoterm', ['EXW', 'FOB', 'CFR', 'CIF'])->default('FOB');
            $t->enum('method', ['sea', 'air', 'land'])->default('sea');
            $t->string('container_type')->nullable();
            $t->decimal('weight_ton', 12, 3)->nullable();
            $t->decimal('volume_cbm', 12, 3)->nullable();
            $t->date('etd')->nullable();
            $t->string('currency')->default('USD');
            $t->decimal('fx_rate', 12, 6)->default(1);
            $t->string('status')->default('draft'); // draft/review/approved/sent
            $t->unsignedBigInteger('created_by');
            $t->timestamps();

            $t->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            $t->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // export_costs (بنود التكلفة)
        Schema::create('export_costs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('export_shipment_id')->constrained()->cascadeOnDelete();
            $t->string('line_name'); // البيان
            $t->enum('category', [
                'manufacturing',
                'packing',
                'local_clearance',
                'port_fees',
                'local_trucking',
                'freight',
                'insurance',
                'bank',
                'docs',
                'extras',
                'profit',
                'final_price',
            ]);
            $t->unsignedInteger('col_index')->default(1); // بند 1/2/3
            $t->decimal('amount', 14, 2)->default(0);
            $t->string('currency')->default('USD');
            $t->json('meta')->nullable(); // ملاحظات/وحدة/كمية...
            $t->timestamps();
        });

        // export_quotes (العرض الناتج)
        Schema::create('export_quotes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('export_shipment_id')->constrained()->cascadeOnDelete();
            $t->string('quote_no');
            $t->enum('incoterm_final', ['EXW', 'FOB', 'CFR', 'CIF']);
            $t->decimal('total_cost', 14, 2);
            $t->decimal('unit_cost', 14, 4)->nullable();
            $t->decimal('margin_pct', 5, 2)->nullable();
            $t->decimal('sell_price', 14, 2)->nullable();
            $t->string('currency')->default('USD');
            $t->string('pdf_path')->nullable();
            $t->string('status')->default('draft'); // draft/sent/accepted/rejected
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_quotes');
        Schema::dropIfExists('export_costs');
        Schema::dropIfExists('export_shipments');
    }
};
