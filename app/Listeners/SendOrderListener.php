<?php

namespace App\Listeners;

use App\Events\CreditIncrement;
use App\Events\SendOrder;
use App\Models\Member\Member;
use App\Models\System\Setting;
use App\Models\User\User;

class SendOrderListener
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
     * @param SendOrder $event
     * @throws \Exception
     */
    public function handle(SendOrder $event)
    {
        $params = $event->params;
        $setting = setting(1);
        if ($setting->credit_order){
            $credit_order = json_decode($setting->credit_order);
            if (!$credit_order) {
                throw new \Exception('管理员还没配置参数');
            }
            $member = User::query()->find($params['member_id']);//直推
            if ($member){
                creditAdd($member,$credit_order->order_commission1_credit1,'credit1','直推积分增加',18);//余额
                creditAdd($member,$credit_order->order_commission1_credit2,'credit2','直推余额增加',17);//积分
                creditAdd($member,$credit_order->order_commission1_credit3,'credit3','直推成长值增加',19);//成长值
            }
            if ($member && $member['inviter_id']){
                $member_inviter = User::query()->find($member['inviter_id']);//上级
                creditAdd($member_inviter,$credit_order->order_commission2_credit1,'credit1','上级积分增加',18);//余额
                creditAdd($member_inviter,$credit_order->order_commission2_credit2,'credit2','上级余额增加',17);//积分
                creditAdd($member_inviter,$credit_order->order_commission2_credit3,'credit3','上级成长值增加',19);//成长值
            }
            if ($member && $member['group_id']){
                $member_group_id = User::query()->find($member['group_id']);//当前组长
                creditAdd($member_group_id,$credit_order->order_group1_credit1,'credit1','组长积分增加',18);//余额
                creditAdd($member_group_id,$credit_order->order_group1_credit2,'credit2','组长余额增加',17);//积分
                creditAdd($member_group_id,$credit_order->order_group1_credit3,'credit3','组长成长值增加',19);//成长值
            }
            if ($member && $member['oldgroup_id']){
                $member_oldgroup_id = User::query()->find($member['oldgroup_id']);//原组长
                creditAdd($member_oldgroup_id,$credit_order->order_group2_credit1,'credit1','原组长积分增加',18);//余额
                creditAdd($member_oldgroup_id,$credit_order->order_group2_credit2,'credit2','原组长余额增加',17);//积分
                creditAdd($member_oldgroup_id,$credit_order->order_group2_credit3,'credit3','原组长成长值增加',19);//成长值
            }
        }
    }
}
