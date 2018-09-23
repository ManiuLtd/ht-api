<?php

namespace App\Handler;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;

/**
 * Class MediaMessageHandler.
 */
class MediaMessageHandler implements EventHandlerInterface
{
    /**
     * @var
     */
    protected $app;

    /**
     * MediaMessageHandler constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @param array $payload
     * @return bool|string|void
     */
    public function handle($payload = null)
    {
        $openID = $payload['FromUserName'];

        $this->app->customer_service->message('接受到了视频、短视频或者语音')->to($openID)->send();
    }
}
