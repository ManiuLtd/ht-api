<?php

namespace App\Listeners;

use App\Events\SendSMS;
use InvalidArgumentException;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Cache;

class SendSMSNotification
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
     * @param SendSMS $event
     * @throws \Exception
     */
    public function handle(SendSMS $event)
    {
        $phone = $event->phone;
        //验证手机号
        if (! preg_match("/^1[3456789]\d{9}$/", $phone)) {
            throw  new InvalidArgumentException('手机号格式错误');
        }
        //60秒内只可以发送一次验证码
        $lastSendTime = cache('SMS_'.$phone);
        if (! $lastSendTime) {
            $lastSendTime = time();
            Cache::put('SMS_'.$phone, $lastSendTime, now()->addSecond(60));
        } elseif (time() - $lastSendTime < 60) {
            throw  new InvalidArgumentException('验证码发送过于频繁');
        }

        $sms = new EasySms(config('sms'));

        //消息内容
        $message = [
            'content' => $event->content,
            'template' => $event->template,
            'data' => [
                'code' => $event->code,
            ],
        ];

        try {
            $sms->send($phone, $message, $event->gateways);
        } catch (\Exception $e) {
            throw  new InvalidArgumentException($e->getMessage());
        }
    }
}
