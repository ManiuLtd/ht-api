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

        $setting = setting (1);

        if ($event->type == 1) {
            $credit = $setting->credit_order;
        } else {
            $credit = $setting->credit_friend;
        }

        if (!$credit) {
            throw new \InvalidArgumentException('管理员还没配置参数');
        }
        $today = now ()->toDateTimeString ();
        //默认值
        $remark = "";
        $credit1 = 0;
        $credit2 = 0;
        $credit3 = 0;
        if ($credit) {

            //直推
            $user = User::query ()->find ($params['user_id']); //直推
            if ($user) {
                if ($event->type == 1) {
                    $credit1 = $credit['order_commission1_credit1'];
                    $credit2 = $credit['order_commission1_credit2'];
                    $credit3 = $credit['order_commission1_credit3'];
                    $remark = "新增直推订单";
                }
                if ($event->type == 2) {

                    $remark = "新增直推粉丝";
                    //余额
                    $credit1_sum = CreditLog::query ()->where (['user_id' => $user->id, 'column' => 'credit1'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']) {
                        $credit1 = $credit['friend_commission1_credit1'];
                    }
                    //积分
                    $credit2_sum = CreditLog::query ()->where (['user_id' => $user->id, 'column' => 'credit2'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']) {
                        $credit2 = $credit['friend_commission1_credit2'];

                    }
                    //成长值
                    $credit3_sum = CreditLog::query ()->where (['user_id' => $user->id, 'column' => 'credit3'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']) {
                        $credit3 = $credit['friend_commission1_credit3'];
                    }
                }
                if ($credit1 != 0) {
                    $user->increment ('credit1', $credit1, ['remark' => $remark]);//余额
                }
                if ($credit2 != 0) {
                    $user->increment ('credit2', $credit2, ['remark' => $remark]); //积分
                }
                if ($credit3 != 0) {
                    $user->increment ('credit3', $credit3, ['remark' => $remark]); //成长值
                }
            }


            //上级
            if ($user && $user['inviter_id'] != null) {
                $inviter = User::query ()->find ($user['inviter_id']); //上级
                //上级不存在
                if (!$inviter) {
                    return;
                }
                if ($event->type == 1) {
                    $credit1 = $credit['order_commission2_credit1'];
                    $credit2 = $credit['order_commission2_credit2'];
                    $credit3 = $credit['order_commission2_credit3'];
                    $remark = "新增下级订单";
                }
                if ($event->type == 2) {
                    $remark = "新增下级粉丝";

                    $credit1_sum = CreditLog::query ()->where (['user_id' => $inviter->id, 'column' => 'credit1'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']) {
                        $credit1 = $credit['friend_commission2_credit1'];
                    }
                    $credit2_sum = CreditLog::query ()->where (['user_id' => $inviter->id, 'column' => 'credit2'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']) {
                        $credit2 = $credit['friend_commission2_credit2'];
                    }

                    $credit3_sum = CreditLog::query ()->where (['user_id' => $inviter->id, 'column' => 'credit3'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']) {
                        $credit3 = $credit['friend_commission2_credit3'];
                    }

                }

                if ($credit1 != 0) {
                    $inviter->increment ('credit1', $credit1, ['remark' => $remark]);//余额
                }
                if ($credit2 != 0) {
                    $inviter->increment ('credit2', $credit2, ['remark' => $remark]); //积分
                }
                if ($credit3 != 0) {
                    $inviter->increment ('credit3', $credit3, ['remark' => $remark]); //成长值
                }
            }
            //团队
            if ($user && $user['group_id'] != null) {
                $group = Group::query ()->find ($user['group_id']);
                if (!$group) {
                    return;
                }
                $userGroup = User::query ()->find ($group['user_id']); //当前组长
                if (!$userGroup) {
                    return;
                }
                if ($event->type == 1) {
                    $credit1 = $credit['order_group1_credit1'];
                    $credit2 = $credit['order_group1_credit2'];
                    $credit3 = $credit['order_group1_credit3'];
                    $remark = "新增团队订单";

                }
                if ($event->type == 2) {
                    $remark = "新增团队粉丝";

                    //判断今日 余额 积分 成长值是否达到上限
                    $credit1_sum = CreditLog::query ()->where (['user_id' => $userGroup->id, 'column' => 'credit1'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']) {
                        $credit1 = $credit['friend_group1_credit1'];
                    }
                    $credit2_sum = CreditLog::query ()->where (['user_id' => $userGroup->id, 'column' => 'credit2'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']) {
                        $credit2 = $credit['friend_group1_credit2'];
                    }
                    $credit3_sum = CreditLog::query ()->where (['user_id' => $userGroup->id, 'column' => 'credit3'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']) {
                        $credit3 = $credit['friend_group1_credit3'];
                    }
                }

                if ($credit1 != 0) {
                    $userGroup->increment ('credit1', $credit1, ['remark' => $remark]);//余额
                }
                if ($credit2 != 0) {
                    $userGroup->increment ('credit2', $credit2, ['remark' => $remark]); //积分
                }
                if ($credit3 != 0) {
                    $userGroup->increment ('credit3', $credit3, ['remark' => $remark]); //成长值
                }

            }
            //补贴
            if ($user && $user['oldgroup_id'] != null) {
                $oldgroup = Group::query ()->find ($user['oldgroup_id']);
                if (!$oldgroup) {
                    return;
                }
                $userOldgroup = User::query ()->find ($oldgroup['user_id']); //原组长
                if (!$userOldgroup) {
                    return;
                }
                if ($event->type == 1) {
                    $credit1 = $credit['order_group2_credit1'];
                    $credit2 = $credit['order_group2_credit2'];
                    $credit3 = $credit['order_group2_credit3'];
                    $remark = "新增补贴订单";
                }
                if ($event->type == 2) {


                    $remark = "新增补贴粉丝";

                    //判断今日 余额 积分 成长值是否达到上限
                    $credit1_sum = CreditLog::query ()->where (['user_id' => $userOldgroup->id, 'column' => 'credit1'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit1_sum < $credit['friend_max_credit1']) {
                        $credit1 = $credit['friend_group2_credit1'];
                    }
                    $credit2_sum = CreditLog::query ()->where (['user_id' => $userOldgroup->id, 'column' => 'credit2'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit2_sum < $credit['friend_max_credit2']) {
                        $credit2 = $credit['friend_group2_credit2'];
                    }
                    $credit3_sum = CreditLog::query ()->where (['user_id' => $userOldgroup->id, 'column' => 'credit3'])
                        ->whereDate ('created_at', $today)
                        ->sum ('credit');
                    if ($credit3_sum < $credit['friend_max_credit3']) {
                        $credit3 = $credit['friend_group2_credit3'];
                    }
                }


                if ($credit1 != 0) {
                    $userOldgroup->increment ('credit1', $credit1, ['remark' => $remark]);//余额
                }
                if ($credit2 != 0) {
                    $userOldgroup->increment ('credit2', $credit2, ['remark' => $remark]); //积分
                }
                if ($credit3 != 0) {
                    $userOldgroup->increment ('credit3', $credit3, ['remark' => $remark]); //成长值
                }


            }
        }
    }
}
