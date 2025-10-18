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
        Schema::create('delivery_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_request_id')->constrained()->onDelete('cascade');
            
            // Order of destination in the trip
            $table->integer('order')->default(1);
            
            // Location details
            $table->string('location_name');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('address')->nullable();
            
            // Contact information
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            
            // Additional details
            $table->text('notes')->nullable();
            
            // Point type flags
            $table->boolean('is_pickup_point')->default(false);
            $table->boolean('is_dropoff_point')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['delivery_request_id', 'order']);
            $table->index('is_pickup_point');
            $table->index('is_dropoff_point');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_destinations');
    }
};