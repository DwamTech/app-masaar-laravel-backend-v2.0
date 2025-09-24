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
        Schema::create('security_permit_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('مفتاح الإعداد');
            $table->text('value')->comment('قيمة الإعداد');
            $table->string('type')->default('string')->comment('نوع البيانات: string, number, boolean, json');
            $table->string('description')->nullable()->comment('وصف الإعداد');
            $table->string('group')->default('general')->comment('مجموعة الإعدادات');
            $table->boolean('is_editable')->default(true)->comment('قابل للتعديل من لوحة التحكم');
            $table->timestamps();
            
            $table->index(['group', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_permit_settings');
    }
};