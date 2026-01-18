<?php

/**
 * Notifications System Migration
 * 
 * Purpose: نظام إشعارات متعدد القنوات
 * Features: Database, Email, SMS, In-App, Webhooks
 * Dependencies: users table
 * 
 * Notification Types:
 * - operation_status: تحديثات حالة العمليات
 * - payment_reminder: تذكيرات الدفع
 * - document_required: طلبات مستندات
 * - approval_needed: طلبات موافقة
 * - kpi_alert: تنبيهات مؤشرات الأداء
 * - system_update: تحديثات النظام
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول الإشعارات الأساسي (Laravel Notifications) - يتخطى إذا كان موجود
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type'); // Class name
                $table->morphs('notifiable'); // user_id, agent_id, etc.
                
                $table->text('data'); // JSON data
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // جدول قنوات الإشعارات المفعّلة لكل مستخدم
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('notification_type'); // operation_status, payment, etc.
            
            // القنوات المفعّلة
            $table->boolean('database_enabled')->default(true);
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('push_enabled')->default(false);
            
            // إعدادات إضافية
            $table->enum('frequency', ['instant', 'hourly', 'daily', 'weekly'])->default('instant');
            $table->time('preferred_time')->nullable(); // للإشعارات المجدولة
            
            $table->timestamps();
            
            $table->unique(['user_id', 'notification_type']);
        });

        // جدول Webhooks للتكامل مع أنظمة خارجية
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('name'); // اسم الـ Webhook
            $table->string('url'); // URL الاستدعاء
            $table->enum('method', ['POST', 'GET', 'PUT'])->default('POST');
            
            // الأحداث المشتركة
            $table->json('subscribed_events'); // ['import.created', 'export.completed']
            
            // الأمان
            $table->string('secret_key')->nullable(); // للتحقق من الطلبات
            $table->json('headers')->nullable(); // Headers إضافية
            
            // الحالة
            $table->boolean('is_active')->default(true);
            $table->integer('retry_count')->default(3);
            $table->integer('timeout_seconds')->default(30);
            
            // الإحصائيات
            $table->unsignedInteger('total_calls')->default(0);
            $table->unsignedInteger('successful_calls')->default(0);
            $table->unsignedInteger('failed_calls')->default(0);
            $table->timestamp('last_called_at')->nullable();
            $table->text('last_error')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'is_active']);
        });

        // جدول سجل استدعاءات Webhooks
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained()->cascadeOnDelete();
            
            $table->string('event'); // اسم الحدث
            $table->json('payload'); // البيانات المرسلة
            
            // معلومات الاستدعاء
            $table->enum('status', ['pending', 'success', 'failed', 'retrying'])->default('pending');
            $table->integer('http_status_code')->nullable();
            $table->text('response_body')->nullable();
            $table->text('error_message')->nullable();
            
            // التوقيت
            $table->integer('attempt_number')->default(1);
            $table->timestamp('sent_at')->nullable();
            $table->integer('response_time_ms')->nullable(); // وقت الاستجابة بالميلي ثانية
            
            $table->timestamps();
            
            $table->index(['webhook_id', 'status']);
            $table->index('created_at');
        });

        // جدول قوائم الانتظار للإشعارات المؤجلة
        Schema::create('notification_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('notification_type');
            $table->string('channel'); // email, sms, database
            $table->json('data');
            
            $table->enum('status', ['queued', 'processing', 'sent', 'failed'])->default('queued');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            
            $table->integer('retry_count')->default(0);
            $table->text('error_message')->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'scheduled_at']);
        });

        // جدول قوالب الإشعارات
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            
            $table->string('type')->unique(); // operation_status, payment_reminder
            $table->string('channel'); // email, sms, database
            
            // محتوى القالب
            $table->string('subject_ar')->nullable(); // للبريد الإلكتروني
            $table->text('body_ar');
            $table->string('subject_en')->nullable();
            $table->text('body_en');
            
            // المتغيرات المتاحة
            $table->json('available_variables'); // ['{user_name}', '{operation_id}']
            
            // إعدادات
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            
            $table->timestamps();
        });

        // جدول سجل إرسال الإشعارات (للتدقيق)
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('notification_type');
            $table->string('channel'); // database, email, sms
            
            $table->enum('status', ['sent', 'failed', 'bounced'])->default('sent');
            $table->text('content')->nullable();
            $table->text('error_message')->nullable();
            
            // معلومات إضافية
            $table->string('recipient')->nullable(); // email or phone
            $table->string('provider')->nullable(); // twilio, sendgrid, etc.
            $table->json('metadata')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('notification_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('notification_queue');
        Schema::dropIfExists('webhook_logs');
        Schema::dropIfExists('webhooks');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
};
