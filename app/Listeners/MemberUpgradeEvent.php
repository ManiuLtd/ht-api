<?php

namespace App\Listeners;

use App\Events\MemberUpgrade;
use App\Models\Taoke\Pid;
use App\Models\User\Level;
use App\Models\User\User;
use App\Tools\Taoke\JingDong;
use App\Tools\Taoke\PinDuoDuo;
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

        $user_level = Level::query()->find($user->level_id);
        if (!$user_level){
            return;
        }
        $level = db('user_levels')
            ->where('level', '>', $user_level->level)
            ->where('status', 1)
            ->orderBy('level', 'asc')
            ->first();

        if (! $level) {
            //等级已最高
            return;
        }

        if ($user->credit3 < $level->credit) {
            //成长值不够不能升级
            return;
        }
        DB::transaction(function () use ($user,$level) {
            //可以升级
            $user = User::query ()->find($user->id);
            $user->update([
                'level_id' => $level->id,
            ]);

            //升级等级是否是组等级
            if ($level->is_group == 1) {
                //创建group
                $group = db('groups')->insert([
                    'user_id' => $user->id,
                    'status' => 1,
                ]);
                $user->update([
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'oldgroup_id' => $user->group_id != null ? $user->group_id : null,
                ]);
                //TODO 设计为组长之前，用的其他的推广位，先取消之前的推广位
                $pid_group = Pid::query()->where('user_id',$user->id)->first();
                if ($pid_group){
                    $pid_group->update([
                        'user_id' => null
                    ]);
                }
            }
            //TODO  组
            if($level->is_commission == 1) {
                //查看是否已经有推广位
                $user_pid = Pid::query ()->where ('user_id', $user->id)->first ();
                if (!$user_pid) {
                    $pid = Pid::query ()->where ('user_id', null)->where ('taobao', '<>', null)->first ();
                    $jingdong = new JingDong();
                    $jingdong_pid = $jingdong->createPid (['group_id' => $group->id]);
                    $pinduoduo = new PinDuoDuo();
                    $pinduoduo_pid = $pinduoduo->createPid ();
                    if ($pid) {
                        $pid->update ([

                            'user_id' => $user->id,
                            'jingdong' => $jingdong_pid,
                            'pinduoduo' => $pinduoduo_pid
                        ]);
                    } else {
                        Pid::query ()->create ([
                            'user_id' => $user->id,
                            'jingdong' => $jingdong_pid,
                            'pinduoduo' => $pinduoduo_pid
                        ]);
                    }
                }
            }

        });
    }
}
