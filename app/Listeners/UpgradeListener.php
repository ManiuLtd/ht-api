<?php

namespace App\Listeners;

use App\Events\Upgrade;
use App\Models\Taoke\Pid;
use App\Models\User\Group;
use App\Models\User\User;
use App\Tools\Taoke\JingDong;
use App\Tools\Taoke\PinDuoDuo;
use Illuminate\Support\Facades\DB;


class UpgradeListener
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
     * @param Upgrade $event
     * @throws \Throwable
     */
    public function handle(Upgrade $event)
    {
        $user = $event->user;
        $level = $event->level;

        DB::transaction(function () use ($user,$level) {
            //可以升级
            $user = User::query ()->find($user->id);
            $user->update([
                'level_id' => $level->id,
            ]);
            $group = $user->group;

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
                //设计为组长之前，用的其他的推广位，先取消之前的推广位
                $pid_group = Pid::query()->where('agent_id',$user->id)->first();
                if ($pid_group){
                    $pid_group->update([
                        'agent_id' => null
                    ]);
                }
            }

            if($level->is_commission == 1) {
                //查看是否已经有推广位
                $user_pid = Pid::query ()->where ('agent_id', $user->id)->first ();
                if (!$user_pid) {
                    $pid = Pid::query ()->whereNull('agent_id')->where('user_id',$group->user_id)->whereNotNull('taobao')->first ();
                    //获取我组长的id
                    $group_id = Group::query()->find($user->group_id);
                    if (!$group_id){
                        throw new \Exception('小组不存在');
                    }
                    $jingdong = new JingDong();
                    $jingdong_pid = $jingdong->createPid (['group_id' => $group->id]);
                    $pinduoduo = new PinDuoDuo();
                    $pinduoduo_pid = $pinduoduo->createPid ();
                    if ($pid) {
                        $pid->update ([
                            'agent_id' => $user->id,
                            'jingdong' => $jingdong_pid,
                            'pinduoduo' => $pinduoduo_pid[0]->p_id
                        ]);
                    } else {
                        Pid::query ()->create ([
                            'user_id' => $group->user_id,
                            'agent_id' => $user->id,
                            'jingdong' => $jingdong_pid,
                            'pinduoduo' => $pinduoduo_pid[0]->p_id
                        ]);
                    }
                }
            }

        });
    }
}
