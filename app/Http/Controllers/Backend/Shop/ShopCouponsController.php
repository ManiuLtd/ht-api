<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Validators\Shop\ShopCouponValidator;
use App\Http\Requests\Shop\ShopCouponCreateRequest;
use App\Http\Requests\Shop\ShopCouponUpdateRequest;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Interfaces\ShopCouponRepository;

/**
 * Class ShopCouponsController.
 */
class ShopCouponsController extends Controller
{
    /**
     * @var ShopCouponRepository
     */
    protected $repository;

    /**
     * @var ShopCouponValidator
     */
    protected $validator;

    /**
     * ShopCouponsController constructor.
     *
     * @param ShopCouponRepository $repository
     * @param ShopCouponValidator $validator
     */
    public function __construct(ShopCouponRepository $repository, ShopCouponValidator $validator)
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
     * @param ShopCouponCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShopCouponCreateRequest $request)
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
     * @param ShopCouponUpdateRequest $request
     * @param                         $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShopCouponUpdateRequest $request, $id)
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
