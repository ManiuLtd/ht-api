<?php

namespace App\Http\Controllers\Api\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interfaces\User\UserRepository;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * RegisterController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    // TODO  支付宝小程序注册
    public function index(Request $request)
    {
    }

    /**
     * 验证密码
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'code.required' => 'code is missing',
            'encryptedData.required' => 'encryptedData is missing',
            'iv.required' => 'iv is missing',
        ];

        return Validator::make($data, [
            'code' => 'required',
            'encryptedData' => 'required',
            'iv' => 'required',
        ], $message);
    }

    /**
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($user)
    {
        $token = auth('user')->login($user);

        $data = [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];

        return json(1001, '登录成功', $data);
    }
}
