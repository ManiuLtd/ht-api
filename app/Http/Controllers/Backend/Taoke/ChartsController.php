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
     * CategoriesController constructor.
     *
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }


    public function order()
    {
        try {
            $chart = $this->repository->chart();

            return json(1001, '获取成功', $chart);

        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    public function member()
    {
        //type 1佣金 2数量
    }
}
