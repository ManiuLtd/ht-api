<?php

namespace App\Handler;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;


/**
 * Class ImageMessageHandler
 * @package App\Handler
 */
class ImageMessageHandler implements EventHandlerInterface
{

    /**
     * @var
     */
    protected $app;

    /**
     * ImageMessageHandler constructor.
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

        $this->app->customer_service->message ("接收到了图片")->to ($openID)->send ();

    }


}
