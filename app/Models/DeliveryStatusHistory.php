<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_request_id',
        'status',
        'changed_by',
        'note',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Get the delivery request this history belongs to
     */
    public function deliveryRequest(): BelongsTo
    {
        return $this->belongsTo(DeliveryRequest::class);
    }

    /**
     * Get the user who changed the status
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get formatted status name
     */
    public function getFormattedStatusAttribute(): string
    {
        $statuses = DeliveryRequest::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }
}