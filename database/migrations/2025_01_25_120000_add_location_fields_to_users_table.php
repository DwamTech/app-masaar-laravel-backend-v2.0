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
        if (Schema::hasColumn('users', 'city')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable()->after('city');
                }
                if (!Schema::hasColumn('users', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
                }
                if (!Schema::hasColumn('users', 'current_address')) {
                    $table->string('current_address', 500)->nullable()->after('longitude');
                }
                if (!Schema::hasColumn('users', 'location_updated_at')) {
                    $table->timestamp('location_updated_at')->nullable()->after('current_address');
                }
                if (!Schema::hasColumn('users', 'location_sharing_enabled')) {
                    $table->boolean('location_sharing_enabled')->default(true)->after('location_updated_at');
                }
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'latitude')) {
                    $table->decimal('latitude', 10, 8)->nullable();
                }
                if (!Schema::hasColumn('users', 'longitude')) {
                    $table->decimal('longitude', 11, 8)->nullable();
                }
                if (!Schema::hasColumn('users', 'current_address')) {
                    $table->string('current_address', 500)->nullable();
                }
                if (!Schema::hasColumn('users', 'location_updated_at')) {
                    $table->timestamp('location_updated_at')->nullable();
                }
                if (!Schema::hasColumn('users', 'location_sharing_enabled')) {
                    $table->boolean('location_sharing_enabled')->default(true);
                }
            });
        }
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