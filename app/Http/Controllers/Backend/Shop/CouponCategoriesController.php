<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CouponCategoryCreateRequest;
use App\Http\Requests\Shop\CouponCategoryUpdateRequest;
use App\Repositories\Interfaces\Shop\CouponCategoryRepository;
use App\Validators\Shop\CouponCategoryValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class CouponCategoriesController.
 */
class CouponCategoriesController extends Controller
{
    /**
     * @var CouponCategoryRepository
     */
    protected $repository;

    /**
     * @var CouponCategoryValidator
     */
    protected $validator;

    /**
     * CouponCategoriesController constructor.
     *
     * @param CouponCategoryRepository $repository
     * @param CouponCategoryValidator $validator
     */
    public function __construct(CouponCategoryRepository $repository, CouponCategoryValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 优惠券分类列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $couponCategories = $this->repository->all();

        return json(1001, '列表获取成功', $couponCategories);
    }

    /**
     * 添加优惠券分类.
     * @param CouponCategoryCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponCategoryCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $couponCategory = $this->repository->create($request->all());

            return json(1001, '创建成功', $couponCategory);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 编辑优惠券分类.
     * @param CouponCategoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponCategoryUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $couponCategory = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $couponCategory);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除优惠券分类.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //检查该分类是否包含优惠券
        $coupon = db('coupons')
            ->where('category_id', $id)
            ->first();
        if ($coupon) {
            return json(4001, '删除失败，禁止删除包含优惠券的分类');
        }

        //删除分类
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
