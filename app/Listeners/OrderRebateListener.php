<?php

namespace App\Listeners;

use App\Events\CreditOrderFriend;
use App\Events\SendNotification;
use App\Models\User\CreditLog;
use App\Models\User\Group;
use App\Models\User\Level;
use App\Models\user\user;
use App\Models\System\Setting;
use App\Tools\Taoke\Commission;
use Carbon\Carbon;

class OrderRebateListener
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
        //去掉平台的抽成
        $price = $params['price'] * (1 - $setting->commission_rate / 100);
        $tool = new Commission();
        $user = User::query()->find($params['user_id']);
        //直推订单返利
        if ($user){
            $rebate = $tool->getCommissionByUser($user->id,$price,'commission_rate1');
            $user->increment('credit1', $rebate, ['remark' => '直推订单返利']);
        }
        //上级订单返利
        if ($user && $user->inviter_id != null){
            $inviter = User::query()->find($user->inviter_id);//上级
            if (!$inviter){
                return;
            }
            $rebate = $tool->getCommissionByUser($inviter->id,$price,'commission_rate2');
            $inviter->increment('credit1', $rebate, ['remark' => '下级订单返利']);
        }
        //组长订单返利
        if ($user && $user->group_id != null){
            $group = Group::query ()->find ($user['group_id']);
            if (!$group) {
                return;
            }
            $userGroup = User::query ()->find ($group['user_id']); //当前组长
            if (!$userGroup) {
                return;
            }
            $rebate = $tool->getCommissionByUser($userGroup->id,$price,'group_rate1');
            $userGroup->increment('credit1', $rebate, ['remark' => '团员订单返利']);
        }
        //原组长订单返利
        if ($user && $user->oldgroup_id != null) {
            $oldgroup = Group::query ()->find ($user['oldgroup_id']);
            if (!$oldgroup) {
                return;
            }
            $userOldgroup = User::query ()->find ($oldgroup['user_id']); //原组长
            if (!$userOldgroup) {
                return;
            }
            $rebate = $tool->getCommissionByUser($userOldgroup->id,$price,'group_rate2');
            $userOldgroup->increment('credit1', $rebate, ['remark' => '老团员订单返利']);
        }
    }
}
