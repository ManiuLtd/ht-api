<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\SignUpRequest;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * 注册用户.
     * @param SignUpRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(SignUpRequest $request, JWTAuth $JWTAuth)
    {
        $validate = $this->validator($request->all());

        if ($validate->errors()->first()) {
            return json(4001, $validate->errors()->first());
        }

        $user = $this->create($request->all());

        if (! $user) {
            return json(4001, '用户注册失败');
        }

        $token = $JWTAuth->fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * 获取Token.
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

        return json(1001, '注册成功', $data);
    }

    /**
     * 验证密码
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'name.required' => '请填写用户名',
            'name.unique' => '用户已存在',
            'email.required' => '请填写邮箱',
            'email.unique' => '邮箱已存在',
            'email.email' => '邮箱格式错误',
            'password.required' => '请填写密码',
            'password.min' => '密码最低为6个字符',
        ];

        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], $message);
    }

    /**
     * 创建用户.
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'created_at' => Carbon::now(),
        ]);
    }
}
