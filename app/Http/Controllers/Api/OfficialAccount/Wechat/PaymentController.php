<?php

namespace App\Http\Controllers\Api\OfficialAccount\Wechat;

use App\Http\Controllers\Controller;
use Overtrue\LaravelWeChat\Facade;

/**
 * Class PaymentController.
 */
class PaymentController extends Controller
{
    /**
     * 微信H5支付.
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
