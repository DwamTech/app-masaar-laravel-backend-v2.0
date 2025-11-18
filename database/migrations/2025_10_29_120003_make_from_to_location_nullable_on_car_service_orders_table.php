<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            // تعديل الأعمدة ليصبحوا NULL غير مدعوم مباشرة في SQLite بدون إعادة بناء الجدول
            return;
        }
        DB::statement("ALTER TABLE car_service_orders MODIFY from_location VARCHAR(255) NULL");
        DB::statement("ALTER TABLE car_service_orders MODIFY to_location VARCHAR(255) NULL");
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'sqlite') {
            return;
        }
        DB::statement("ALTER TABLE car_service_orders MODIFY from_location VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE car_service_orders MODIFY to_location VARCHAR(255) NOT NULL");
    }
};