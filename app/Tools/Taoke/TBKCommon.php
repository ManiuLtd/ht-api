<?php

namespace App\Tools\Taoke;

trait TBKCommon{

    /**
     *  获取当前用户 的 pids
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|null|object
     */
    public function getPids()
    {
        $user = getUser();
        $user_pid = db('tbk_pids')->where('user_id', $user->id)->first();

        if ($user_pid) {
            return $user_pid;
        }
        $inviter_pid = db('tbk_pids')->where('user_id', $user->inviter_id)->first();
        if ($inviter_pid) {
            return $inviter_pid; 
        }
        $group = db('groups')->find($user->group_id);
        $group_pid = db('tbk_pids')->where('user_id', $group->user_id)->first();
        if (!$group_pid){
            $setting = setting($this->getUserId()); //应该是根据user或者user_id

            $group_pid = $setting->pid;
        }

        return $group_pid;
    }

    /**
     * 获取当前用户对应的user
     * @return mixed
     */
    protected function getUserId()
    {
        $user = getUser();
        $user = $user->user;

        if (!$user) {
            $user = \App\Models\User\User::query()->find(1);
        }
        return $user->id;

    }
}