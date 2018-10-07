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
use Tymon\JWTAuth\Contracts\Providers\Auth;


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
            $order = $this->repository
                ->findWhere(['member_id'=>1])
//                ->with('member')
                ->paginate('limit',10);
            return json(1001, '列表获取成功', $order);
        }catch (\Exception $e){
            return json(5001,$e->getMessage());
        }
    }

    //TODO 手动提交订单 存到tbk_member_orders  ，使用脚本订单读取这个表中的订单号，和tbk_order里面的订单绑定
    public function  submit()
    {

    }
}