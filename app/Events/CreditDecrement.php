<?php

namespace App\Events;

use App\Models\Member\Member;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CreditDecrement
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 需要操作的会员.
     * @var Member
     */
    public $member;

    /**
     * 1积分 2余额.
     * @var
     */
    public $column;

    /**
     * 改变的积分或者余额数量.
     * @var float
     */
    public $credit;


    /**
     * 额外参数
     * @var array
     */
    public $extra;

    /**
     * CreditDecrement constructor.
     * @param Member $member 需要操作的会员
     * @param int $column 1积分 2余额 3经验
     * @param float $credit 改变的积分或者余额数量
     * @param array $extra 备注
     */
    public function __construct(Member $member, int $column, float $credit, array $extra)
    {
        $this->member = $member;
        $this->column = $column;
        $this->credit = $credit;
        $this->extra = $extra;
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
