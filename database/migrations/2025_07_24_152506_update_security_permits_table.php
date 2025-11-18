<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();
        Schema::table('security_permits', function (Blueprint $table) use ($driver) {
            // إضافة الحقول الجديدة بشكل آمن (لا تُنشأ إذا كانت موجودة)
            if (!Schema::hasColumn('security_permits', 'country_id')) {
                $table->foreignId('country_id')->nullable()->after('coming_from')->constrained('countries')->onDelete('set null');
            }
            if (!Schema::hasColumn('security_permits', 'nationality_id')) {
                $table->foreignId('nationality_id')->nullable()->after('nationality')->constrained('nationalities')->onDelete('set null');
            }

            // تحديث حقل الحالة (يبقى كما هو)
            if (Schema::hasColumn('security_permits', 'status') && $driver !== 'sqlite') {
                $table->enum('status', ['new', 'pending', 'approved', 'rejected', 'expired'])->default('new')->change();
            }

            // إضافة حقول جديدة بشكل آمن
            if (!Schema::hasColumn('security_permits', 'residence_images')) {
                $table->json('residence_images')->nullable()->after('other_document_image')->comment('صور الإقامة متعددة');
            }
            if (!Schema::hasColumn('security_permits', 'payment_method')) {
                $table->enum('payment_method', ['credit_card', 'digital_wallet'])->nullable()->after('residence_images');
            }
            if (!Schema::hasColumn('security_permits', 'individual_fee')) {
                $table->decimal('individual_fee', 10, 2)->nullable()->after('payment_method')->comment('رسوم الفرد الواحد');
            }
            if (!Schema::hasColumn('security_permits', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->after('individual_fee')->comment('المبلغ الإجمالي');
            }
            if (!Schema::hasColumn('security_permits', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending')->after('total_amount');
            }
            if (!Schema::hasColumn('security_permits', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_status')->comment('مرجع الدفع');
            }
            if (!Schema::hasColumn('security_permits', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('payment_reference')->comment('تاريخ المعالجة');
            }
            if (!Schema::hasColumn('security_permits', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes')->comment('ملاحظات الإدارة');
            }

            // إضافة فهارس بشكل آمن لتجنب ازدواجية الأسماء
            if ($driver !== 'sqlite') {
                $dbName = DB::getDatabaseName();
                $hasStatusCreatedIndex = DB::table('information_schema.statistics')
                    ->where('table_schema', $dbName)
                    ->where('table_name', 'security_permits')
                    ->where('index_name', 'security_permits_status_created_at_index')
                    ->exists();
                if (!$hasStatusCreatedIndex) {
                    $table->index(['status', 'created_at'], 'security_permits_status_created_at_index');
                }

                $hasPaymentStatusIndex = DB::table('information_schema.statistics')
                    ->where('table_schema', $dbName)
                    ->where('table_name', 'security_permits')
                    ->where('index_name', 'security_permits_payment_status_index')
                    ->exists();
                if (!$hasPaymentStatusIndex) {
                    $table->index(['payment_status'], 'security_permits_payment_status_index');
                }

                $hasUserStatusIndex = DB::table('information_schema.statistics')
                    ->where('table_schema', $dbName)
                    ->where('table_name', 'security_permits')
                    ->where('index_name', 'security_permits_user_id_status_index')
                    ->exists();
                if (!$hasUserStatusIndex) {
                    $table->index(['user_id', 'status'], 'security_permits_user_id_status_index');
                }
            } else {
                // في SQLite، ننشئ الفهارس مباشرة بدون التحقق من information_schema
                $table->index(['status', 'created_at'], 'security_permits_status_created_at_index');
                $table->index(['payment_status'], 'security_permits_payment_status_index');
                $table->index(['user_id', 'status'], 'security_permits_user_id_status_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('security_permits', function (Blueprint $table) {
            if (Schema::hasColumn('security_permits', 'country_id')) {
                $table->dropForeign(['country_id']);
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('security_permits', 'nationality_id')) {
                $table->dropForeign(['nationality_id']);
                $table->dropColumn('nationality_id');
            }
            if (Schema::hasColumn('security_permits', 'residence_images')) {
                $table->dropColumn('residence_images');
            }
            if (Schema::hasColumn('security_permits', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('security_permits', 'individual_fee')) {
                $table->dropColumn('individual_fee');
            }
            if (Schema::hasColumn('security_permits', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('security_permits', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
            if (Schema::hasColumn('security_permits', 'payment_reference')) {
                $table->dropColumn('payment_reference');
            }
            if (Schema::hasColumn('security_permits', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
            if (Schema::hasColumn('security_permits', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
        });
    }
};