<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Member;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Member\MemberRepository;

/**
 * 会员管理
 * Class MemberController
 * @package App\Http\Controllers\Api\Member
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
     * 获取当前会员信息，包括用户等级等
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $memberId = getMemberId ();
            $members = $this->repository
                ->with (['commissionLevel', 'inviter','group'])
                ->find ($memberId);

            return json (1001, '会员信息获取成功', $members);
        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }

    /**
     * 好友列表  可根据inviter_id查看
     * @return \Illuminate\Http\JsonResponse
     */
    public function friends()
    {

        try {
            return $this->repository->getFrineds ();
        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }
}