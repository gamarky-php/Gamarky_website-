<?php

/**
 * Dashboard KPIs Migration
 * 
 * Purpose: مؤشرات الأداء الرئيسية لجميع الأقسام الستة
 * Dependencies: users table
 * Sections: Import, Export, Manufacturing, Customs, Containers, Agents
 * 
 * KPI Categories:
 * - import_operations: عمليات الاستيراد (quotes, shipments, completed)
 * - export_operations: عمليات التصدير (quotes, shipments, markets_accessed)
 * - manufacturing_runs: الإنتاج (bom_items, cost_runs, quotes_generated)
 * - customs_clearance: التخليص الجمركي (pending, approved, rejected)
 * - container_operations: عمليات الحاويات (bookings, tracking, deliveries)
 * - agent_activities: نشاطات الوكلاء (shipping_ops, brand_requests, commissions)
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول KPIs الرئيسية - تحديث يومي/أسبوعي/شهري
        Schema::create('dashboard_kpis', function (Blueprint $table) {
            $table->id();
            
            // معرّف القسم والفترة
            $table->string('section'); // import, export, manufacturing, customs, containers, agents
            $table->enum('period', ['daily', 'weekly', 'monthly', 'yearly'])->default('daily');
            $table->date('period_date'); // تاريخ الفترة
            
            // KPIs عامة لكل الأقسام
            $table->unsignedInteger('total_operations')->default(0);
            $table->unsignedInteger('completed_operations')->default(0);
            $table->unsignedInteger('pending_operations')->default(0);
            $table->unsignedInteger('cancelled_operations')->default(0);
            
            // معلومات مالية
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('net_profit', 15, 2)->default(0);
            
            // معلومات إضافية محددة (JSON للمرونة)
            $table->json('section_specific_data')->nullable();
            
            // KPIs إضافية قابلة للتخصيص
            $table->json('custom_metrics')->nullable();
            
            $table->timestamps();
            
            // Indexes للأداء
            $table->index(['section', 'period', 'period_date']);
            $table->index('period_date');
        });

        // جدول تفصيلي لعمليات الاستيراد
        Schema::create('import_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('operation_type'); // quote, shipment, clearance
            $table->string('status')->default('pending'); // pending, in_progress, completed, cancelled
            
            // تفاصيل الشحنة
            $table->string('hs_code')->nullable();
            $table->string('product_name');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('kg');
            
            // معلومات المنشأ والوجهة
            $table->string('origin_country')->nullable();
            $table->string('origin_port')->nullable();
            $table->string('destination_port')->nullable();
            
            // التكاليف
            $table->decimal('customs_duty', 10, 2)->nullable();
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            
            // تواريخ مهمة
            $table->date('expected_arrival_date')->nullable();
            $table->date('actual_arrival_date')->nullable();
            $table->date('clearance_date')->nullable();
            
            // معلومات إضافية
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index('hs_code');
        });

        // جدول تفصيلي لعمليات التصدير
        Schema::create('export_operations_detailed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('operation_type'); // quote, shipment, market_research
            $table->string('status')->default('pending');
            
            // تفاصيل المنتج
            $table->string('product_name');
            $table->string('hs_code')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('kg');
            
            // السوق المستهدف
            $table->string('target_country');
            $table->string('target_market')->nullable();
            $table->json('market_requirements')->nullable();
            
            // التكاليف والأسعار
            $table->decimal('fob_price', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('insurance_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            
            // التواريخ
            $table->date('shipment_date')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            
            // الوثائق
            $table->json('export_documents')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index('target_country');
        });

        // جدول تفصيلي لعمليات التصنيع
        Schema::create('manufacturing_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('operation_type'); // bom, cost_run, quote
            $table->string('status')->default('draft');
            
            // تفاصيل المنتج
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->decimal('target_quantity', 10, 2);
            
            // BOM - Bill of Materials
            $table->json('bom_items')->nullable(); // قائمة المواد الأولية
            $table->json('operations')->nullable(); // العمليات الإنتاجية
            $table->json('overhead_costs')->nullable(); // التكاليف العامة
            
            // التكاليف
            $table->decimal('material_cost', 10, 2)->default(0);
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->decimal('overhead_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('unit_cost', 10, 2)->default(0);
            
            // الجدول الزمني
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
        });

        // جدول عمليات التخليص الجمركي
        Schema::create('customs_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->nullable()->constrained('users');
            
            $table->string('operation_type'); // clearance, valuation, inspection
            $table->string('status')->default('pending'); // pending, under_review, approved, rejected
            
            // معلومات الشحنة
            $table->string('declaration_number')->unique()->nullable();
            $table->string('bill_of_lading')->nullable();
            $table->date('declaration_date')->nullable();
            
            // التفاصيل الجمركية
            $table->string('hs_code');
            $table->string('product_description');
            $table->decimal('declared_value', 10, 2);
            $table->decimal('customs_value', 10, 2)->nullable();
            
            // الرسوم
            $table->decimal('customs_duty', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);
            $table->decimal('other_fees', 10, 2)->default(0);
            $table->decimal('total_fees', 10, 2)->default(0);
            
            // المستندات والملاحظات
            $table->json('required_documents')->nullable();
            $table->json('uploaded_documents')->nullable();
            $table->text('broker_notes')->nullable();
            $table->text('customs_notes')->nullable();
            
            // التواريخ
            $table->date('submission_date')->nullable();
            $table->date('approval_date')->nullable();
            $table->date('clearance_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'declaration_date']);
            $table->index('broker_id');
        });

        // جدول عمليات الحاويات
        Schema::create('container_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('operation_type'); // booking, tracking, delivery
            $table->string('status')->default('pending');
            
            // تفاصيل الحاوية
            $table->string('container_number')->nullable();
            $table->string('container_type'); // 20ft, 40ft, 40ft_hc, reefer
            $table->string('seal_number')->nullable();
            
            // معلومات الشحن
            $table->string('shipping_line')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('voyage_number')->nullable();
            
            // المنافذ
            $table->string('loading_port');
            $table->string('discharge_port');
            $table->date('etd')->nullable(); // Expected Time of Departure
            $table->date('eta')->nullable(); // Expected Time of Arrival
            $table->date('actual_departure')->nullable();
            $table->date('actual_arrival')->nullable();
            
            // التكاليف
            $table->decimal('freight_cost', 10, 2)->nullable();
            $table->decimal('handling_charges', 10, 2)->nullable();
            $table->decimal('detention_charges', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->nullable();
            
            // Tracking
            $table->json('tracking_history')->nullable();
            $table->string('current_location')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('container_number');
            $table->index(['user_id', 'status']);
        });

        // جدول عمليات الوكلاء
        Schema::create('agent_operations_detailed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained();
            
            $table->string('operation_type'); // shipping, brand_agency, customs, other
            $table->string('status')->default('pending');
            
            // تفاصيل العملية
            $table->string('operation_code')->unique();
            $table->string('client_name')->nullable();
            $table->text('service_description');
            
            // المعلومات المالية
            $table->decimal('service_value', 10, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0); // نسبة العمولة
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('net_income', 10, 2)->default(0);
            
            // التواريخ
            $table->date('contract_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('completion_date')->nullable();
            
            // التقييم والأداء
            $table->integer('client_rating')->nullable(); // 1-5
            $table->text('client_feedback')->nullable();
            
            // معلومات إضافية
            $table->json('operation_details')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['agent_id', 'status']);
            $table->index('operation_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_operations_detailed');
        Schema::dropIfExists('container_operations');
        Schema::dropIfExists('customs_operations');
        Schema::dropIfExists('manufacturing_operations');
        Schema::dropIfExists('export_operations_detailed');
        Schema::dropIfExists('import_operations');
        Schema::dropIfExists('dashboard_kpis');
    }
};
