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
        Schema::create('delivery_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            
            // Offer details
            $table->decimal('offered_price', 10, 2);
            $table->integer('estimated_duration')->nullable()->comment('Duration in minutes');
            $table->text('offer_notes')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            
            // Timestamps for status changes
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['delivery_request_id', 'status']);
            $table->index(['driver_id', 'status']);
            $table->unique(['delivery_request_id', 'driver_id']); // One offer per driver per request
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_offers');
    }
};