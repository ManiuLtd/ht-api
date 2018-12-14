<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OrderRepository;
use App\Repositories\Interfaces\User\UserRepository;


/**
 * Class CategoriesController.
 */
class ChartsController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ChartsController constructor.
     * @param OrderRepository $orderRepository
     * @param UserRepository $userRepository
     */
    public function __construct(OrderRepository $orderRepository,UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }


    /**
     * 订单报表
     * @return \Illuminate\Http\JsonResponse
     */
    public function order()
    {
        try {
            $chart = $this->orderRepository->chart();

            return json(1001, '获取成功', $chart);

        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    /**
     * 会员统计
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        try {

            $chart = $this->userRepository->chart();

            return json(1001, '获取成功', $chart);

        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}
