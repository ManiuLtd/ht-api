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

