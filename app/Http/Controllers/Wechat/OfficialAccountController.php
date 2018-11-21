<?php

namespace App\Http\Controllers\Wechat;

use App\Models\User\Level;
use App\Models\User\User;
use App\Repositories\Interfaces\User\UserRepository;
use Overtrue\LaravelWeChat\Facade;
use App\Handler\TextMessageHandler;
use App\Handler\EventMessageHandler;
use App\Handler\ImageMessageHandler;
use App\Handler\MediaMessageHandler;
use App\Http\Controllers\Controller;
use EasyWeChat\Kernel\Messages\Message;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * 微信公众号开发请搭配内网穿透工具，这样方便本地调试
 * 工具地址：https://open-doc.dingtalk.com/microapp/debug/ucof2g
 * Class OfficialAccountController.
 */
class OfficialAccountController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $repository;


    /**
     * OfficialAccountController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

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

            $redirectUrl = route ('wechat.callback', [
                'redirect_url' => request ('redirect_url'),
                'inviter' => request ('inviter'),
            ]);

            $response = $app->oauth->scopes (['snsapi_userinfo'])
                ->redirect ($redirectUrl);

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage ());
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function callback()
    {
        try {
            $app = Facade::officialAccount ();

            $user = $app->oauth->user ();

            dd (request ('redirect_url'));
            $original = $user->getOriginal ();

            $user = User::query ()->where ([
                'wx_unionid' => $original['unionid'],
            ])->first ();

            // 用户存在， 登陆
            if ($user) {
                if (request ('inviter')) {
                    try {

                        $this->repository->bindinviterRegister ($user, request ('inviter'));
                    } catch (\Exception $exception) {
                    }
                }
                $user->update ([
                    'headimgurl' => $original['headimgurl'],
                    'nickname' => $original['nickname'],
                ]);

                // 重定向
                return redirect (request ('redirect_url'));
            }
            $level = Level::query ()->where ('default', 1)->first ();
            // 用户不存在，注册
            $insert = [
                'wx_unionid' => $original['unionid'],
                'headimgurl' => $original['headimgurl'],
                'nickname' => $original['nickname'],
                'level_id' => $level->id,
            ];

            $user = User::query ()->create ($insert);
            if (request ('inviter')) {
                try {
                    $this->repository->bindinviterRegister ($user, request ('inviter'));
                } catch (\Exception $exception) {
                }
            }
            // 重定向
            return redirect (request ('redirect_url'));
        } catch (JWTException $e) {
            throw  new \Exception($e->getMessage ());
        }
    }


}
