<?php

namespace App\Listeners;

use App\Events\MemberUpgrade;
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
        $user = $event->user;
        $level = db('user_levels')
            ->where('level', '>', $user->level->level)
            ->where('status', 1)
            ->orderBy('level', 'asc')
            ->first();

        if (! $level) {
            //等级已最高
            return;
        }

        if ($user->credit2 < $level->credit) {
            //积分不够不能升级
            return;
        }
        DB::transaction(function () use ($user,$level) {
            //可以升级
            $user = db('users')->find($user->id);
            $user->update([
                'level_id' => $level->id,
            ]);

            //升级等级是否是组等级
            if ($level->is_group != 1) {
                //不是组
                return;
            }
            //会员未绑定手机号
            if ($user->phone == null) {
                return;
            }
            $user = db('users')->insert([
                'name' => $user->phone,
                'password' => $user->password ?? Hash::make('123456'),
                'status' => 1,
            ]);

            //创建group
            $group = db('groups')->insert([
                'user_id' => $user->id,
                'user_id' => 1, //只有一个app
                'status' => 1,
            ]);
            $user->update([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'oldgroup_id' => $user->group_id != null ? $user->group_id : null,
            ]);
        });
    }
}
