<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Http\Controllers\Controller;

/**
 * 订单管理
 * Class OrdersController
 * @package App\Http\Controllers\Api\Member
 */
class OrdersController extends Controller
{

    /**
     * OrdersController constructor.
     */
    public function __construct()
    {
    }

    //TODO 订单列表 可根据平台、时间查看
    public function index()
    {

    }

    //TODO 手动提交订单 存到tbk_member_orders  ，使用脚本订单读取这个表中的订单号，和tbk_order里面的订单绑定
    public function  submit()
    {

    }
}