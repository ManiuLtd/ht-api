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
        $member_level = $member->level;
        $level = db('member_levels')->where('level','>',$member_level->level)->where('status',1)->orderBy('level','asc')->first();

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
            db('members')->where('id',$member->id)->update([
                'level_id' => $level->id,
            ]);

            //升级等级是否是组等级
            if ($level->is_group != 1) {
                //不是组
                return;
            }
            $user = User::create([
                'name' => $member->nickname,
                'email' => $member->phone,
                'password' => $member->password ?? Hash::make('123456'),
                'status' => 1,
            ]);

            //创建group
            $group = Group::create([
                'member_id' => $member->id,
                'user_id' => $user->id,
                'status' => 1,
            ]);
            db('members')->where('id',$member->id)->update([
                'user_id' => $user->id,
                'group_id' => $group->id,
            ]);
        });
        return;
    }
}
