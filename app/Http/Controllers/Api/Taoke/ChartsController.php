<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Member\MemberRepository;
use App\Repositories\Interfaces\Taoke\OrderRepository;

/**
 * 报表
 * Class ChartsController
 * @package App\Http\Controllers\Api\Member
 */
class ChartsController extends Controller
{
    /**
     * @var
     */
    protected $orderRepository;

    /**
     * @var
     */
    protected $memberRepository;

    /**
     * ChartsController constructor.
     * @param OrderRepository $orderRepository
     * @param MemberRepository $memberRepository
     */
    public function __construct(OrderRepository $orderRepository, MemberRepository $memberRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->memberRepository = $memberRepository;

    }

    /**
     * 订单报表 可根据 今日，昨日，本月，上月查看
     * @return \Illuminate\Http\JsonResponse
     */
    public function order()
    {
        try {
            $data = $this->orderRepository->getOrderCharts();
            return json(1001, '获取成功', $data);
        }catch (\Exception $e) {
            return json(5001,$e->getMessage());
        }
    }

    //TODO 团队报表 可根据 今日，昨日，本月，上月查看
    public function team()
    {
        $teamData = $this->memberRepository->getTeamCharts();
        dd($teamData);
    }

    //TODO 提现报表  显示 待结算 累计结算 累计提现
    public function withdraw()
    {

    }

}