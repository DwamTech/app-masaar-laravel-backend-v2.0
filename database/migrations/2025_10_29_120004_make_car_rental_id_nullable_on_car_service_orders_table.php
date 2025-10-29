<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // جعل العمود car_rental_id قابلًا ليكون NULL حتى يتم تعيينه وقت القبول
        DB::statement("ALTER TABLE car_service_orders MODIFY car_rental_id BIGINT UNSIGNED NULL");
    }

    public function down(): void
    {
        // إعادة العمود ليكون NOT NULL كما كان سابقًا
        DB::statement("ALTER TABLE car_service_orders MODIFY car_rental_id BIGINT UNSIGNED NOT NULL");
    }
};