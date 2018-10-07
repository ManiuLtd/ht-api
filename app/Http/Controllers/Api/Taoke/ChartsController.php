<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Member;


use App\Http\Controllers\Controller;

/**
 * 报表
 * Class ChartsController
 * @package App\Http\Controllers\Api\Member
 */
class ChartsController extends Controller
{

    /**
     * ChartsController constructor.
     */
    public function __construct()
    {
    }

    //TODO 订单报表 可根据 今日，昨日，本月，上月查看
    public function order()
    {

    }

    //TODO 团队报表 可根据 今日，昨日，本月，上月查看
    public function team()
    {

    }

    //TODO 提现报表  显示 待结算 累计结算 累计提现
    public function withdraw()
    {

    }

}