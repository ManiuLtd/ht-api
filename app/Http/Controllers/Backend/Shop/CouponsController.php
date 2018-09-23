<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Validators\Shop\CouponValidator;
use App\Http\Requests\Shop\CouponCreateRequest;
use App\Http\Requests\Shop\CouponUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\Shop\CouponRepository;

/**
 * Class CouponsController.
 */
class CouponsController extends Controller
{
    /**
     * @var CouponRepository
     */
    protected $repository;

    /**
     * @var CouponValidator
     */
    protected $validator;

    /**
     * CouponsController constructor.
     *
     * @param CouponRepository $repository
     * @param CouponValidator $validator
     */
    public function __construct(CouponRepository $repository, CouponValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 优惠券列表.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $coupons = $this->repository->with('category')->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $coupons);
    }

    /**
     * 添加优惠券.
     *
     * @param CouponCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $coupon = $this->repository->create($request->all());

            return json(1001, '创建成功', $coupon);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 优惠券详情.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $coupon = $this->repository->with('category')->find($id);

        return json(1001, '详情获取成功', $coupon);
    }

    /**
     * 编辑优惠券.
     *
     * @param CouponUpdateRequest $request
     * @param                         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $coupon = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $coupon);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除优惠券.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
