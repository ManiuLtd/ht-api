<?php

namespace App\Http\Controllers\Api\MiniProgram\Wechat;

use Overtrue\LaravelWeChat\Facade;
use App\Http\Controllers\Controller;

/**
 * Class PaymentController.
 */
class PaymentController extends Controller
{
    /**
     * 微信小程序支付.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $prepayId = request('prepayId');
            if (! $prepayId) {
                throw  new \InvalidArgumentException('支付失败，缺少prepayId');
            }

            $app = Facade::payment();
            $config = $app->jssdk->sdkConfig($prepayId);

            return response(1001, '支付参数获取成功', $config);
        } catch (\Exception $e) {
            return response(4001, $e->getMessage());
        }
    }
}
