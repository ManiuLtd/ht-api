<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Tools\Taoke\JingDong;
use App\Tools\Taoke\Taobao;

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
        $jingdong = new JingDong();
        dd($jingdong->getJdDetail(['itemid'=>29782184954]));
        return view('home');
    }
}
