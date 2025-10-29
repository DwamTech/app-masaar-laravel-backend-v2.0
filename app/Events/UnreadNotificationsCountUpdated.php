<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnreadNotificationsCountUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $userId;
    public int $count;

    public function __construct(int $userId, int $count)
    {
        $this->userId = $userId;
        $this->count = $count;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.User.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'unread-count-updated';
    }

    public function broadcastWith(): array
    {
        return [
            'unread_count' => $this->count,
        ];
    }
}