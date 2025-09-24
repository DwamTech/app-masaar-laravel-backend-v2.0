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
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->comment('اسم الجنسية بالعربية');
            $table->string('name_en')->comment('اسم الجنسية بالإنجليزية');
            $table->string('code', 3)->unique()->comment('كود الجنسية');
            $table->boolean('is_active')->default(true)->comment('حالة الجنسية');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nationalities');
    }
};