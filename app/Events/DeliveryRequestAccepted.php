<?php

namespace App\Events;

use App\Models\DeliveryRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryRequestAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public DeliveryRequest $deliveryRequest;

    public function __construct(DeliveryRequest $deliveryRequest)
    {
        $this->deliveryRequest = $deliveryRequest;
    }

    public function broadcastOn(): PrivateChannel
    {
        // بث على قناة المستخدم الخاصة بالسائق المقبول
        return new PrivateChannel('user.' . $this->deliveryRequest->driver_id);
    }

    public function broadcastAs(): string
    {
        return 'delivery.request.accepted';
    }

    public function broadcastWith(): array
    {
        // إرسال الطلب المحدث مع العلاقات الضرورية للسائق
        $fresh = $this->deliveryRequest->fresh(['driver', 'destinations']);
        return [
            'delivery_request' => $fresh ? $fresh->toArray() : $this->deliveryRequest->toArray(),
        ];
    }
}