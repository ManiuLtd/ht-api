<?php

namespace App\Http\Controllers\Api\OpenPlatform\Wechat;

use App\Http\Controllers\Controller;
use Overtrue\LaravelWeChat\Facade;

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
