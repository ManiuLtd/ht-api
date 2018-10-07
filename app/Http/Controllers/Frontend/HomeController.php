<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Tools\Taoke\TBKInterface;

class HomeController extends Controller
{
//    protected $test;
//
//    public function __construct(TBKInterface $test)
//    {
//        $this->test = $test;
//    }

    /**
     * 淘宝 534939574752
     * 京东 20294254021
     * 拼多多 74872615
     *
     */
    public function index()
    {

//        $rest = $this->test->getDetail([
//            'id' => '74872615',
//        ]);
//        dd($rest);
        return view('home', compact('members'));
    }
}
