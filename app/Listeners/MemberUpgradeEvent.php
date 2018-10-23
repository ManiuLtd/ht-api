<?php

namespace App\Listeners;

use App\Events\MemberUpgrade;
use App\Models\Member\Group;
use App\Models\User\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberUpgradeEvent
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
     * @param MemberUpgrade $event
     * @throws \Throwable
     */
    public function handle(MemberUpgrade $event)
    {
        $member = $event->member;
        $level = db('member_levels')
            ->where('level','>',$member->level->level)
            ->where('status',1)
            ->orderBy('level','asc')
            ->first();

        if (!$level) {
            //等级已最高
            return;
        }

        if ($member->credit2 < $level->credit) {
            //积分不够不能升级
            return;
        }
        DB::transaction(function () use ($member,$level){
            //可以升级
            $member = db('members')->find($member->id);
            $member->update([
                'level_id' => $level->id,
            ]);

            //升级等级是否是组等级
            if ($level->is_group != 1) {
                //不是组
                return;
            }
            //会员未绑定手机号
            if($member->phone == null){
                return ;
            }
            $user = db('users')->insert([
                'name' => $member->phone,
                'password' => $member->password ?? Hash::make('123456'),
                'status' => 1,
            ]);

            //创建group
            $group = db('groups')->insert([
                'member_id' => $member->id,
                'user_id' => 1, //只有一个app
                'status' => 1,
            ]);
            $member->update([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'oldgroup_id' => $member->group_id != null ? $member->group_id : null,
            ]);
        });
        return;
    }
}
