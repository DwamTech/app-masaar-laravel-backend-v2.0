<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_request_id',
        'driver_id',
        'offered_price',
        'estimated_duration',
        'offer_notes',
        'status',
        'accepted_at',
        'rejected_at',
        'rejection_reason'
    ];

    protected $casts = [
        'offered_price' => 'decimal:2',
        'estimated_duration' => 'integer',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    /**
     * Get the delivery request this offer belongs to
     */
    public function deliveryRequest(): BelongsTo
    {
        return $this->belongsTo(DeliveryRequest::class);
    }

    /**
     * Get the driver who made this offer
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_ACCEPTED => 'مقبول',
            self::STATUS_REJECTED => 'مرفوض',
            self::STATUS_WITHDRAWN => 'مسحوب'
        ];
    }

    /**
     * Check if offer is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if offer is accepted
     */
    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    /**
     * Check if offer is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}