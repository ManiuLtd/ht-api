<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;

use App\Criteria\DatePickerCriteria;
use App\Criteria\MemberCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\Taoke\MemberOrderCreateRequest;
use App\Repositories\Interfaces\Taoke\OrderRepository;


/**
 * 订单管理
 * Class OrdersController
 * @package App\Http\Controllers\Api\Taoke
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
     * TODO 新建一个查询条件，可查看团队订单和订单：https://dev.tencent.com/u/cnsecer/p/tbkapp/git/blob/master/app/Repositories/OrderRepository.php
     *
     * 订单列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $orders = $this->repository
                ->with ('member')
                ->pushCriteria (new DatePickerCriteria())
                ->pushCriteria (new MemberCriteria())
                ->paginate (request ('limit', 10));

            return json (1001, '列表获取成功', $orders);
        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }

    /**
     * 提交订单
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit()
    {
        try{
            return $this->repository->submitOrder();
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }
}