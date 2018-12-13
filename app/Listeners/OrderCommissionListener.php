<?php

namespace App\Listeners;

use App\Events\OrderCommission;
use App\Events\SendNotification;
use App\Models\User\CreditLog;
use App\Models\User\Group;
use App\Models\User\Level;
use App\Models\user\user;
use App\Models\System\Setting;
use App\Tools\Taoke\Commission;
use Carbon\Carbon;

class OrderCommissionListener
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
     * @param OrderCommission $event
     * @throws \Exception
     */
    public function handle(OrderCommission $event)
    {
        $params = $event->params;

        $setting = setting (1);
        //去掉平台的抽成
        $price = $params['price'] * (1 - $setting->commission_rate / 100);
        $tool = new Commission();
        $user = User::query()->with('level')->find($params['user_id']);
        //直推订单返利
        if ($user && $user['level']->is_commission == 1){
            $rebate = $tool->getCommissionByUser($user->id,$price,'commission_rate1');
            $user->increment('credit1', $rebate, ['remark' => '直推订单返利']);
        }
        //上级订单返利
        if ($user && $user->inviter_id != null){
            $inviter = User::query()->with('level')->find($user->inviter_id);//上级
            if (!$inviter && $inviter['level']->is_commission == 1){
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
            $userGroup = User::query ()->with('level')->find ($group['user_id']); //当前组长
            if (!$userGroup && $userGroup['level']->is_group == 1) {
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
            $userOldgroup = User::query ()->with('level')->find ($oldgroup['user_id']); //原组长
            if (!$userOldgroup && $userOldgroup['level']->is_group == 1) {
                return;
            }
            $rebate = $tool->getCommissionByUser($userOldgroup->id,$price,'group_rate2');
            $userOldgroup->increment('credit1', $rebate, ['remark' => '补贴订单返利']);
        }
    }
}
