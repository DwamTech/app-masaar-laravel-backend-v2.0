<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // تعديل قيم ENUM إلى 'office','person'
        DB::statement("ALTER TABLE car_service_orders MODIFY provider_type ENUM('office','person') NULL");
    }

    public function down(): void
    {
        // إعادة القيم القديمة 'office','driver' إن لزم
        DB::statement("ALTER TABLE car_service_orders MODIFY provider_type ENUM('office','driver') NULL");
    }
};