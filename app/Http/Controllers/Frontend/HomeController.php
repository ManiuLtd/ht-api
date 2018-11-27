<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Tools\Taoke\JingDong;

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
//        $en = encrypt ('https%3A%2F%2Fmigu22.kuaizhan.com%2F%3Fkf%3D%28%EF%BF%A5skJFbPOpRz3%EF%BF%A5%29%26zr%3D%EF%BF%A5skJFbPOpRz3%EF%BF%A5%26base%3DenI%3D%26sku%3D580477371625%26rand%3D3'.'!'.'5vj1gv');
//
//        dd (decrypt ($en));
        return view('home');
    }
}
