<?php

namespace App\Http\Controllers\Api\OfficialAccount\Alipay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MemberRepository;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * @var MemberRepository
     */
    protected $repository;

    /**
     * RegisterController constructor.
     * @param MemberRepository $repository
     */
    public function __construct(MemberRepository $repository)
    {
        $this->repository = $repository;
    }

    // TODO 支付宝H5注册
    public function index(Request $request)
    {
    }

    /**
     * @param $member
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($member)
    {
        $token = auth('member')->login($member);

        $data = [
            'member' => $member,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];

        return json(1001, '登录成功', $data);
    }
}
