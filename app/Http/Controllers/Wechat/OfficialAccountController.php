<?php

namespace App\Http\Controllers\Wechat;

use Overtrue\LaravelWeChat\Facade;
use App\Handler\TextMessageHandler;
use App\Handler\EventMessageHandler;
use App\Handler\ImageMessageHandler;
use App\Handler\MediaMessageHandler;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Message;

/**
 * 微信公众号开发请搭配内网穿透工具，这样方便本地调试
 * 工具地址：https://open-doc.dingtalk.com/microapp/debug/ucof2g
 * Class OfficialAccountController.
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

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function login()
    {
        try {

            $app = Facade::officialAccount ();

            $redirectUrl = route('wechat.callback', [
                'redirect_url' => request('redirect_url'),
                'inviter' => request('inviter'),
            ]);

            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect($redirectUrl);

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage ());
        }
    }

    /**
     * 回调
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function callback()
    {

        $app = Facade::officialAccount ();

        $user = $app->oauth->user();

        $original = $user->getOriginal();

//        $res = $this->memberRepository->h5Login($original);
//
//        return redirect(route('mobile.download.index'));

    }
}
