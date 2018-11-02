<?php

namespace App\Listeners;

use App\Events\SendOrder;
use App\Models\Member\Member;
use App\Models\System\Setting;

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


    public function handle(SendOrder $event)
    {
        $params = $event->params;
        $setting = Setting::query()->find(1);
        if ($setting->credit_order){
            $credit_order = json_decode($setting->credit_order);
            $member = Member::query()->find($params['member_id']);//直推
            if ($member){
                Member::query()->where('id',$member['id'])->update([
                    'credit1' => $credit_order->order_commission1_credit1,//余额
                    'credit2' => $credit_order->order_commission1_credit2,//积分
                    'credit3' => $credit_order->order_commission1_credit3,//成长值
                ]);
            }
            if ($member && $member['inviter_id']){
                $member_inviter = Member::query()->find($member['inviter_id']);//上级
                Member::query()->where('id',$member_inviter['id'])->update([
                    'credit1' => $credit_order->order_commission2_credit1,//余额
                    'credit2' => $credit_order->order_commission2_credit2,//积分
                    'credit3' => $credit_order->order_commission2_credit3,//成长值
                ]);
            }
            if ($member && $member['group_id']){
                $member_group_id = Member::query()->find($member['group_id']);//当前组长
                Member::query()->where('id',$member_group_id['id'])->update([
                    'credit1' => $credit_order->order_group1_credit1,//余额
                    'credit2' => $credit_order->order_group1_credit2,//积分
                    'credit3' => $credit_order->order_group1_credit3,//成长值
                ]);
            }
            if ($member && $member['oldgroup_id']){
                $member_oldgroup_id = Member::query()->find($member['oldgroup_id']);//原组长
                Member::query()->where('id',$member_oldgroup_id['id'])->update([
                    'credit1' => $credit_order->order_group2_credit1,//余额
                    'credit2' => $credit_order->order_group2_credit2,//积分
                    'credit3' => $credit_order->order_group2_credit3,//成长值
                ]);
            }
        }
    }
}
