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

            $encode = base64_encode (request ('redirect_url', 'sb') . '!' . request ('inviter', 'sb'));


            $redirectUrl = route ('wechat.callback',[$encode]);

            $response = $app->oauth->scopes (['snsapi_userinfo'])->redirect ($redirectUrl);

            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage ());
        }
    }

    /**
     * @param $encode  http://htapi.vaiwan.com/wechat/official_account/login?redirect_url=http://www.baidu.com&inviter=0v07r6
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     * @throws \Exception
     */
    public function callback($encode)
    {

        $str = base64_decode ($encode);

        $decode = explode ('!', $str);
        try {
            $app = Facade::officialAccount ();

            $user = $app->oauth->user ();

            $original = $user->getOriginal ();

            $user = User::query ()->where ([
                'wx_unionid' => $original['unionid'],
            ])->first ();

            // 用户存在， 登陆
            if ($user) {
                if ($decode[1] != 'sb') {
                    try {
                        $this->repository->bindinviterRegister ($user, $decode[1]);
                    } catch (\Exception $exception) {
                    }
                }
                if ($original['unionid'] != null) {
                    $user->update ([
                        'headimgurl' => $original['headimgurl'],
                        'nickname' => $original['nickname'],
                    ]);
                }


            } else {
                $level = Level::query ()->where ('default', 1)->first ();
                // 用户不存在，注册
                if ($original['unionid'] != null) {
                    $insert = [
                        'wx_unionid' => $original['unionid'],
                        'headimgurl' => $original['headimgurl'],
                        'nickname' => $original['nickname'],
                        'level_id' => $level->id,
                    ];

                    $user = User::query()->create($insert);
                }
                if ($decode[1] != 'sb') {
                    try {
                        $this->repository->bindinviterRegister ($user, $decode[1]);
                    } catch (\Exception $exception) {
                    }
                }
            }

            if ($decode[0] != 'sb') {
                return redirect ($decode[0]);

            }
            return "未指定重定向地址";
        } catch (JWTException $e) {
            throw  new \Exception($e->getMessage ());
        }
    }


}
