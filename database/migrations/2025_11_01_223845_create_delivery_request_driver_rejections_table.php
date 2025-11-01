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
        if (!Schema::hasTable('delivery_request_driver_rejections')) {
            Schema::create('delivery_request_driver_rejections', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('delivery_request_id');
                $table->unsignedBigInteger('driver_id');
                $table->timestamps();

                $table->unique(['delivery_request_id', 'driver_id'], 'dr_req_driver_unique');
                $table->foreign('delivery_request_id')->references('id')->on('delivery_requests')->onDelete('cascade');
                $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_request_driver_rejections');
    }
};
