<?php

namespace App\Http\Controllers\Backend\Taoke;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OrderRepository;
use App\Repositories\Interfaces\User\UserRepository;
use App\Validators\Taoke\OrderValidator;
use App\Validators\User\UserValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $this->memberrepository = $memberrepository;
        $this->repository = $repository;
        $this->validator = $validator;
        $this->membervalidator = $membervalidator;
    }


    public function order()
    {
        //type 1佣金 2数量
//        try {
            $orderChart = $this->repository->orderChart();
            return json(1001, '获取成功', $orderChart);
//        } catch (\Exception $e) {
//            return json(5001, $e->getMessage());
//        }
    }

    public function member()
    {
        try {
            return $this->memberrepository->member();
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}
