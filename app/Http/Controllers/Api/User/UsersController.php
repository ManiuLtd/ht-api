<?php

namespace App\Http\Controllers\Api\User;

use App\Models\User\Group;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\User\UserRepository;

/**
 * 会员管理
 * Class UserController.
 */
class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * UserController constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 获取当前会员信息，包括用户等级等.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $userId = getUserId();
        $users = $this->repository
            ->withCount('friends')
            ->with(['level', 'inviter', 'group'])
            ->find($userId);

        return json(1001, '会员信息获取成功', $users);
    }

    /**
     * 好友列表  可根据inviter_id查看.
     * @return \Illuminate\Http\JsonResponse
     */
    public function friends()
    {
        try {
            $friends = $this->repository->getFrineds();

            return json(1001, '获取成功', $friends);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 根据邀请码查看用户信息.
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function inviter()
    {
        try {
            $inviter = $this->repository->getInviter();

            return json(1001, '获取成功', $inviter);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 绑定手机号.
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function bindMobile()
    {
        try {
            $this->repository->bindMobile();

            return json('1001', '手机号绑定成功');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 绑定邀请人.
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function bindInviter()
    {
        try {
            $this->repository->bindInviter();

            return json('1001', '上级绑定成功');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 手动升级.
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function checkUpgrade()
    {
        try {
            $this->repository->checkUpgrade();

            return json('1001', '升级成功');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 绑定支付宝.
     * @return \Illuminate\Http\JsonResponse
     */
    public function bindAlipay()
    {
        try {
            $this->repository->bindAlipay();

            return json('1001', '支付宝绑定成功');
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
