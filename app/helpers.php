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
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    function json(int $code, string $message, $data = null, $statusCode = 200)
    {
        $array = [
            'code' => $code,
            'message' => $message,
        ];

        if ($data != null) {
            $array['data'] = $data;
        }

        return response()->json($array, $statusCode);
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
        return auth()->id();
    }
}
if (! function_exists('getUser')) {

    /**
     * @return int|mixed
     */
    function getUser()
    {
        return auth()->user();
//        return $user = \App\Models\User\User::query()->find(1);
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
            ['created_at', '>=', now()->addSecond(-env('VERIFY_CODE_EXPIRED_TIME'))->toDateTimeString()],
        ])->first();

        if (! $model) {
            return false;
        }

        return true;
    }
}

if (! function_exists('setting')) {
    /**
     * 获取设置信息.
     * @param $userID
     * @return mixed
     */
    function setting($userID)
    {
        $setting = (new \App\Models\System\Setting())->where('user_id', $userID)->first();

        return $setting;
    }
}
if (! function_exists('tbksetting')) {
    /**
     * 获取设置信息.
     * @param $userID
     * @return mixed
     */
    function tbksetting($userID)
    {
        $setting = \App\Models\Taoke\Setting::where('user_id', $userID)->first();

        return $setting;
    }
}

if (! function_exists('resort_array')) {
    /**
     * @param $array
     * @param $type  日 周 月 年 自定义天数
     * @return mixed
     */
    function resort_array($array, $type)
    {
        //array_pluck
        //2018-12-11 00
        //2018-12-11 01
        //2018-12-11 02
        $names = array_pluck($array, 'created_at');

        $array = [
            [
                'created_at' => '2018-12-11 14:01:20',
                'commission_amount' => '20',
            ],
            [
                'created_at' => '2018-12-11 14:01:20',
                'commission_amount' => '20',
            ],
        ];

        $array = [100, '200', 300, '400', 500];
        //只查询2018-12-11 00

        $filtered = array_where($array, function ($value, $key) {
            return str_contains($value['created_at'], '2018-12-11 00');
        });
    }
}
