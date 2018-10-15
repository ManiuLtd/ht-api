<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SendNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messages;

    public $isAllAudience;

    /**
     * SendNotification constructor.
     * @param array $messages
     * @param bool $isAllAudience
     */
    public function __construct(array $messages, bool $isAllAudience = false)
    {
        $this->messages = $messages;
        $this->isAllAudience = $isAllAudience;
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
