<?php

namespace App\Listeners;

use App\Events\CreditOrderFriend;
use App\Events\SendNotification;
use App\Models\User\CreditLog;
use App\Models\User\Group;
use App\Models\user\user;
use App\Models\System\Setting;
use Carbon\Carbon;

class CreditOrderFriendListener
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
     * @param CreditOrderFriend $event
     * @throws \Exception
     */
    public function handle(CreditOrderFriend $event)
    {
        $params = $event->params;

        $setting = setting(1);

        if ($event->type == 1){
            $title = '新增订单';
            $messageCredit1 = '订单余额收益增加';
            $messageCredit2 = '订单积分增加';
            $messageCredit3 = '订单成长值增加';
            $credit = $setting->credit_order;
        }else{
            $title = '新增粉丝';
            $messageCredit1 = '增加粉丝余额收益增加';
            $messageCredit2 = '增加粉丝积分增加';
            $messageCredit3 = '增加粉丝成长值增加';
            $credit = $setting->credit_friend;
        }


        if (! $credit) {
            throw new \Exception('管理员还没配置参数');
        }
        $today = now ()->toDateTimeString();

        if ($credit) {
            $user = User::query()->find($params['user_id']); //直推
            if ($user) {
                if ($event->type == 1){
                    $credit1 = $credit['order_commission1_credit1'];
                    $credit2 = $credit['order_commission1_credit2'];
                    $credit3 = $credit['order_commission1_credit3'];
                }else{
                    //判断今日 余额 积分 成长值是否达到上限
                    $credit1_sum = CreditLog::query()->where(['user_id' => $user->id, 'column' => 'credit1'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']){
                        $credit1 = $credit['friend_commission1_credit1'];
                    }else{
                        $credit1 = 0;
                    }
                    $credit2_sum = CreditLog::query()->where(['user_id' => $user->id, 'column' => 'credit2'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']){
                        $credit2 = $credit['friend_commission1_credit2'];
                    }else{
                        $credit2 = 0;
                    }
                    $credit3_sum = CreditLog::query()->where(['user_id' => $user->id, 'column' => 'credit3'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']){
                        $credit3 = $credit['friend_commission1_credit3'];
                    }else{
                        $credit3 = 0;
                    }
                }
                $user->increment('credit1',$credit1,['remark' => $messageCredit1]);//余额
                $user->increment('credit2',$credit2,['remark' => $messageCredit2]); //积分
                $user->increment('credit3',$credit3,['remark' => $messageCredit3]); //成长值
                //推送
                $user['title']   = $title;
                $user['message'] = $messageCredit1.$credit1;
                event(new SendNotification($user->toArray()));
            }

            if ($user && $user['inviter_id'] != null) {
                $user_inviter = User::query()->find($user['inviter_id']); //上级
                if ($event->type == 1){
                    $credit1 = $credit['order_commission2_credit1'];
                    $credit2 = $credit['order_commission2_credit2'];
                    $credit3 = $credit['order_commission2_credit3'];
                }else{
                    //判断今日 余额 积分 成长值是否达到上限
                    $credit1_sum = CreditLog::query()->where(['user_id' => $user_inviter->id, 'column' => 'credit1'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']){
                        $credit1 = $credit['friend_commission2_credit1'];
                    }else{
                        $credit1 = 0;
                    }
                    $credit2_sum = CreditLog::query()->where(['user_id' => $user_inviter->id, 'column' => 'credit2'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']){
                        $credit2 = $credit['friend_commission2_credit2'];
                    }else{
                        $credit2 = 0;
                    }
                    $credit3_sum = CreditLog::query()->where(['user_id' => $user_inviter->id, 'column' => 'credit3'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']){
                        $credit3 = $credit['friend_commission2_credit3'];
                    }else{
                        $credit3 = 0;
                    }
                }
                $user_inviter->increment('credit1',$credit1,['remark' => '上级'.$messageCredit1]);//余额
                $user_inviter->increment('credit2',$credit2,['remark' => '上级'.$messageCredit2]); //积分
                $user_inviter->increment('credit3',$credit3,['remark' => '上级'.$messageCredit3]); //成长值
                //推送
                $user_inviter['title']   = $title;
                $user_inviter['message'] = '上级'.$messageCredit1.$credit1;
                event(new SendNotification($user_inviter->toArray()));
            }
            if ($user && $user['group_id'] != null) {
                $group = Group::query()->find($user['group_id']);
                $user_group_id = User::query()->find($group['user_id']); //当前组长

                if ($event->type == 1){
                    $credit1 = $credit['order_group1_credit1'];
                    $credit2 = $credit['order_group1_credit2'];
                    $credit3 = $credit['order_group1_credit3'];
                }else{
                    //判断今日 余额 积分 成长值是否达到上限
                    $credit1_sum = CreditLog::query()->where(['user_id' => $user_group_id->id, 'column' => 'credit1'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']){
                        $credit1 = $credit['friend_group1_credit1'];
                    }else{
                        $credit1 = 0;
                    }
                    $credit2_sum = CreditLog::query()->where(['user_id' => $user_group_id->id, 'column' => 'credit2'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']){
                        $credit2 = $credit['friend_group1_credit2'];
                    }else{
                        $credit2 = 0;
                    }
                    $credit3_sum = CreditLog::query()->where(['user_id' => $user_group_id->id, 'column' => 'credit3'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']){
                        $credit3 = $credit['friend_group1_credit3'];
                    }else{
                        $credit3 = 0;
                    }
                }
                $user_group_id->increment('credit1',$credit1,['remark' => '组长'.$messageCredit1]);//余额
                $user_group_id->increment('credit2',$credit2,['remark' => '组长'.$messageCredit2]); //积分
                $user_group_id->increment('credit3',$credit3,['remark' => '组长'.$messageCredit3]); //成长值
                //推送
                $user_group_id['title']   = $title;
                $user_group_id['message'] = '组长'.$messageCredit1.$credit1;
                event(new SendNotification($user_group_id->toArray()));
            }
            if ($user && $user['oldgroup_id'] != null && $event->type == 1) {
                $oldgroup = Group::query()->find($user['oldgroup_id']);
                $user_oldgroup_id = User::query()->find($oldgroup['user_id']); //原组长
                if ($event->type == 1){
                    $credit1 = $credit['order_group2_credit1'];
                    $credit2 = $credit['order_group2_credit2'];
                    $credit3 = $credit['order_group2_credit3'];
                }else{
                    //判断今日 余额 积分 成长值是否达到上限
                    $credit1_sum = CreditLog::query()->where(['user_id' => $user_oldgroup_id->id, 'column' => 'credit1'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']){
                        $credit1 = $credit['friend_group2_credit1'];
                    }else{
                        $credit1 = 0;
                    }
                    $credit2_sum = CreditLog::query()->where(['user_id' => $user_oldgroup_id->id, 'column' => 'credit2'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']){
                        $credit2 = $credit['friend_group2_credit2'];
                    }else{
                        $credit2 = 0;
                    }
                    $credit3_sum = CreditLog::query()->where(['user_id' => $user_oldgroup_id->id, 'column' => 'credit3'])
                        ->whereDate('created_at', $today)
                        ->sum('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']){
                        $credit3 = $credit['friend_group2_credit3'];
                    }else{
                        $credit3 = 0;
                    }
                }
                $user_oldgroup_id->increment('credit1',$credit1,['remark' => '原组长'.$messageCredit1]);//余额
                $user_oldgroup_id->increment('credit2',$credit2,['remark' => '原组长'.$messageCredit2]); //积分
                $user_oldgroup_id->increment('credit3',$credit3,['remark' => '原组长'.$messageCredit3]); //成长值
                //推送
                $user_oldgroup_id['title']   = $title;
                $user_oldgroup_id['message'] = '原组长'.$messageCredit1.$credit1;
                event(new SendNotification($user_oldgroup_id->toArray()));
            }
        }
    }
}
