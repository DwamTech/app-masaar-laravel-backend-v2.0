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
        Schema::table('properties', function (Blueprint $table) {
            // إضافة الحقول الجديدة المطلوبة حسب JSON Schema
            $table->string('title')->after('id');
            $table->enum('ownership_type', ['freehold', 'leasehold', 'usufruct'])->after('title');
            $table->decimal('property_price', 15, 2)->after('ownership_type');
            $table->decimal('down_payment', 15, 2)->nullable()->after('property_price');
            $table->string('property_code')->nullable()->after('down_payment');
            $table->integer('view_count')->default(0)->after('property_code');
            $table->enum('advertiser_type', ['owner', 'broker', 'developer'])->after('view_count');
            
            // معلومات الاتصال (JSON)
            $table->json('contact_info')->after('advertiser_type');
            
            // معلومات الموقع (JSON)
            $table->json('location')->after('contact_info');
            
            // تفاصيل العقار
            $table->integer('bedrooms')->change(); // تأكد من أنه integer
            $table->integer('bathrooms')->change(); // تأكد من أنه integer
            $table->decimal('size_in_sqm', 10, 2)->after('bathrooms');
            $table->enum('finishing_level', ['fully_finished', 'semi_finished', 'core_and_shell'])->nullable()->after('size_in_sqm');
            $table->integer('floor_number')->nullable()->after('finishing_level');
            $table->string('overlooking')->nullable()->after('floor_number');
            $table->integer('year_built')->nullable()->after('overlooking');
            $table->decimal('price_per_square_meter', 10, 2)->nullable()->after('year_built');
            $table->enum('property_status', ['available', 'sold', 'rented'])->default('available')->after('payment_method');
            $table->string('developer_name')->nullable()->after('property_status');
            $table->string('logo_url')->nullable()->after('developer_name');
            
            // المميزات والخدمات (JSON Arrays)
            $table->json('features')->nullable()->after('logo_url');
            $table->json('amenities')->nullable()->after('features');
            
            // الصور
            $table->string('main_image')->after('amenities');
            $table->json('gallery_image_urls')->nullable()->after('main_image');
            
            // نوع العقار وحالة الجاهزية
            $table->enum('property_type', ['apartment', 'villa', 'townhouse', 'office', 'shop'])->after('gallery_image_urls');
            $table->enum('readiness_status', ['ready_to_move', 'under_construction', 'off_plan'])->nullable()->after('property_type');
            
            // العملة والمميز
            $table->string('currency', 3)->default('EGP')->after('readiness_status');
            $table->boolean('is_featured')->default(false)->after('currency');
            
            // إعادة تسمية الحقول الموجودة
            $table->renameColumn('type', 'old_type');
            $table->renameColumn('price', 'old_price');
            $table->renameColumn('image_url', 'old_image_url');
            $table->renameColumn('view', 'old_view');
            $table->renameColumn('area', 'old_area');
        });

        // Populate existing rows with a unique property_code
        foreach (\App\Models\Property::whereNull('property_code')->orWhere('property_code', '')->cursor() as $property) {
            $property->update(['property_code' => 'PROP-' . strtoupper(uniqid())]);
        }

        // Now, enforce the unique constraint and make it non-nullable
        Schema::table('properties', function (Blueprint $table) {
            $table->string('property_code')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // حذف الحقول الجديدة
            $table->dropColumn([
                'title', 'ownership_type', 'property_price', 'down_payment', 
                'property_code', 'view_count', 'advertiser_type', 'contact_info',
                'location', 'size_in_sqm', 'finishing_level', 'floor_number',
                'overlooking', 'year_built', 'price_per_square_meter', 
                'property_status', 'developer_name', 'logo_url', 'features',
                'amenities', 'main_image', 'gallery_image_urls', 'property_type',
                'readiness_status', 'currency', 'is_featured'
            ]);
            
            // إعادة تسمية الحقول القديمة
            $table->renameColumn('old_type', 'type');
            $table->renameColumn('old_price', 'price');
            $table->renameColumn('old_image_url', 'image_url');
            $table->renameColumn('old_view', 'view');
            $table->renameColumn('old_area', 'area');
        });
    }
};
