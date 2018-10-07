<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\Taoke\OrderRepository;


/**
 * 订单管理
 * Class OrdersController
 * @package App\Http\Controllers\Api\Member
 */
class OrdersController extends Controller
{
    /**
     * @var
     */
    protected $repository;
    /**
     * OrdersController constructor.
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    //TODO 订单列表 可根据平台、时间查看
    /**
     * 订单列表
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function index()
    {
        try{
            return $this->repository->orderList();
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}