<?php

namespace App\Http\Controllers\Backend\Shop;

use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Shop\ShopOrderUpdateRequest;
use App\Repositories\Interfaces\ShopOrderRepository;
use App\Validators\Shop\ShopOrderValidator;

/**
 * Class ShopOrdersController.
 *
 * @package namespace App\Http\Controllers;
 */
class ShopOrdersController extends Controller
{

    /**
     * @var ShopOrderRepository
     */
    protected $repository;

    /**
     * @var ShopOrderValidator
     */
    protected $validator;

    /**
     * ShopOrdersController constructor.
     * @param ShopOrderRepository $repository
     * @param ShopOrderValidator $validator
     */
    public function __construct(ShopOrderRepository $repository, ShopOrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     *  订单列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = $this->repository
            ->pushCriteria (new DatePickerCriteria())
            ->with (['goods', 'member'])
            ->paginate (request ('limit') ?? 10);

        return json (1001, '列表获取成功', $orders);
    }


    /**
     * 订单详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $order = $this->repository->with (['goods', 'member'])->find ($id);

        return json (1001, "详情获取成功", $order);
    }

    /**
     * 编辑订单
     * @param ShopOrderUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ShopOrderUpdateRequest $request, $id)
    {
        try {
            $this->validator->with ($request->all ())->passesOrFail (ValidatorInterface::RULE_UPDATE);

            $insert = $request->all ();

            //TODO 根据status处理订单结果
            $order = $this->repository->update ($insert, $id);

            return json (1001, "更新成功", $order);

        } catch (ValidatorException $e) {

            return json (5001, $e->getMessageBag ());
        }
    }


    /**
     * 删除订单
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->repository->delete ($id);

        return json (1001, "删除成功");
    }
}
