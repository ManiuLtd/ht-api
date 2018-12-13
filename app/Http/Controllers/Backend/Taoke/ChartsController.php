<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OrderRepository;
use App\Repositories\Interfaces\User\UserRepository;
use App\Validators\Taoke\OrderValidator;
use App\Validators\User\UserValidator;

/**
 * Class CategoriesController.
 */
class ChartsController extends Controller
{
    /**
     * @var OrderRepository
     */
    protected $repository;
    protected $memberrepository;

    /**
     * @var OrderValidator
     */
    protected $validator;
    protected $membervalidator;

    /**
     * CategoriesController constructor.
     *
     * @param OrderRepository $repository
     * @param OrderValidator $validator
     */
    public function __construct(OrderRepository $repository,UserRepository $memberrepository, OrderValidator $validator,UserValidator $membervalidator)
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


    }
}
