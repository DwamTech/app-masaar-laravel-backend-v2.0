<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('car_service_orders', function (Blueprint $table) {
            // مصدر التأجير: شركة أو أفراد (يُشتق آلياً من car_rental_id)
            $table->enum('provider_type', ['office', 'driver'])->nullable()->after('car_rental_id');

            // بسائق أم لا
            $table->boolean('with_driver')->default(false)->after('order_type');

            // موقع التسليم
            $table->string('delivery_location')->nullable()->after('to_location');

            // نوع التأجير ومدة التأجير
            $table->enum('rental_period_type', ['daily', 'weekly', 'monthly'])->nullable()->after('payment_method');
            $table->unsignedInteger('rental_duration')->nullable()->after('rental_period_type');

            // موعد الحجز من وإلى
            $table->timestamp('rental_start_at')->nullable()->after('delivery_time');
            $table->timestamp('rental_end_at')->nullable()->after('rental_start_at');

            // موديل السيارة المختار في الطلب (اختياري)
            $table->string('car_model')->nullable()->after('car_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_service_orders', function (Blueprint $table) {
            $table->dropColumn([
                'provider_type',
                'with_driver',
                'delivery_location',
                'rental_period_type',
                'rental_duration',
                'rental_start_at',
                'rental_end_at',
                'car_model',
            ]);
        });
    }
};