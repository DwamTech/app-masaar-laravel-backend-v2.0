<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if (Schema::hasTable('real_estate_individuals_details')) {
            Schema::table('real_estate_individuals_details', function (Blueprint $table) use ($driver) {
                if ($driver !== 'sqlite') {
                    if (Schema::hasColumn('real_estate_individuals_details', 'tax_card_front_image')) {
                        $table->string('tax_card_front_image')->nullable()->change();
                    }
                    if (Schema::hasColumn('real_estate_individuals_details', 'tax_card_back_image')) {
                        $table->string('tax_card_back_image')->nullable()->change();
                    }
                }
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        if (Schema::hasTable('real_estate_individuals_details')) {
            Schema::table('real_estate_individuals_details', function (Blueprint $table) use ($driver) {
                if ($driver !== 'sqlite') {
                    if (Schema::hasColumn('real_estate_individuals_details', 'tax_card_front_image')) {
                        $table->string('tax_card_front_image')->nullable(false)->change();
                    }
                    if (Schema::hasColumn('real_estate_individuals_details', 'tax_card_back_image')) {
                        $table->string('tax_card_back_image')->nullable(false)->change();
                    }
                }
            });
        }
    }
};