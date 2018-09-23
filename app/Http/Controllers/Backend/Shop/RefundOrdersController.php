<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\RefundOrderUpdateRequest;
use App\Repositories\Interfaces\Shop\RefundOrderRepository;
use App\Validators\Shop\RefundOrderValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class RefundOrdersController.
 */
class RefundOrdersController extends Controller
{
    /**
     * @var RefundOrderRepository
     */
    protected $repository;

    /**
     * @var RefundOrderValidator
     */
    protected $validator;

    /**
     * RefundOrdersController constructor.
     *
     * @param RefundOrderRepository $repository
     * @param RefundOrderValidator $validator
     */
    public function __construct(RefundOrderRepository $repository, RefundOrderValidator $validator)
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
     * @param RefundOrderUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RefundOrderUpdateRequest $request, $id)
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
