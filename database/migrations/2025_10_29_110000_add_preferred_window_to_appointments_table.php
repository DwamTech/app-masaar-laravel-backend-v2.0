<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'preferred_from')) {
                $table->dateTime('preferred_from')->after('provider_id');
            }
            if (!Schema::hasColumn('appointments', 'preferred_to')) {
                $table->dateTime('preferred_to')->after('preferred_from');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'preferred_from')) {
                $table->dropColumn('preferred_from');
            }
            if (Schema::hasColumn('appointments', 'preferred_to')) {
                $table->dropColumn('preferred_to');
            }
        });
    }
};