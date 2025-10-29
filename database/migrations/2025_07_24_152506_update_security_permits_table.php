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
        Schema::table('security_permits', function (Blueprint $table) {
            // إضافة الحقول الجديدة
            $table->foreignId('country_id')->nullable()->after('coming_from')->constrained('countries')->onDelete('set null');
            $table->foreignId('nationality_id')->nullable()->after('nationality')->constrained('nationalities')->onDelete('set null');
            
            // تحديث حقل الحالة
            $table->enum('status', ['new', 'pending', 'approved', 'rejected', 'expired'])->default('new')->change();
            
            // إضافة حقول جديدة
            $table->json('residence_images')->nullable()->after('other_document_image')->comment('صور الإقامة متعددة');
            $table->enum('payment_method', ['credit_card', 'digital_wallet'])->nullable()->after('residence_images');
            $table->decimal('individual_fee', 10, 2)->nullable()->after('payment_method')->comment('رسوم الفرد الواحد');
            $table->decimal('total_amount', 10, 2)->nullable()->after('individual_fee')->comment('المبلغ الإجمالي');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('total_amount');
            $table->string('payment_reference')->nullable()->after('payment_status')->comment('مرجع الدفع');
            $table->timestamp('processed_at')->nullable()->after('payment_reference')->comment('تاريخ المعالجة');
            $table->text('admin_notes')->nullable()->after('notes')->comment('ملاحظات الإدارة');
            
            // إضافة فهارس
            $table->index(['status', 'created_at']);
            $table->index(['payment_status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_permits', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['nationality_id']);
            $table->dropColumn([
                'country_id',
                'nationality_id',
                'residence_images',
                'payment_method',
                'individual_fee',
                'total_amount',
                'payment_status',
                'payment_reference',
                'processed_at',
                'admin_notes'
            ]);
        });
    }
};