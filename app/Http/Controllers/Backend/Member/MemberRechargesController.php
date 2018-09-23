<?php

namespace App\Http\Controllers\Backend\Member;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Member\MemberRechargeValidator;
use App\Repositories\Interfaces\RechargeRepository;

/**
 * 充值记录
 * Class MemberRechargesController.
 */
class MemberRechargesController extends Controller
{
    /**
     * @var RechargeRepository
     */
    protected $repository;

    /**
     * @var MemberRechargeValidator
     */
    protected $validator;

    /**
     * MemberRechargesController constructor.
     *
     * @param RechargeRepository $repository
     * @param MemberRechargeValidator $validator
     */
    public function __construct(RechargeRepository $repository, MemberRechargeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 充值列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $recharges = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with('member')
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $recharges);
    }
}
