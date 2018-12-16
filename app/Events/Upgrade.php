<?php

namespace App\Events;

use App\Models\User\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class Upgrade
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $level;

    /**
     * Upgrade constructor.
     * @param User $user
     * @param $level
     */
    public function __construct($user, $level)
    {
        $this->user = $user;
        $this->level = $level;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
