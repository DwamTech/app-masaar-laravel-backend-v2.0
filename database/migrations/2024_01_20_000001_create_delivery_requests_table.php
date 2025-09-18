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
        Schema::create('delivery_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Trip details
            $table->enum('trip_type', ['one_way', 'round_trip', 'multiple_destinations'])->default('one_way');
            $table->datetime('delivery_time');
            
            // Status and pricing
            $table->enum('status', [
                'pending_offers',
                'accepted_waiting_driver', 
                'driver_arrived',
                'trip_started',
                'trip_completed',
                'cancelled',
                'rejected'
            ])->default('pending_offers');
            
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('agreed_price', 10, 2)->nullable();
            
            // Car and trip details
            $table->enum('car_category', ['economy', 'comfort', 'premium', 'van'])->default('economy');
            $table->integer('estimated_duration')->nullable()->comment('Duration in minutes');
            
            // Payment
            $table->enum('payment_method', ['cash', 'bank_transfer', 'card'])->default('cash');
            
            // Additional fields
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Timestamps for different statuses
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('driver_arrived_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['client_id', 'status']);
            $table->index(['driver_id', 'status']);
            $table->index('delivery_time');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_requests');
    }
};