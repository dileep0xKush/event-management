<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;

class UserForceLogout implements ShouldBroadcast
{
    use SerializesModels;

    public $user_id;
    public $session_id;

    public function __construct($user_id, $session_id)
    {
        $this->user_id = $user_id;
        $this->session_id = $session_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("user.{$this->user_id}");
    }

    public function broadcastAs()
    {
        return 'UserForceLogout';
    }
}
