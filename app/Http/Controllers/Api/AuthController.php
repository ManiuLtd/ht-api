<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

/**
 * Class AuthController
 * @package App\Http\Controllers\Backend\Auth
 */
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware ('api', ['except' => ['login']]);
    }

    /**
     * admin/auth/login
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request (['name', 'password']);

        if (!$token = auth ()->attempt ($credentials)) {
            return json (4001, '账号或者密码错误');
        }

        return $this->respondWithToken ($token);
    }

    /**
     * 获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response ()->json (auth ()->user ());
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth ()->logout ();

        return response ()->json (['message' => 'Successfully logged out']);
    }

    /**
     * 刷新token
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken (auth ()->refresh ());
    }

    /**
     * 获取token
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth ()->factory ()->getTTL () * 60
        ];

        return json (1001, '登录成功', $data);
    }
}
