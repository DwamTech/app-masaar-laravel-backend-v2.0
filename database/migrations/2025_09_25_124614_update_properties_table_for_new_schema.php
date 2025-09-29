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
            if (!Schema::hasColumn('properties', 'title')) {
                $table->string('title')->after('id');
            }
            if (!Schema::hasColumn('properties', 'ownership_type')) {
                $table->enum('ownership_type', ['freehold', 'leasehold', 'usufruct'])->after('title');
            }
            if (!Schema::hasColumn('properties', 'property_price')) {
                $table->decimal('property_price', 15, 2)->after('ownership_type');
            }
            if (!Schema::hasColumn('properties', 'down_payment')) {
                $table->decimal('down_payment', 15, 2)->nullable()->after('property_price');
            }
            if (!Schema::hasColumn('properties', 'property_code')) {
                $table->string('property_code')->nullable()->after('down_payment');
            }
            if (!Schema::hasColumn('properties', 'view_count')) {
                $table->integer('view_count')->default(0)->after('property_code');
            }
            if (!Schema::hasColumn('properties', 'advertiser_type')) {
                $table->enum('advertiser_type', ['owner', 'broker', 'developer'])->after('view_count');
            }
            if (!Schema::hasColumn('properties', 'contact_info')) {
                $table->json('contact_info')->after('advertiser_type');
            }
            if (!Schema::hasColumn('properties', 'location')) {
                $table->json('location')->after('contact_info');
            }
            if (Schema::hasColumn('properties', 'bedrooms')) {
                $table->integer('bedrooms')->change();
            }
            if (Schema::hasColumn('properties', 'bathrooms')) {
                $table->integer('bathrooms')->change();
            }
            if (!Schema::hasColumn('properties', 'size_in_sqm')) {
                $table->decimal('size_in_sqm', 10, 2)->after('bathrooms');
            }
            if (!Schema::hasColumn('properties', 'finishing_level')) {
                $table->enum('finishing_level', ['fully_finished', 'semi_finished', 'core_and_shell'])->nullable()->after('size_in_sqm');
            }
            if (!Schema::hasColumn('properties', 'floor_number')) {
                $table->integer('floor_number')->nullable()->after('finishing_level');
            }
            if (!Schema::hasColumn('properties', 'overlooking')) {
                $table->string('overlooking')->nullable()->after('floor_number');
            }
            if (!Schema::hasColumn('properties', 'year_built')) {
                $table->integer('year_built')->nullable()->after('overlooking');
            }
            if (!Schema::hasColumn('properties', 'price_per_square_meter')) {
                $table->decimal('price_per_square_meter', 10, 2)->nullable()->after('year_built');
            }
            if (!Schema::hasColumn('properties', 'property_status')) {
                $table->enum('property_status', ['available', 'sold', 'rented'])->default('available')->after('payment_method');
            }
            if (!Schema::hasColumn('properties', 'developer_name')) {
                $table->string('developer_name')->nullable()->after('property_status');
            }
            if (!Schema::hasColumn('properties', 'logo_url')) {
                $table->string('logo_url')->nullable()->after('developer_name');
            }
            if (!Schema::hasColumn('properties', 'features')) {
                $table->json('features')->nullable()->after('logo_url');
            }
            if (!Schema::hasColumn('properties', 'amenities')) {
                $table->json('amenities')->nullable()->after('features');
            }
            if (!Schema::hasColumn('properties', 'main_image')) {
                $table->string('main_image')->after('amenities');
            }
            if (!Schema::hasColumn('properties', 'gallery_image_urls')) {
                $table->json('gallery_image_urls')->nullable()->after('main_image');
            }
            if (!Schema::hasColumn('properties', 'property_type')) {
                $table->enum('property_type', ['apartment', 'villa', 'townhouse', 'office', 'shop'])->after('gallery_image_urls');
            }
            if (!Schema::hasColumn('properties', 'readiness_status')) {
                $table->enum('readiness_status', ['ready_to_move', 'under_construction', 'off_plan'])->nullable()->after('property_type');
            }
            if (!Schema::hasColumn('properties', 'currency')) {
                $table->string('currency', 3)->default('EGP')->after('readiness_status');
            }
            if (!Schema::hasColumn('properties', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('currency');
            }
            if (Schema::hasColumn('properties', 'type') && !Schema::hasColumn('properties', 'old_type')) {
                $table->renameColumn('type', 'old_type');
            }
            if (Schema::hasColumn('properties', 'price') && !Schema::hasColumn('properties', 'old_price')) {
                $table->renameColumn('price', 'old_price');
            }
            if (Schema::hasColumn('properties', 'image_url') && !Schema::hasColumn('properties', 'old_image_url')) {
                $table->renameColumn('image_url', 'old_image_url');
            }
            if (Schema::hasColumn('properties', 'view') && !Schema::hasColumn('properties', 'old_view')) {
                $table->renameColumn('view', 'old_view');
            }
            if (Schema::hasColumn('properties', 'area') && !Schema::hasColumn('properties', 'old_area')) {
                $table->renameColumn('area', 'old_area');
            }
        });

        if (Schema::hasColumn('properties', 'property_code')) {
            foreach (\App\Models\Property::whereNull('property_code')->orWhere('property_code', '')->cursor() as $property) {
                $property->update(['property_code' => 'PROP-' . strtoupper(uniqid())]);
            }
        }

        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'property_code')) {
                $table->string('property_code')->nullable(false)->unique()->change();
            }
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
