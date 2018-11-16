<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Models\User\User;
use Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\Auth\User\LoginRequest;

/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    /**
     * 用户登录.
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            //验证字段
            $validator = \Validator::make (request ()->all (), [
                'phone' => 'required',
                'code' => 'required',
            ]);
            //字段验证失败
            if ($validator->fails ()) {
                return json (4001, $validator->errors ()->first ());
            }
            $phone = request ('phone');
            $code = request ('code');
            //检查验证码
            if (!checkSms ($phone, $code)) {
                return json (4001, '验证码错误');
            }
            $user = User::query ()->where ([
                'phone' => $phone,
            ])->first ();
            //TODO 判断权限

            if (!$user) {
                return json (4001, '用户不存在');
            }
            $token = auth ()->login ($user);

            return $this->respondWithToken ($token);

        } catch (\Exception $e) {

            return json (5001, $e->getMessage ());
        }


    }

    /**
     * 获取token.
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [
            'role' => 'admin',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth ()->factory ()->getTTL () * 60,
        ];

        return json (1001, '登录成功', $data);
    }
}
