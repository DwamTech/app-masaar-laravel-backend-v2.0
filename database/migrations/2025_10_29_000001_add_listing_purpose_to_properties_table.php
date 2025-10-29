<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'listing_purpose')) {
                $table->enum('listing_purpose', ['sale', 'rent'])->default('sale')->after('property_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'listing_purpose')) {
                $table->dropColumn('listing_purpose');
            }
        });
    }
};