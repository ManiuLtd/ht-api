<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Auth;

/**
 * Class LogoutController
 *
 * @package App\Http\Controllers\Backend\Auth
 */
class LogoutController extends Controller
{


    /**
     * 退出登录
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard ()->logout ();

        return json ('1001', '退出成功');
    }
}
