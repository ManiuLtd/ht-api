<?php

namespace App\Handler;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;

/**
 * Class TransferMessageHandler.
 */
class TransferMessageHandler implements EventHandlerInterface
{
    /**
     * @var
     */
    protected $app;

    /**
     * TransferMessageHandler constructor.
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

        //打开客服会话
        if ($payload['Event'] == 'user_enter_tempsession') {
            $this->app->customer_service->message('我是微信小程序客服消息')->to($openID)->send();

            return;
        }
        $this->app->customer_service->message('我是其他的客服消息')->to($openID)->send();
    }
}
