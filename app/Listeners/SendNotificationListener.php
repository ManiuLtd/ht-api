<?php

namespace App\Listeners;

use App\Events\SendNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
     * Handle the event.
     *
     * @param  SendNotification $event
     * @return void
     */
    public function handle(SendNotification $event)
    {
        $messages = $event->messages;
        $isAllAudience = $event->isAllAudience;
        $this->pushMsg($messages, $isAllAudience);
    }

    /**
     * 推送消息 并写入日志
     * @param $messages
     * @param $isAllAudience
     */
    protected function pushMsg($messages, $isAllAudience)
    {
        $app_key = env('JPUSH_APP_KEY','bd4666b8f14622d415153481');
        $master_secret = env('JPUSH_MASTER_SECRET','52ebeda1cc40f6be53c78a28');;
        $client = new \JPush\Client($app_key, $master_secret);
        $push = $client->push();
        $push->options([
            "time_to_live" => 6000,//离线保存时间，单位是 秒
            "apns_production" => true //注意环境问题，false 代表开发环境
        ]);
        $push->setPlatform('all');

        //判断是否为群发
        if (!$isAllAudience) {
            $tag = Hashids::encode($messages['member_id']);
            $push->addTag($tag);
            $insert['type'] = 2;

        } else {
            $push->addAllAudience();
            $insert['type'] = 1;
        }
        $push->setNotificationAlert($messages['message']);

        try {
            $result = $push->send();
            $insert['sendno'] = $result['body']['sendno'];
            $insert['msg_id'] = $result['body']['msg_id'];
            $insert['user_id'] = $messages['user_id'];
            $insert['member_id'] = $messages['member_id'];
            $insert['title'] = $messages['title'];
            $insert['logo'] = $messages['logo'];
            $insert['message'] = $messages['message'];
            $insert['created_at'] = now()->toDateTimeString();
            $insert['updated_at'] = now()->toDateTimeString();
            DB::table('notifications')->insert($insert);
        } catch (\JPush\Exceptions\JPushException $e) {
            Log::info($e->getMessage());
        }

    }
}
