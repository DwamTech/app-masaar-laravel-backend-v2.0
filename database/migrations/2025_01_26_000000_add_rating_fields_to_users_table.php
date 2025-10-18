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
        if (Schema::hasColumn('users', 'push_notifications_enabled')) {
            Schema::table('users', function (Blueprint $table) {
                // إضافة حقول التقييم للسائقين مع ترتيب بعد push_notifications_enabled عند توفره
                if (!Schema::hasColumn('users', 'rating')) {
                    $table->decimal('rating', 3, 2)->default(0.00)->after('push_notifications_enabled');
                }
                if (!Schema::hasColumn('users', 'rating_count')) {
                    $table->unsignedInteger('rating_count')->default(0)->after('rating');
                }
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                // إضافة الحقول بدون after لتجنب خطأ عند غياب push_notifications_enabled
                if (!Schema::hasColumn('users', 'rating')) {
                    $table->decimal('rating', 3, 2)->default(0.00);
                }
                if (!Schema::hasColumn('users', 'rating_count')) {
                    $table->unsignedInteger('rating_count')->default(0);
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
                'rating',
                'rating_count'
            ]);
        });
    }
};