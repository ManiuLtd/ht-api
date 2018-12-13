<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OrderRepository;
use App\Validators\Taoke\OrderValidator;

/**
 * Class CategoriesController.
 */
class ChartsController extends Controller
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


    public function order()
    {
        //type 1佣金 2数量
        try {
            $orderChart = $this->repository->orderChart();
            return json(1001, '获取成功', $orderChart);
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    public function member()
    {
        //type 1佣金 2数量
    }
}
