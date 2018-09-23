<?php

namespace App\Http\Controllers\Wechat;

use App\Handler\EventMessageHandler;
use App\Handler\ImageMessageHandler;
use App\Handler\MediaMessageHandler;
use App\Handler\TextMessageHandler;
use App\Handler\TransferMessageHandler;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Message;
use Overtrue\LaravelWeChat\Facade;

/**
 * 微信小程序客服消息接入
 * Class OfficialAccountController.
 */
class MiniProgramController extends Controller
{
    /**
     * 文档地址：https://www.easywechat.com/docs/master/zh-CN/mini-program/customer_service.
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
        $app = Facade::miniProgram();

        $app->server->push(new TextMessageHandler($app), Message::TEXT);
        $app->server->push(new ImageMessageHandler($app), Message::IMAGE);
        $app->server->push(new EventMessageHandler($app), Message::EVENT);
        $app->server->push(new MediaMessageHandler($app), Message::VOICE | Message::VIDEO | Message::SHORT_VIDEO);
        $app->server->push(new TransferMessageHandler($app), Message::TRANSFER);

        return $app->server->serve();
    }
}
