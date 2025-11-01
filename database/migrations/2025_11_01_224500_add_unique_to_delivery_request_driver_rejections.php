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
        Schema::table('delivery_request_driver_rejections', function (Blueprint $table) {
            // Ensure composite unique index on (delivery_request_id, driver_id) with a short name
            $table->unique(['delivery_request_id', 'driver_id'], 'dr_req_driver_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_request_driver_rejections', function (Blueprint $table) {
            $table->dropUnique('dr_req_driver_unique');
        });
    }
};