<?php

namespace App\Http\Controllers\Wechat;

use App\Handler\EventMessageHandler;
use App\Handler\ImageMessageHandler;
use App\Handler\MediaMessageHandler;
use App\Handler\TextMessageHandler;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Message;
use Overtrue\LaravelWeChat\Facade;


/**
 * 微信公众号开发请搭配内网穿透工具，这样方便本地调试
 * 工具地址：https://open-doc.dingtalk.com/microapp/debug/ucof2g
 * Class OfficialAccountController
 * @package App\Http\Controllers\Wechat
 */
class OfficialAccountController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
        $app = Facade::officialAccount ();

        $app->server->push (new TextMessageHandler($app), Message::TEXT);
        $app->server->push (new ImageMessageHandler($app), Message::IMAGE);
        $app->server->push (new EventMessageHandler($app), Message::EVENT);
        $app->server->push (new MediaMessageHandler($app), Message::VOICE | Message::VIDEO | Message::SHORT_VIDEO);


        return $app->server->serve ();
    }

}
