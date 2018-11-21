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
            if ($level->is_group != 1) {
                //不是组
                return;
            }

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
            //TODO 推广位未分配   查询淘宝推广位不为空并且用户id为空的  分配给他，调用接口生成京东和拼多多推广位，
            //TODO 淘宝推广位用完了，在pid表生成一条新数据，然后调用接口生成京东和拼多多推广位，让淘宝推广位为空
            //查看是否已经有推广位
            $user_pid = Pid::query()->where('agent_id',$user->id)->first();
            if (!$user_pid){
                $pid = Pid::query()->where('agent_id',null)->where('taobao','<>',null)->first();
                $jingdong = new JingDong();
                $jingdong_pid = $jingdong->createPid(['group_id' => $group->id]);
                $pinduoduo = new PinDuoDuo();
                $pinduoduo_pid = $pinduoduo->createPid();
                if ($pid){
                    $pid->update([
                        'user_id'   => $user->id,
                        'jingdong'  => $jingdong_pid,
                        'pinduoduo' => $pinduoduo_pid
                    ]);
                }else{
                    Pid::query()->create([
                        'user_id'   => $user->id,
                        'jingdong'  => $jingdong_pid,
                        'pinduoduo' => $pinduoduo_pid
                    ]);
                }
            }

        });
    }
}
