<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryDestination extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_request_id',
        'order',
        'location_name',
        'latitude',
        'longitude',
        'address',
        'contact_name',
        'contact_phone',
        'notes',
        'is_pickup_point',
        'is_dropoff_point'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'order' => 'integer',
        'is_pickup_point' => 'boolean',
        'is_dropoff_point' => 'boolean'
    ];

    /**
     * Get the delivery request that owns this destination
     */
    public function deliveryRequest(): BelongsTo
    {
        return $this->belongsTo(DeliveryRequest::class);
    }

    /**
     * Scope to get pickup points
     */
    public function scopePickupPoints($query)
    {
        return $query->where('is_pickup_point', true);
    }

    /**
     * Scope to get dropoff points
     */
    public function scopeDropoffPoints($query)
    {
        return $query->where('is_dropoff_point', true);
    }

    /**
     * Get formatted location string
     */
    public function getFormattedLocationAttribute(): string
    {
        return $this->location_name . ($this->address ? ' - ' . $this->address : '');
    }

    /**
     * Get coordinates as array
     */
    public function getCoordinatesAttribute(): array
    {
        return [
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude
        ];
    }
}