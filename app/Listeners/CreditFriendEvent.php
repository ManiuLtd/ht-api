<?php

namespace App\Listeners;

use App\Events\CreditFriend;
use App\Models\Member\CreditLog;
use App\Models\Member\Group;
use App\Models\Member\Member;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * 粉丝增加事件
     * Handle the event.
     *
     * @param  CreditFriend  $event
     * @return void
     */
    public function handle(CreditFriend $event)
    {
        //设置信息
        $setting = setting(1);
        $credit_friend = json_decode($setting->credit_friend);
        if (!$credit_friend) {
            throw new \Exception('管理员还没配置参数');
        }
        //邀请人
        $member1 = $event->member;
        if(!$member1){
            throw new \Exception('邀请人不存在');
        }
        creditAdd($member1,$credit_friend->friend_commission1_credit2,'credit2','直推积分增加',17);//积分
        creditAdd($member1,$credit_friend->friend_commission1_credit1,'credit1','直推余额增加',18);//余额
        creditAdd($member1,$credit_friend->friend_commission1_credit3,'credit3','直推成长值增加',19);//成长值

        $member2 = $event->member->inviter;//上级
        if($member2){
            creditAdd($member2,$credit_friend->friend_commission1_credit2,'credit2','推荐上级积分增加',17);//积分
            creditAdd($member2,$credit_friend->friend_commission1_credit1,'credit1','推荐上级余额增加',18);//余额
            creditAdd($member2,$credit_friend->friend_commission1_credit3,'credit3','推荐上级成长值增加',19);//成长值
        }

        $group1 = $event->member->group;//组
        if($group1){
            $member3 = Member::find($group1['member_id']);
            if($member3){
                creditAdd($member3,$credit_friend->friend_commission1_credit2,'credit2','推荐组长积分增加',17);//积分
                creditAdd($member3,$credit_friend->friend_commission1_credit1,'credit1','推荐组长余额增加',18);//余额
                creditAdd($member3,$credit_friend->friend_commission1_credit3,'credit3','推荐组长成长值增加',19);//成长值
            }
        }

        $group2 = $event->member->oldGroup;//原组
        if($group2){
            $member4 = Member::find($group2['member_id']);
            if($member4){
                creditAdd($member4,$credit_friend->friend_commission1_credit2,'credit2','推荐原组长积分增加',17);//积分
                creditAdd($member4,$credit_friend->friend_commission1_credit1,'credit1','推荐原组长余额增加',18);//余额
                creditAdd($member4,$credit_friend->friend_commission1_credit3,'credit3','推荐原组长成长值增加',19);//成长值
            }
        }
    }
}
