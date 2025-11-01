<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'driver_id',
        'trip_type',
        'delivery_time',
        'status',
        'price',
        'agreed_price',
        'car_category',
        'estimated_duration',
        'payment_method',
        'notes',
        'governorate',
        'rejection_reason',
        'accepted_at',
        'rejected_at',
        'started_at',
        'completed_at',
        'driver_arrived_at'
    ];

    protected $casts = [
        'delivery_time' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'driver_arrived_at' => 'datetime',
        'price' => 'decimal:2',
        'agreed_price' => 'decimal:2',
        'estimated_duration' => 'integer'
    ];

    // Trip types constants
    const TRIP_TYPE_ONE_WAY = 'one_way';
    const TRIP_TYPE_ROUND_TRIP = 'round_trip';
    const TRIP_TYPE_MULTIPLE_DESTINATIONS = 'multiple_destinations';

    // Status constants
    const STATUS_PENDING_OFFERS = 'pending_offers';
    const STATUS_ACCEPTED_WAITING_DRIVER = 'accepted_waiting_driver';
    const STATUS_DRIVER_ARRIVED = 'driver_arrived';
    const STATUS_TRIP_STARTED = 'trip_started';
    const STATUS_TRIP_COMPLETED = 'trip_completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    // Payment methods constants
    const PAYMENT_CASH = 'cash';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_CARD = 'card';

    // Car categories constants
    const CAR_CATEGORY_ECONOMY = 'economy';
    const CAR_CATEGORY_COMFORT = 'comfort';
    const CAR_CATEGORY_PREMIUM = 'premium';
    const CAR_CATEGORY_VAN = 'van';

    /**
     * Get the client who made the delivery request
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the driver assigned to the delivery request
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the destinations for this delivery request
     */
    public function destinations(): HasMany
    {
        return $this->hasMany(DeliveryDestination::class)->orderBy('order');
    }

    /**
     * Get the offers for this delivery request
     */
    public function offers(): HasMany
    {
        return $this->hasMany(DeliveryOffer::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the status history for this delivery request
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(DeliveryStatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get available trip types
     */
    public static function getTripTypes(): array
    {
        return [
            self::TRIP_TYPE_ONE_WAY => 'ذهاب فقط',
            self::TRIP_TYPE_ROUND_TRIP => 'ذهاب وعودة',
            self::TRIP_TYPE_MULTIPLE_DESTINATIONS => 'وجهات متعددة'
        ];
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING_OFFERS => 'في انتظار العروض',
            self::STATUS_ACCEPTED_WAITING_DRIVER => 'مقبول - انتظار السائق',
            self::STATUS_DRIVER_ARRIVED => 'وصل السائق',
            self::STATUS_TRIP_STARTED => 'بدأت الرحلة',
            self::STATUS_TRIP_COMPLETED => 'انتهت الرحلة',
            self::STATUS_CANCELLED => 'ملغي',
            self::STATUS_REJECTED => 'مرفوض'
        ];
    }

    /**
     * Get available payment methods
     */
    public static function getPaymentMethods(): array
    {
        return [
            self::PAYMENT_CASH => 'نقدي',
            self::PAYMENT_BANK_TRANSFER => 'تحويل بنكي',
            self::PAYMENT_CARD => 'بطاقة ائتمان'
        ];
    }

    /**
     * Get available car categories
     */
    public static function getCarCategories(): array
    {
        return [
            self::CAR_CATEGORY_ECONOMY => 'اقتصادي',
            self::CAR_CATEGORY_COMFORT => 'مريح',
            self::CAR_CATEGORY_PREMIUM => 'فاخر',
            self::CAR_CATEGORY_VAN => 'فان'
        ];
    }

    /**
     * Check if the request can receive offers
     */
    public function canReceiveOffers(): bool
    {
        return $this->status === self::STATUS_PENDING_OFFERS;
    }

    /**
     * Check if the request is in progress
     */
    public function isInProgress(): bool
    {
        return in_array($this->status, [
            self::STATUS_ACCEPTED_WAITING_DRIVER,
            self::STATUS_DRIVER_ARRIVED,
            self::STATUS_TRIP_STARTED
        ]);
    }

    /**
     * Check if the request is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, [
            self::STATUS_TRIP_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_REJECTED
        ]);
    }

    /**
     * Drivers who explicitly declined this request
     */
    public function driverRejections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DeliveryRequestDriverRejection::class);
    }
}
