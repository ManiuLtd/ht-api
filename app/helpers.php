<?php

/**
 * Global helpers file with misc functions.
 */
if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('history')) {
    /**
     * Access the history facade anywhere.
     */
    function history()
    {
        return app('history');
    }
}

if (! function_exists('db')) {

    /**
     * @param $table
     * @return \Illuminate\Database\Query\Builder
     */
    function db($table)
    {
        return \Illuminate\Support\Facades\DB::table($table);
    }
}

if (! function_exists('storage')) {

    /**
     * @param $disk
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    function storage($disk = null)
    {
        return \Illuminate\Support\Facades\Storage::disk($disk);
    }
}

if (! function_exists('json')) {
    /**
     * @param int $code 状态码
     * @param string $message 状态描述
     * @param null $data 返回数据
     * @return \Illuminate\Http\JsonResponse
     */
    function json(int $code, string $message, $data = null)
    {
        $array = [
            'code' => $code,
            'message' => $message,
        ];

        if ($data != null) {
            $array['data'] = $data;
        }

        return response()->json($array);
    }
}

if (! function_exists('include_route_files')) {
    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);
            while ($it->valid()) {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }
                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (! function_exists('getUserId')) {

    /**
     * @return int|mixed
     */
    function getUserId()
    {
        return 1;
    }
}
if (! function_exists('getUser')) {

    /**
     * @return int|mixed
     */
    function getUser()
    {
        return \App\Models\User\User::find(1);
    }
}



if (! function_exists('checkSms')) {

    /**
     * 验证短信是否过期
     * @param $phone
     * @param $code
     * @return bool
     */
    function checkSms($phone, $code)
    {
        $model = \App\Models\System\Sms::where([
            'code' => $code,
            'phone' => $phone,
            ['created_at', '>=', \Illuminate\Support\Carbon::now()->addMinute(-1)],
        ])->first();
        if (! $model) {
            return false;
        }

        return true;
    }
}

if (!function_exists('setting')) {
    /**
     * 获取设置信息
     * @param $userID
     * @return mixed
     */
    function setting($userID)
    {
        $setting = (new \App\Models\System\Setting())->where('user_id', $userID)->first();
        return $setting;
    }
}

if (!function_exists('creditAdd')) {
    /**
     * 积分、余额、成长值增加
     * @param $member
     * @param $credit
     * @param $column
     * @param $extra
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    function creditAdd($member,$credit,$column,$extra,$type)
    {
        $today = \Carbon\Carbon::today()->toDateTimeString();
        $credits = \App\Models\User\CreditLog::where(['member_id'=>$member->id,'column'=>$credit])
            ->whereIn('type',[11,13,15,21,22,23,16,17,18,19])
            ->whereDate('created_at',$today)
            ->sum('credit');
        //设置信息
        $setting = setting(1);
        $credit_friend = json_decode($setting->credit_friend);
        if (!$credit_friend) {
            throw new \Exception('管理员还没配置参数');
        }

        if($column == 'credit1' && $credits < $credit_friend->friend_max_credit1){
            event(new \App\Events\CreditIncrement($member, $column, $credit, $extra,$type));//余额
            return true;
        }elseif ($column == 'credit2' && $credits < $credit_friend->friend_max_credit2){
            event(new \App\Events\CreditIncrement($member, $column, $credit, $extra,$type));//积分
            return true;
        }elseif ($column == 'credit3' && $credits < $credit_friend->friend_max_credit3){
            event(new \App\Events\CreditIncrement($member, $column, $credit, $extra,$type));//成长值
            return true;
        }else{
            return false;
        }
    }
}