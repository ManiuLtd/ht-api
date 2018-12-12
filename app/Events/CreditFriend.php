<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreditFriend
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $params;

    /**
     * @var int
     */
    public $type;

    /**
     * CreditOrderFriend constructor.
     * @param array $params
     * @param int $type   1.代表订单 2. 代表粉丝
     */
    public function __construct(array $params,int $type)
    {
        $this->params = $params;
        $this->type   = $type;
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
