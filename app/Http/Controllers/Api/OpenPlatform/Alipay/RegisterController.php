<?php

namespace App\Http\Controllers\Api\OpenPlatform\Alipay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    // TODO 支付宝app注册
    public function index(Request $request)
    {
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
