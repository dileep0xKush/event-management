<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UserForceLogout implements ShouldBroadcast
{
    use SerializesModels;

    public $userId;
    public $sessionId;

    public function __construct($userId, $sessionId)
    {
        $this->userId = $userId;
        $this->sessionId = $sessionId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastWith()
    {
        return ['session_id' => $this->sessionId];
    }
}
