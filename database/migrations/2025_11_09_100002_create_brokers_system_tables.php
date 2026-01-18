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
        // Brokers table
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('country', 3); // ISO country code
            $table->json('ports')->nullable(); // array of port codes
            $table->json('activities')->nullable(); // ['customs', 'freight', 'warehousing']
            $table->unsignedSmallInteger('experience_years')->default(0);
            $table->decimal('score', 3, 2)->default(0.00); // 0.00 to 5.00
            $table->json('certifications')->nullable(); // ['ISO9001', 'AEO', etc.]
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('contact_person')->nullable();
            $table->timestamps();
            
            $table->index('country');
            $table->index('status');
            $table->index('score');
        });

        // Broker Documents table
        Schema::create('broker_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'license',
                'insurance',
                'certification',
                'tax_registration',
                'contract',
                'other'
            ])->default('other');
            $table->string('file_key'); // storage path/key
            $table->string('original_filename')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['broker_id', 'type']);
            $table->index('valid_until');
            $table->index('status');
        });

        // Broker Reviews table
        Schema::create('broker_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broker_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('source', ['site', 'client'])->default('client');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('comments')->nullable();
            $table->json('evidence_links')->nullable(); // supporting documents/images
            $table->json('criteria_scores')->nullable(); // ['communication' => 5, 'timeliness' => 4]
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            $table->index(['broker_id', 'status']);
            $table->index('rating');
            $table->index('source');
        });

        // Clearance Jobs table
        Schema::create('clearance_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('broker_id')->nullable()->constrained()->onDelete('set null');
            $table->string('shipment_ref')->unique();
            $table->string('bl_number')->nullable(); // Bill of Lading
            $table->json('stages')->nullable(); // [{stage: 'documents', status: 'completed', date: '2025-11-01'}]
            $table->unsignedSmallInteger('sla_days')->default(7); // Service Level Agreement
            $table->date('expected_clearance_date')->nullable();
            $table->date('actual_clearance_date')->nullable();
            $table->enum('status', [
                'pending',
                'documents_received',
                'under_review',
                'customs_processing',
                'payment_pending',
                'cleared',
                'released',
                'cancelled'
            ])->default('pending');
            $table->decimal('total_fees', 12, 2)->nullable();
            $table->string('currency', 3)->default('SAR');
            $table->json('fees_breakdown')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('shipment_ref');
            $table->index(['client_id', 'status']);
            $table->index(['broker_id', 'status']);
            $table->index('expected_clearance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clearance_jobs');
        Schema::dropIfExists('broker_reviews');
        Schema::dropIfExists('broker_documents');
        Schema::dropIfExists('brokers');
    }
};
