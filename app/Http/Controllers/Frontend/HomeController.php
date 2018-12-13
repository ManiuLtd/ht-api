<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Tools\Taoke\JingDong;
use App\Tools\Taoke\Taobao;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * tbk_darenshuo
     * tbk_kuaiqaing
     * tbk_zhuanchang.
     *
     *
     *
     * 淘宝 534939574752
     * 京东 20294254021
     * 拼多多 74872615.
     */
    public function index()
    {
        //https://www.cnblogs.com/wwanstudio/p/5053447.html

        $res = DB::table('tbk_orders')
            ->whereDate('created_at', '>=', '2018-10-01')
            ->select(DB::raw ("DATE_FORMAT(created_at,'%Y-%m-%d') weeks"), DB::raw('count(id) as total'))
            ->groupBy('weeks')
            ->get();


//        $sql = "SELECT DATE_FORMAT(created_at,'%Y-%m-%d') weeks ,COUNT(id) as total  FROM tbk_orders GROUP BY weeks";
//        $res2 = DB::select(DB::raw ($sql))

        dd ($res);
        return view('home');
    }
}
