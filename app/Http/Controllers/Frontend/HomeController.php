<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Member\Group;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{


    /**
     * tbk_darenshuo
     * tbk_kuaiqaing
     * tbk_zhuanchang
     *
     *
     *
     * 淘宝 534939574752
     * 京东 20294254021
     * 拼多多 74872615.
     */
    public function index()
    {
        return view ('home');
    }


    public function test()
    {


    }



}
