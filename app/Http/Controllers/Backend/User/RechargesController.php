<?php

namespace App\Http\Controllers\Backend\User;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Validators\User\RechargeValidator;
use App\Repositories\Interfaces\User\RechargeRepository;

/**
 * 充值记录
 * Class RechargesController.
 */
class RechargesController extends Controller
{
    /**
     * @var RechargeRepository
     */
    protected $repository;

    /**
     * @var RechargeValidator
     */
    protected $validator;

    /**
     * RechargesController constructor.
     *
     * @param RechargeRepository $repository
     * @param RechargeValidator $validator
     */
    public function __construct(RechargeRepository $repository, RechargeValidator $validator)
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
            ->with('user')
            ->paginate(request('limit', 10));

        return json(1001, '获取成功', $recharges);
    }
}
