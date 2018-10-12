<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Member\CreditLogRepository;
use App\Repositories\Interfaces\Member\MemberRepository;
use App\Repositories\Interfaces\Taoke\OrderRepository;
use App\Validators\Member\CreditLogValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * TODO
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
     * @var
     */
    protected $creditLogRepository;

    protected $validator;

    /**
     * ChartsController constructor.
     * @param OrderRepository $orderRepository
     * @param MemberRepository $memberRepository
     * @param CreditLogRepository $creditLogRepository
     * @param CreditLogValidator $validator
     */
    public function __construct(OrderRepository $orderRepository, MemberRepository $memberRepository,CreditLogRepository $creditLogRepository,CreditLogValidator $validator)
    {
        $this->orderRepository = $orderRepository;
        $this->memberRepository = $memberRepository;
        $this->creditLogRepository = $creditLogRepository;
        $this->validator = $validator;
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

    /**
     * 团队报表 可根据 今日，昨日，本月，上月查看
     * @return \Illuminate\Http\JsonResponse
     */
    public function team()
    {
        try {
            $teamData = $this->memberRepository->getTeamCharts();
            return json(1001, '获取成功', $teamData);
        }catch (\Exception $e){
            return json(4001,$e->getMessage());
        }
    }

    /**
     * 提现报表  显示 待结算 累计结算 累计提现
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw()
    {
        try {
            $this->validator->with(request()->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
        }catch (ValidatorException $e){
            return json(4001,$e->getMessageBag()->first());
        }
        try {
            $withdraw = $this->creditLogRepository->getWithdrawCharts();

            return json(1001, '获取成功', $withdraw);
        }catch (\Exception $e){
            return json(4001,$e->getMessage());
        }
    }

}