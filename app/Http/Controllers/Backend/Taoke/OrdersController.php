<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Criteria\OrderCriteria;
use App\Criteria\UserCriteria;
use App\Http\Controllers\Controller;
use App\Validators\Taoke\OrderValidator;
use App\Repositories\Interfaces\Taoke\OrderRepository;

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
     * CategoriesController constructor.
     *
     * @param OrderRepository $repository
     * @param OrderValidator $validator
     */
    public function __construct(OrderRepository $repository, OrderValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * 订单列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = $this->repository
            ->pushCriteria(new OrderCriteria())
            ->with(['user', 'group', 'oldGroup'])->paginate(request('limit', 10));

        return json(1001, '列表获取成功', $orders);
    }

    /**
     * 订单详情.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $orders = $this->repository->with(['user', 'group', 'oldGroup'])->find($id);

        return json(1001, '详情获取成功', $orders);
    }
}
