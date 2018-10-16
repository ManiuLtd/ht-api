<?php
namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Member\MemberRepository;

/**
 * 会员管理
 * Class MemberController.
 */
class MembersController extends Controller
{
    /**
     * @var MemberRepository
     */
    protected $repository;

    /**
     * MemberController constructor.
     * @param MemberRepository $repository
     */
    public function __construct(MemberRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 获取当前会员信息，包括用户等级等.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $memberId = getMemberId();
        $members = $this->repository
            ->with(['level', 'inviter', 'group'])
            ->find($memberId);

        return json(1001, '会员信息获取成功', $members);
    }

    /**
     * 好友列表  可根据inviter_id查看.
     * @return \Illuminate\Http\JsonResponse
     */
    public function friends()
    {
        try {
            return $this->repository->getFrineds();
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
