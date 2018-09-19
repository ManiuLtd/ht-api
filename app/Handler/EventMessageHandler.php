<?php

namespace App\Handler;

use EasyWeChat\Kernel\Contracts\EventHandlerInterface;


/**
 * Class EventMessageHandler
 * @package App\Handler
 */
class EventMessageHandler implements EventHandlerInterface
{

    /**
     * @var
     */
    protected $app;

    /**
     * EventMessageHandler constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 事件消息:关注公众号、取消关注关注使用、点击菜单、上报地址位置等
     * 文档地址：https://www.easywechat.com/docs/master/zh-CN/official-account/server
     * @param array $payload
     * @return bool|string|void
     */
    public function handle($payload = null)
    {

        $openID = $payload['FromUserName'];

        $message = "Hello World";

        switch ($payload['Event']) {
            case 'subscribe':
                //TODO 更新用户信息
                //如果是扫码关注 ，可以取到两个额外参数:
                //- EventKey    事件KEY值，比如：qrscene_123123，qrscene_为前缀，后面为二维码的参数值
                //- Ticket      二维码的 ticket，可用来换取二维码图片
                $message = "感谢关注";
                break;
            case 'unsubscribe':
                //TODO 取消关注后需要执行的操作
                break;
            case 'CLICK':
                $message = '用户点击了菜单' . $payload['EventKey'];
                break;
            case 'LOCATION':
                $message = "用户上报了地理位置";
                break;
            default:
                $message = "这是其他未处理的事件";
                break;

        }

        $this->app->customer_service->message ($message)->to ($openID)->send ();

    }


}
