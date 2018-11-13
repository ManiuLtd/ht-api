<?php

namespace App\Tools\Taoke;

trait TBKCommon{

    /**
     *  获取当前用户 的 pids
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|null|object
     */
    public function getPids()
    {
        $member = getMember();
        $member_pid = db('tbk_pids')->where('member_id', $member->id)->first();

        if ($member_pid) {
            return $member_pid;
        }
        $inviter_pid = db('tbk_pids')->where('member_id', $member->inviter_id)->first();
        if ($inviter_pid) {
            return $inviter_pid;
        }
        $group = db('groups')->find($member->group_id);
        $group_pid = db('tbk_pids')->where('member_id', $group->member_id)->first();
        if (!$group_pid){
            $setting = setting($this->getUserId()); //应该是根据member或者user_id
            $group_pid = json_decode($setting->pid);
        }

        return $group_pid;
    }

    /**
     * 获取当前用户对应的user
     * @return mixed
     */
    protected function getUserId()
    {
        $member = getMember();
        $user = $member->user;

        if (!$user) {
            $user = \App\Models\User\User::query()->find(1);
        }
        return $user->id;

    }
}