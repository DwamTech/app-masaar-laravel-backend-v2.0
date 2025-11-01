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
        Schema::table('car_rental_offices_details', function (Blueprint $table) {
            $table->string('owner_id_front_image')->nullable();
            $table->string('owner_id_back_image')->nullable();
            $table->string('license_front_image')->nullable();
            $table->string('license_back_image')->nullable();
            $table->string('vat_front_image')->nullable();
            $table->string('vat_back_image')->nullable();
            $table->boolean('includes_vat')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_rental_offices_details', function (Blueprint $table) {
            $table->dropColumn([
                'owner_id_front_image',
                'owner_id_back_image',
                'license_front_image',
                'license_back_image',
                'vat_front_image',
                'vat_back_image',
                'includes_vat',
            ]);
        });
    }
};