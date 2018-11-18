<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User\User;

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
        dd (setting (1)->credit_order);
        return view('home');
    }


}
