<?php

namespace App\Http\Controllers\Api\OpenPlatform\Wechat;


use App\Http\Controllers\Controller;


/**
 * Class PaymentController
 * @package App\Http\Controllers\Api\OfficialAccount\Alipay
 */
class PaymentController extends Controller
{
    //TODO 微信app支付
    public function index()
    {
        $app = factory ('wechat.payment');
    }
}
