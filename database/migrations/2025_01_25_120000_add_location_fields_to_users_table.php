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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('city');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('current_address', 500)->nullable()->after('longitude');
            $table->timestamp('location_updated_at')->nullable()->after('current_address');
            $table->boolean('location_sharing_enabled')->default(true)->after('location_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude', 
                'current_address',
                'location_updated_at',
                'location_sharing_enabled'
            ]);
        });
    }
};