<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
//        $members = Member::find (1);
//        event (new CreditIncrement($members, 1, 20, '增加积分'));
        return view('home', compact('members'));
    }
}
