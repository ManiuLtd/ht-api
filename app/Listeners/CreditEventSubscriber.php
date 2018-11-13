<?php

namespace App\Listeners;

use App\Events\MemberUpgrade;
use InvalidArgumentException;
use App\Events\CreditDecrement;
use App\Events\CreditIncrement;
use Illuminate\Support\Facades\DB;

class CreditEventSubscriber
{
    /**
     * @param CreditIncrement $event
     * @throws \Throwable
     */
    public function onCreditIncrement(CreditIncrement $event)
    {
        $this->updateCredit($event, true);
    }

    /**
     * @param CreditDecrement $event
     * @throws \Throwable
     */
    public function onCreditDecrement(CreditDecrement $event)
    {
        $this->updateCredit($event, false);
    }

    /**
     * 为订阅者注册监听器.
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\CreditIncrement',
            'App\Listeners\CreditEventSubscriber@onCreditIncrement'
        );

        $events->listen(
            'App\Events\CreditDecrement',
            'App\Listeners\CreditEventSubscriber@onCreditDecrement'
        );
    }

    /**
     * 改变积分并写入日志.
     * @param $event
     * @param bool $isIncrement 是否为增加
     * @throws \Throwable
     */
    protected function updateCredit($event, bool $isIncrement): void
    {
        //如果积分数组不符合规范
        if (! is_numeric($event->credit) || ! in_array($event->column, ['credit1', 'credit2', 'credit3'])) {
            throw  new InvalidArgumentException('修改基本所需要传入的参数格式错误');
        }

        //修改积分并添加操作日志
        DB::transaction(function () use ($event, $isIncrement) {
            if (! $isIncrement) {
                $event->user->decrement($event->column, $event->credit);
                if($event->column == 'credit3'){
                    event(new MemberUpgrade($this));
                }
            } else {
                $event->user->increment($event->column, $event->credit);
            }
            //需要插入的日志
            $insert = [
                'user_id' => $event->id,
                'operater_id' => $event->extra->operaterId ?? null,
                'credit' => $event->credit,
                'column' => $event->column,
                'type' => $event->type,
                'remark' => $event->extra->remark,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];

            db('user_credit_logs')->insert($insert);
        });
    }
}
