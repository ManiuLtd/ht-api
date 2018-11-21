<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User\Level;
use App\Models\User\User;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\Auth\User\LoginRequest;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function login(LoginRequest $request)
    {
        switch (request('type')) {
            case 'wechat':
                return $this->wechatLogin();
                break;
            case 'phone':
                return $this->phoneLogin();
                break;
            default:
                return json(4001, 'login type error');
        }
    }

    /**
     * 获取token.
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user,
        ];

        return json(1001, '登录成功', $data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function wechatLogin()
    {
        //验证字段
        $validator = \Validator::make(request()->all(), [
            'unionid' => 'required',
            'openid' => 'required',
            'headimgurl' => 'required',
            'nickname' => 'required',
        ]);
        //字段验证失败
        if ($validator->fails()) {
            throw  new \Exception($validator->errors()->first());
        }

        try {
            $user = User::query()->where([
                'wx_unionid' => request('unionid'),
            ])->first();

            // 用户存在， 登陆
            if ($user) {
                //TODO request('inviter') 如果存在绑定上级
                $user->update([
                    'headimgurl' => request('headimgurl'),
                    'nickname' => request('nickname'),
                ]);
                $token = auth()->login($user);

                return $this->respondWithToken($token, $user);
            }
            $level = Level::query()->where('default',1)->first();
            // 用户不存在，注册
            $insert = [
                'wx_unionid' => request('unionid'),
                'wx_openid1' => request('openid'),
                'headimgurl' => request('headimgurl'),
                'nickname' => request('nickname'),
                'level_id' => $level->id,
            ];

            $user = User::query()->create($insert);
            $token = auth()->login($user);

            return $this->respondWithToken($token, $user);
        } catch (JWTException $e) {
            throw  new \Exception($e->getMessage());
        }
    }

    /***
     * @return \Illuminate\Http\JsonResponse
     */
    protected function phoneLogin()
    {
        $credentials = request()->only(['phone', 'password']);
        try {
            $token = auth()->attempt($credentials);
            if (! $token) {
                return json(4001, '用户登录失败');
            }
            $user = auth()->user();
        } catch (JWTException $e) {
            return json(5001, $e->getMessage());
        }

        return $this->respondWithToken($token, $user);
    }
}
