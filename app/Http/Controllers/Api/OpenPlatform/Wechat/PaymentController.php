<?php

namespace App\Http\Controllers\Api\OpenPlatform\Wechat;

use Overtrue\LaravelWeChat\Facade;
use App\Http\Controllers\Controller;

/**
 * Class PaymentController.
 */
class PaymentController extends Controller
{
    //TODO 微信app支付
    public function index()
    {
        $app = Facade::payment();
    }
}
