<?php

namespace App\Listeners;

use App\Events\CreditOrder;
use App\Events\SendNotification;
use App\Models\User\Group;
use App\Models\user\user;

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


        $title = '退款订单';
        $messageCredit1 = '订单余额收益减少';
        $messageCredit2 = '订单积分减少';
        $messageCredit3 = '订单成长值减少';
        $credit = $setting->credit_order;

        if (! $credit) {
            throw new \Exception('管理员还没配置参数');
        }

        if ($credit) {
            $user = User::query()->find($params['user_id']); //直推
            if ($user) {

                $credit1 = $credit['order_commission1_credit1'];
                $credit2 = $credit['order_commission1_credit2'];
                $credit3 = $credit['order_commission1_credit3'];

                $user->decrement('credit1',$credit1,['remark' => $messageCredit1]);//余额
                $user->decrement('credit2',$credit2,['remark' => $messageCredit2]); //积分
                $user->decrement('credit3',$credit3,['remark' => $messageCredit3]); //成长值
                //推送
                $user['title']   = $title;
                $user['message'] = $messageCredit1.$credit1;
            }

            if ($user && $user['inviter_id'] != null) {
                $user_inviter = User::query()->find($user['inviter_id']); //上级

                $credit1 = $credit['order_commission2_credit1'];
                $credit2 = $credit['order_commission2_credit2'];
                $credit3 = $credit['order_commission2_credit3'];

                $user_inviter->decrement('credit1',$credit1,['remark' => '上级'.$messageCredit1]);//余额
                $user_inviter->decrement('credit2',$credit2,['remark' => '上级'.$messageCredit2]); //积分
                $user_inviter->decrement('credit3',$credit3,['remark' => '上级'.$messageCredit3]); //成长值
                //推送
                $user_inviter['title']   = $title;
                $user_inviter['message'] = '上级'.$messageCredit1.$credit1;
            }

            if ($user && $user['group_id'] != null) {
                $group = Group::query()->find($user['group_id']);
                $user_group_id = User::query()->find($group['user_id']); //当前组长

                $credit1 = $credit['order_group1_credit1'];
                $credit2 = $credit['order_group1_credit2'];
                $credit3 = $credit['order_group1_credit3'];

                $user_group_id->decrement('credit1',$credit1,['remark' => '组长'.$messageCredit1]);//余额
                $user_group_id->decrement('credit2',$credit2,['remark' => '组长'.$messageCredit2]); //积分
                $user_group_id->decrement('credit3',$credit3,['remark' => '组长'.$messageCredit3]); //成长值
                //推送
                $user_group_id['title']   = $title;
                $user_group_id['message'] = '组长'.$messageCredit1.$credit1;
            }

            if ($user && $user['oldgroup_id'] != null) {
                $oldgroup = Group::query()->find($user['oldgroup_id']);
                $user_oldgroup_id = User::query()->find($oldgroup['user_id']); //原组长

                $credit1 = $credit['order_group2_credit1'];
                $credit2 = $credit['order_group2_credit2'];
                $credit3 = $credit['order_group2_credit3'];

                $user_oldgroup_id->decrement('credit1',$credit1,['remark' => '原组长'.$messageCredit1]);//余额
                $user_oldgroup_id->decrement('credit2',$credit2,['remark' => '原组长'.$messageCredit2]); //积分
                $user_oldgroup_id->decrement('credit3',$credit3,['remark' => '原组长'.$messageCredit3]); //成长值
                //推送
                $user_oldgroup_id['title']   = $title;
                $user_oldgroup_id['message'] = '原组长'.$messageCredit1.$credit1;
            }

        }
    }
}
