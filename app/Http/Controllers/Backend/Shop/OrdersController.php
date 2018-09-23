<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\OrderUpdateRequest;
use App\Repositories\Interfaces\Shop\OrderRepository;
use App\Validators\Shop\OrderValidator;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class OrdersController.
 */
class OrdersController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $repository;

    /**
     * @var OrderValidator
     */
    protected $validator;

    /**
     * OrdersController constructor.
     * @param OrderRepository $repository
     * @param OrderValidator $validator
     */
    public function __construct(OrderRepository $repository, OrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  订单列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = $this->repository
            ->pushCriteria(new DatePickerCriteria())
            ->with(['goods', 'member', 'address'])
            ->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $orders);
    }

    /**
     * 订单详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $order = $this->repository->with(['subOrders', 'member'])->find($id);

        return json(1001, '详情获取成功', $order);
    }

    /**
     * 编辑订单.
     * @param OrderUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OrderUpdateRequest $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $insert = $request->all();

            //TODO 根据status处理订单结果
            $order = $this->repository->update($insert, $id);

            return json(1001, '更新成功', $order);
        } catch (ValidatorException $e) {
            return json(5001, $e->getMessageBag());
        }
    }

    /**
     * 删除订单.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return json(1001, '删除成功');
    }
}
