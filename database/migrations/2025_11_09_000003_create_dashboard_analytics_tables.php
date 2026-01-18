<?php

/**
 * Dashboard Analytics & Reports Migration
 * 
 * Purpose: جداول التحليلات والتقارير المتقدمة
 * Dependencies: users, dashboard_kpis tables
 * 
 * Features:
 * - Real-time Analytics
 * - Custom Reports
 * - Data Exports
 * - Scheduled Reports
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول التقارير المحفوظة
        Schema::create('dashboard_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // اسم التقرير
            $table->string('type'); // kpi, financial, operational, custom
            $table->string('section')->nullable(); // import, export, etc.
            
            // معايير التقرير
            $table->json('filters'); // المعايير المستخدمة
            $table->json('columns'); // الأعمدة المختارة
            $table->json('grouping')->nullable(); // التجميع
            $table->json('sorting')->nullable(); // الترتيب
            
            // الجدولة
            $table->boolean('is_scheduled')->default(false);
            $table->enum('schedule_frequency', ['daily', 'weekly', 'monthly'])->nullable();
            $table->time('schedule_time')->nullable();
            $table->json('recipients')->nullable(); // emails
            
            // الحالة
            $table->boolean('is_public')->default(false); // مشاركة مع مستخدمين آخرين
            $table->boolean('is_favorite')->default(false);
            
            $table->timestamps();
            
            $table->index(['user_id', 'type']);
        });

        // جدول البيانات المصدّرة
        Schema::create('data_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('report_id')->nullable()->constrained('dashboard_reports')->nullOnDelete();
            
            $table->string('name'); // اسم التصدير
            $table->string('format'); // excel, csv, pdf, json
            
            // معلومات البيانات
            $table->string('data_source'); // import_operations, export_operations, etc.
            $table->json('filters')->nullable();
            $table->integer('total_records')->default(0);
            
            // الملف
            $table->string('file_path')->nullable();
            $table->integer('file_size_bytes')->nullable();
            
            // الحالة
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('progress_percentage')->default(0);
            $table->text('error_message')->nullable();
            
            // التواريخ
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // صلاحية الملف
            
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });

        // جدول لوحات المعلومات المخصصة (Custom Dashboards)
        Schema::create('custom_dashboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('name');
            $table->text('description')->nullable();
            
            // التخطيط (Layout)
            $table->json('layout'); // Grid layout configuration
            $table->json('widgets'); // Widget configurations
            
            // الإعدادات
            $table->boolean('is_default')->default(false);
            $table->boolean('is_shared')->default(false);
            $table->json('shared_with')->nullable(); // user IDs
            
            // الموضوع والتخصيص
            $table->enum('theme', ['light', 'dark', 'auto'])->default('auto');
            $table->json('color_scheme')->nullable();
            
            $table->timestamps();
            
            $table->index('user_id');
        });

        // جدول Widgets المتاحة
        Schema::create('dashboard_widgets', function (Blueprint $table) {
            $table->id();
            
            $table->string('code')->unique(); // kpi_card, chart_line, table_operations
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('category'); // kpi, chart, table, metric
            
            // إعدادات الـ Widget
            $table->string('component'); // اسم مكون Livewire
            $table->json('default_config'); // التكوين الافتراضي
            $table->json('available_options'); // الخيارات المتاحة
            
            // الأذونات
            $table->json('required_permissions')->nullable();
            $table->json('available_sections'); // [import, export, etc.]
            
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
        });

        // جدول إحصائيات الاستخدام (لتحسين الأداء)
        Schema::create('dashboard_usage_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->date('stat_date');
            
            // معلومات الجلسة
            $table->integer('total_sessions')->default(0);
            $table->integer('total_page_views')->default(0);
            $table->integer('avg_session_duration_seconds')->default(0);
            
            // الأقسام الأكثر استخدامًا
            $table->json('sections_visited')->nullable(); // {import: 10, export: 5}
            $table->json('widgets_interacted')->nullable();
            $table->json('reports_generated')->nullable();
            
            // نشاط العمليات
            $table->integer('operations_created')->default(0);
            $table->integer('operations_completed')->default(0);
            
            $table->timestamps();
            
            $table->unique(['user_id', 'stat_date']);
        });

        // جدول التنبيهات المخصصة (Custom Alerts)
        Schema::create('dashboard_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // اسم التنبيه
            $table->string('alert_type'); // threshold, anomaly, schedule
            
            // الشروط
            $table->string('metric'); // total_cost, pending_operations, etc.
            $table->string('operator'); // >, <, =, >=, <=
            $table->decimal('threshold_value', 15, 2);
            $table->string('section')->nullable(); // import, export, etc.
            
            // الإجراءات
            $table->json('notification_channels'); // [email, sms, database]
            $table->json('recipients')->nullable();
            
            // الحالة
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_triggered_at')->nullable();
            $table->integer('trigger_count')->default(0);
            
            // الجدولة
            $table->enum('check_frequency', ['realtime', 'hourly', 'daily'])->default('hourly');
            
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
        });

        // جدول سجل التنبيهات
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained('dashboard_alerts')->cascadeOnDelete();
            
            $table->decimal('actual_value', 15, 2);
            $table->decimal('threshold_value', 15, 2);
            
            $table->text('message');
            $table->json('context_data')->nullable();
            
            $table->boolean('was_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            
            $table->timestamps();
            
            $table->index('alert_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
        Schema::dropIfExists('dashboard_alerts');
        Schema::dropIfExists('dashboard_usage_stats');
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('custom_dashboards');
        Schema::dropIfExists('data_exports');
        Schema::dropIfExists('dashboard_reports');
    }
};
