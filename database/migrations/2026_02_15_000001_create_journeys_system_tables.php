<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pay-per-Journey System Migration
 * 
 * جماركي - الدفع مقابل الوظيفة (Journey-based Payment)
 * Innovation: Pay per operation/journey, not by time/subscription
 * Currency: EGP only via Paymob
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ========================================
        // 1. JOURNEYS TABLE (العمليات/الرحلات)
        // ========================================
        Schema::create('journeys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('User (null for guest)');
            
            // Journey Identification
            $table->string('operation_code', 50)->unique()->comment('Unique journey code sent to customer');
            $table->string('guest_id', 100)->nullable()->index()->comment('Guest identifier before login');
            
            // Status Management
            $table->enum('status', [
                'draft',            // مسودة - قيد الإنشاء
                'pending_payment',  // انتظار الدفع
                'active',           // نشط - تم الدفع
                'completed',        // مكتمل
                'cancelled',        // ملغى
                'refunded'          // مسترد
            ])->default('draft')->index();
            
            // Financial Data (EGP Only)
            $table->string('currency', 3)->default('EGP')->comment('Always EGP');
            $table->decimal('service_total', 12, 2)->default(0)->comment('مجموع رسوم الخدمات (provider fees)');
            $table->decimal('platform_total', 12, 2)->default(0)->comment('مجموع اشتراك جماركي (platform fees)');
            $table->decimal('grand_total', 12, 2)->default(0)->comment('الإجمالي الكلي');
            
            // Notification Preferences
            $table->enum('notify_via', ['email', 'sms', 'both'])->default('email');
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 20)->nullable();
            
            // Metadata
            $table->string('journey_type', 50)->nullable()->comment('customs|shipping|manufacturing|etc');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable()->comment('Additional flexible data');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('created_at');
        });

        // ========================================
        // 2. JOURNEY_ITEMS TABLE (خدمات الرحلة)
        // ========================================
        Schema::create('journey_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->onDelete('cascade');
            
            // Service Identification
            $table->string('service_key', 100)->comment('Unique key: customs_clearance, broker_service, etc');
            $table->string('service_name', 255)->comment('Display name');
            $table->text('service_description')->nullable();
            
            // Pricing
            $table->decimal('provider_fee', 12, 2)->default(0)->comment('Service provider fee (مستخلص/وكيل)');
            $table->decimal('platform_fee', 12, 2)->default(0)->comment('Gamarky platform fee');
            $table->decimal('item_total', 12, 2)->default(0)->comment('provider_fee + platform_fee');
            
            // Free Services (for preview/reference)
            $table->boolean('is_free')->default(false)->comment('Free service (pricing reference only)');
            $table->string('free_reason')->nullable()->comment('Why free: preview|trial|promotion');
            
            // Status
            $table->enum('status', [
                'selected',   // محدد - في السلة
                'paid',       // مدفوع
                'cancelled'   // ملغى
            ])->default('selected');
            
            // Metadata
            $table->json('service_params')->nullable()->comment('Service-specific parameters');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['journey_id', 'status']);
            $table->index('service_key');
        });

        // ========================================
        // 3. PAYMENTS TABLE (معاملات الدفع)
        // ========================================
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Payment Gateway Info
            $table->string('provider', 50)->default('paymob')->comment('Payment provider');
            $table->string('provider_reference', 255)->nullable()->unique()->comment('Paymob transaction/order ID');
            $table->string('provider_payment_key', 255)->nullable()->comment('Paymob payment key/token');
            
            // Amount
            $table->decimal('amount_egp', 12, 2)->comment('Amount in EGP');
            $table->string('currency', 3)->default('EGP');
            
            // Status
            $table->enum('status', [
                'initiated',   // تم إنشاء النية
                'pending',     // انتظار العميل
                'paid',        // تم الدفع
                'failed',      // فشل
                'refunded',    // مسترد
                'cancelled'    // ملغى
            ])->default('initiated')->index();
            
            // Payment Method (from Paymob callback)
            $table->string('method', 50)->nullable()->comment('card|wallet|kiosk|bank_transfer');
            $table->string('method_details', 255)->nullable()->comment('Card last 4, wallet provider, etc');
            
            // Webhook & Callback Data
            $table->json('raw_payload')->nullable()->comment('Full webhook payload from Paymob');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            
            // Idempotency & Security
            $table->string('idempotency_key', 100)->nullable()->unique()->comment('Prevent duplicate processing');
            $table->boolean('webhook_verified')->default(false)->comment('HMAC verification passed');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['journey_id', 'status']);
            $table->index('provider_reference');
            $table->index(['status', 'created_at']);
        });

        // ========================================
        // 4. ENTITLEMENTS TABLE (الاستحقاقات)
        // ========================================
        // Alternative: Could reuse subscriptions table, but creating dedicated one for clarity
        Schema::create('journey_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journey_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Entitlement Status
            $table->boolean('active')->default(false)->index();
            $table->timestamp('activated_at')->nullable()->comment('When journey became active (payment success)');
            $table->timestamp('expires_at')->nullable()->comment('Journey completion or cancellation');
            
            // Usage Tracking (optional - for analytics)
            $table->integer('services_accessed')->default(0)->comment('How many services customer used');
            $table->timestamp('last_accessed_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'active']);
            $table->index(['journey_id', 'active']);
        });

        // ========================================
        // 5. INCOMING PAYMOB WEBHOOKS LOG
        // ========================================
        // Note: incoming_webhook_logs already exists, we'll reuse it
        // If not, uncomment below:
        /*
        if (!Schema::hasTable('incoming_webhook_logs')) {
            Schema::create('incoming_webhook_logs', function (Blueprint $table) {
                $table->id();
                $table->string('source', 50)->default('paymob')->index();
                $table->string('event_type', 100)->nullable()->index();
                $table->text('payload')->nullable();
                $table->string('signature', 255)->nullable();
                $table->boolean('verified')->default(false)->index();
                $table->boolean('processed')->default(false)->index();
                $table->text('processing_notes')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                
                $table->index('created_at');
            });
        }
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journey_entitlements');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('journey_items');
        Schema::dropIfExists('journeys');
        // Don't drop incoming_webhook_logs if it's shared
    }
};
