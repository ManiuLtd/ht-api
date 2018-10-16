<?php

namespace App\Listeners;

use App\Events\SendNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class SendNotificationListener
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
     * 极光推送消息
     * 文档：https://docs.jiguang.cn/jpush/server/push/rest_api_v3_push/.
     * @param SendNotification $event
     */
    public function handle(SendNotification $event)
    {
        $messages = $event->messages;
        $isAllAudience = $event->isAllAudience;
        $app_key = env('JPUSH_APP_KEY');
        $master_secret = env('JPUSH_MASTER_SECRET');
        $client = new \JPush\Client($app_key, $master_secret);

        $push = $client->push();
        //默认参数
        $push->options([
            'time_to_live' => 6000, //离线保存时间，单位是 秒
            'apns_production' => false, //注意环境问题，false 代表开发环境
        ]);

        //推送至所有平台
        $push->setPlatform('all');

        //判断是否为群发
        if (! $isAllAudience) {
            $tag = Hashids::encode($messages['member_id']);
            $push->addTag($tag);
            $insert['type'] = 1;
        } else {
            $push->addAllAudience();
            $insert['type'] = 2;
        }
        //推送消息
        $push->setMessage($messages['message'], $messages['title']);

        try {
            $push->send();
            $insert['user_id'] = $messages['user_id'];
            $insert['member_id'] = $messages['member_id'];
            $insert['title'] = $messages['title'];
            $insert['message'] = $messages['message'];
            $insert['created_at'] = now()->toDateTimeString();
            $insert['updated_at'] = now()->toDateTimeString();
            db('notifications')->insert($insert);
        } catch (\Exception $e) {
            Log::info('极光推送错误');
        }
    }
}
