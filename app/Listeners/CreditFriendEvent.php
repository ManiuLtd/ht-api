<?php

namespace App\Listeners;

use App\Models\user\user;
use App\Events\CreditFriend;

class CreditFriendEvent
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
     * 粉丝增加事件.
     * @param CreditFriend $event
     * @throws \Exception
     */
    public function handle(CreditFriend $event)
    {
        //设置信息
        $setting = setting(1);
        $credit_friend = $setting->credit_friend;
        if (! $credit_friend) {
            throw new \Exception('管理员还没配置参数');
        }
        //邀请人
        $user1 = $event->user;
        if (! $user1) {
            throw new \Exception('邀请人不存在');
        }
        creditAdd($user1, $credit_friend->friend_commission1_credit2, 'credit2', '直推积分增加', 17); //积分
        creditAdd($user1, $credit_friend->friend_commission1_credit1, 'credit1', '直推余额增加', 18); //余额
        creditAdd($user1, $credit_friend->friend_commission1_credit3, 'credit3', '直推成长值增加', 19); //成长值

        $user2 = $event->user->inviter; //上级
        if ($user2) {
            creditAdd($user2, $credit_friend->friend_commission1_credit2, 'credit2', '推荐上级积分增加', 17); //积分
            creditAdd($user2, $credit_friend->friend_commission1_credit1, 'credit1', '推荐上级余额增加', 18); //余额
            creditAdd($user2, $credit_friend->friend_commission1_credit3, 'credit3', '推荐上级成长值增加', 19); //成长值
        }

        $group1 = $event->user->group; //组
        if ($group1) {
            $user3 = User::find($group1['user_id']);
            if ($user3) {
                creditAdd($user3, $credit_friend->friend_commission1_credit2, 'credit2', '推荐组长积分增加', 17); //积分
                creditAdd($user3, $credit_friend->friend_commission1_credit1, 'credit1', '推荐组长余额增加', 18); //余额
                creditAdd($user3, $credit_friend->friend_commission1_credit3, 'credit3', '推荐组长成长值增加', 19); //成长值
            }
        }

        $group2 = $event->user->oldGroup; //原组
        if ($group2) {
            $user4 = User::find($group2['user_id']);
            if ($user4) {
                creditAdd($user4, $credit_friend->friend_commission1_credit2, 'credit2', '推荐原组长积分增加', 17); //积分
                creditAdd($user4, $credit_friend->friend_commission1_credit1, 'credit1', '推荐原组长余额增加', 18); //余额
                creditAdd($user4, $credit_friend->friend_commission1_credit3, 'credit3', '推荐原组长成长值增加', 19);//成长值
            }
        }
    }
}
