<?php

namespace App\Tools\Taoke;

trait TBKCommon
{
    /**
     *  获取当前用户 的 pids.
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|mixed|null|object
     */
    public function getPids()
    {
        $user = getUser ();
        $setting = setting (1); //应该是根据user或者user_id

        //未登录 获取系统默认pid

        $user_pid = db ('tbk_pids')->where ('user_id', $user->id)->first ();

        //自己
        if ($user_pid) {
            return $user_pid;
        }
        //邀请人
        $inviter_pid = db ('tbk_pids')->where ('user_id', $user->inviter_id)->first ();

        if ($inviter_pid) {
            return $inviter_pid;
        }
        //小组
        $group = db ('groups')->find ($user->group_id);
        $group_pid = db ('tbk_pids')->where ('user_id', $group->user_id)->first ();

        if (!$group_pid) {
            return $setting->pid;

        }

        return $group_pid;
    }


    private function arrayToObject($e)
    {

        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)$this->arrayToObject($v);
        }
        return (object)$e;
    }
}
