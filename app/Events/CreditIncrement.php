<?php

namespace App\Events;

use App\Models\Member\Member;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreditIncrement
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
    public $type;

    /**
     * 改变的积分或者余额数量.
     * @var float
     */
    public $credit;

    /**
     * 备注.
     * @var string
     */
    public $remark;

    /**
     * 后端操作人ID，管理员手动修改积分或者余额使用.
     * @var int|null
     */
    public $operaterId;

    /**
     * CreditDecrement constructor.
     * @param Member $member 需要操作的会员
     * @param int $type 1积分 2余额
     * @param float $credit 改变的积分或者余额数量
     * @param string $remark 备注
     * @param int|null $operaterId 后端操作人ID，管理员手动修改积分或者余额使用
     */
    public function __construct(Member $member, int $type, float $credit, string $remark, int $operaterId = null)
    {
        $this->member = $member;
        $this->type = $type;
        $this->credit = $credit;
        $this->remark = $remark;
        $this->operaterId = $operaterId;
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
