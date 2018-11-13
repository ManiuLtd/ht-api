<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Validators\Shop\CouponLogValidator;
use App\Repositories\Interfaces\Shop\CouponLogRepository;

/**
 * Class CouponLogsController.
 */
class CouponLogsController extends Controller
{
    /**
     * @var CouponLogRepository
     */
    protected $repository;

    /**
     * @var CouponLogValidator
     */
    protected $validator;

    /**
     * CouponLogsController constructor.
     *
     * @param CouponLogRepository $repository
     * @param CouponLogValidator $validator
     */
    public function __construct(CouponLogRepository $repository, CouponLogValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 优惠券领取日志列表.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $couponLogs = $this->repository->with(['user', 'coupon'])->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $couponLogs);
    }

    /**
     * 日志详情.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $couponLog = $this->repository->with(['user', 'coupon'])->find($id);

        return json(1001, '详情获取成功', $couponLog);
    }
}
