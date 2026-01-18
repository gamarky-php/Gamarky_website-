<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('container_bookings', function (Blueprint $table) {
            // إضافة shipper_id
            $table->unsignedBigInteger('shipper_id')->nullable()->after('user_id')->index();
            
            // Foreign key إذا كان جدول users موجود
            if (Schema::hasTable('users')) {
                $table->foreign('shipper_id')->references('id')->on('users')->onDelete('set null');
            }
        });

        // ✅ Backfill ذكي: نسخ البيانات من أول عمود موجود
        $sourceColumn = null;
        if (Schema::hasColumn('container_bookings', 'user_id')) {
            $sourceColumn = 'user_id';
        } elseif (Schema::hasColumn('container_bookings', 'requester_id')) {
            $sourceColumn = 'requester_id';
        } elseif (Schema::hasColumn('container_bookings', 'customer_id')) {
            $sourceColumn = 'customer_id';
        }

        if ($sourceColumn) {
            DB::table('container_bookings')
                ->whereNotNull($sourceColumn)
                ->whereNull('shipper_id')
                ->update(['shipper_id' => DB::raw($sourceColumn)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('container_bookings', function (Blueprint $table) {
            // حذف foreign key أولاً إذا كان موجود
            if (Schema::hasColumn('container_bookings', 'shipper_id')) {
                $table->dropForeign(['shipper_id']);
                $table->dropColumn('shipper_id');
            }
        });
    }
};
