<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messages;

    public $isAllAudience;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $messages, bool $isAllAudience = false)
    {
        $this->messages = $messages;
        $this->isAll = $isAllAudience;
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
