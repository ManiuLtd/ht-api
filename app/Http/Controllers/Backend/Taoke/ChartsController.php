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
    }

    public function member()
    {
        //type 1佣金 2数量
    }
}
