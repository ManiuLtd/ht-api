<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Shop\ShopCouponLogCreateRequest;
use App\Repositories\Interfaces\ShopCouponLogRepository;
use App\Validators\Shop\ShopCouponLogValidator;


/**
 * Class ShopCouponLogsController.
 *
 * @package namespace App\Http\Controllers;
 */
class ShopCouponLogsController extends Controller
{
    /**
     * @var ShopCouponLogRepository
     */
    protected $repository;

    /**
     * @var ShopCouponLogValidator
     */
    protected $validator;

    /**
     * ShopCouponLogsController constructor.
     *
     * @param ShopCouponLogRepository $repository
     * @param ShopCouponLogValidator $validator
     */
    public function __construct(ShopCouponLogRepository $repository, ShopCouponLogValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }


    /**
     * 优惠券领取日志列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $couponLogs = $this->repository->with (['member', 'coupon'])->paginate (request ('limit',10));

        return json (1001, '列表获取成功', $couponLogs);

    }


    /**
     * 日志详情
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $couponLog = $this->repository->with (['member', 'coupon'])->find ($id);

        return json (1001, "详情获取成功", $couponLog);

    }


}
