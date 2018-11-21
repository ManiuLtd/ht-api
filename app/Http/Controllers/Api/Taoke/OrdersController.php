<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18.
 */

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\UserCriteria;
use App\Criteria\OrderTypeCriteria;
use App\Criteria\DatePickerCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OrderRepository;
use App\Tools\Taoke\Taobao;

/**
 * 订单管理
 * Class OrdersController.
 */
class OrdersController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $repository;

    /**
     * OrdersController constructor.
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 订单列表.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $orders = $this->repository
                ->with('user')
                ->pushCriteria(new DatePickerCriteria())
                ->pushCriteria(new OrderTypeCriteria())
                ->pushCriteria(new UserCriteria())
                ->paginate(request('limit', 10));
            return json(1001, '列表获取成功', $orders);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 提交订单.
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit()
    {
        try {
            return $this->repository->submitOrder();
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }
}
