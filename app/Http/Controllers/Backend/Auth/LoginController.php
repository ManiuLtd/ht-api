<?php

namespace App\Http\Controllers\Backend\Auth;

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
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            $token = Auth::guard()->attempt($credentials);

            if (! $token) {
                return json(4001, '用户登录失败');
            }
        } catch (JWTException $e) {
            return json(5001, $e->getMessage());
        }

        return $this->respondWithToken($token);
    }

    /**
     * 获取token.
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];

        return json(1001, '登录成功', $data);
    }
}
