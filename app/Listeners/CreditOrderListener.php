<?php

namespace App\Listeners;

use App\Events\CreditOrder;
use App\Events\SendNotification;
use App\Models\user\user;
use App\Models\System\Setting;

class CreditOrderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param CreditOrder $event
     * @throws \Exception
     */
    public function handle(CreditOrder $event)
    {
        $params = $event->params;
        $setting = setting(1);
        if ($setting->credit_order) {
            $credit_order = $setting->credit_order;
            if (! $credit_order) {
                throw new \Exception('管理员还没配置参数');
            }
            $user = User::query()->find($params['user_id']); //直推

            if ($user) {
                creditOrderAdd($user, $credit_order['order_commission1_credit1'], 'credit1', '直推余额增加', 18); //余额
                creditOrderAdd($user, $credit_order['order_commission1_credit2'], 'credit2', '直推积分增加', 17); //积分
                creditOrderAdd($user, $credit_order['order_commission1_credit3'], 'credit3', '直推成长值增加', 19); //成长值

                $user['message'] = '订单余额收益增加'.$credit_order['order_commission1_credit1'].',订单积分增加'.$credit_order['order_commission1_credit2'].',订单成长值增加'.$credit_order['order_commission1_credit3'];
                event(new SendNotification($user->toArray()));
            }

            if ($user && $user['inviter_id'] != null) {
                $user_inviter = User::query()->find($user['inviter_id']); //上级
                creditOrderAdd($user_inviter, $credit_order['order_commission2_credit1'], 'credit1', '上级余额增加', 18); //余额
                creditOrderAdd($user_inviter, $credit_order['order_commission2_credit2'], 'credit2', '上级积分增加', 17); //积分
                creditOrderAdd($user_inviter, $credit_order['order_commission2_credit3'], 'credit3', '上级成长值增加', 19); //成长值

                $user_inviter['message'] = '订单余额收益增加'.$credit_order['order_commission2_credit1'].',订单积分增加'.$credit_order['order_commission2_credit2'].',订单成长值增加'.$credit_order['order_commission2_credit3'];
                event(new SendNotification($user_inviter->toArray()));
            }
            if ($user && $user['group_id'] != null) {
                $user_group_id = User::query()->find($user['group_id']); //当前组长
                creditOrderAdd($user_group_id, $credit_order['order_group1_credit1'], 'credit1', '组长余额增加', 18); //余额
                creditOrderAdd($user_group_id, $credit_order['order_group1_credit2'], 'credit2', '组长积分增加', 17); //积分
                creditOrderAdd($user_group_id, $credit_order['order_group1_credit3'], 'credit3', '组长成长值增加', 19); //成长值

                $user_group_id['message'] = '订单余额收益增加'.$credit_order['order_group1_credit1'].'订单积分增加'.$credit_order['order_group1_credit2'].',订单成长值增加'.$credit_order['order_group1_credit3'];
                event(new SendNotification($user_group_id->toArray()));
            }
            if ($user && $user['oldgroup_id'] != null) {
                $user_oldgroup_id = User::query()->find($user['oldgroup_id']); //原组长
                creditOrderAdd($user_oldgroup_id, $credit_order['order_group2_credit1'], 'credit1', '原组长余额增加', 18); //余额
                creditOrderAdd($user_oldgroup_id, $credit_order['order_group2_credit2'], 'credit2', '原组长积分增加', 17); //积分
                creditOrderAdd($user_oldgroup_id, $credit_order['order_group2_credit3'], 'credit3', '原组长成长值增加', 19);//成长值

                $user_oldgroup_id['message'] = '订单余额收益增加'.$credit_order['order_group2_credit1'].',订单积分增加'.$credit_order['order_group2_credit2'].',订单成长值增加'.$credit_order['order_group2_credit3'];
                event(new SendNotification($user_oldgroup_id->toArray()));
            }
        }
    }
}
