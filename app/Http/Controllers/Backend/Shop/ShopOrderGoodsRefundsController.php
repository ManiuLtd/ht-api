<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopOrderGoodsRefundUpdateRequest;
use App\Repositories\Interfaces\ShopOrderGoodsRefundRepository;
use App\Validators\Shop\ShopOrderGoodsRefundValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ShopOrderGoodsRefundsController.
 */
class ShopOrderGoodsRefundsController extends Controller
{
    /**
     * @var ShopOrderGoodsRefundRepository
     */
    protected $repository;

    /**
     * @var ShopOrderGoodsRefundValidator
     */
    protected $validator;

    /**
     * ShopOrderGoodsRefundsController constructor.
     *
     * @param ShopOrderGoodsRefundRepository $repository
     * @param ShopOrderGoodsRefundValidator $validator
     */
    public function __construct(ShopOrderGoodsRefundRepository $repository, ShopOrderGoodsRefundValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 维权订单列表.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderGoodsRefunds = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with(['order', 'goods', 'member'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $orderGoodsRefunds);
    }

    /**
     * 维权订单详情.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $orderGoodsRefund = $this->repository->with(['order', 'goods', 'member'])->find($id);

        return json(1001, '详情获取成功', $orderGoodsRefund);
    }

    /**
     * 编辑维权订单.
     * @param ShopOrderGoodsRefundUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShopOrderGoodsRefundUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $orderGoodsRefund = $this->repository->update($request->all(), $id);

            return json(1001, '更新成功', $orderGoodsRefund);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除维权订单.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
