<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Auth;

/**
 * Class LogoutController.
 */
class LogoutController extends Controller
{
    /**
     * 退出登录.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard()->logout();

        return json('1001', '退出成功');
    }
}
