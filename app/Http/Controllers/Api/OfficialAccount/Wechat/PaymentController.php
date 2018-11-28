<?php

namespace App\Http\Controllers\Api\OfficialAccount\Wechat;

use Overtrue\LaravelWeChat\Facade;
use App\Http\Controllers\Controller;

/**
 * Class PaymentController.
 */
class PaymentController extends Controller
{
    /**
     * 微信H5支付.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function h5()
    {
        try {
            $prepayId = request ('prepayId');
            if (!$prepayId) {
                throw  new \InvalidArgumentException('支付失败，缺少prepayId');
            }

            $app = Facade::payment ();
            $config = $app->jssdk->sdkConfig ($prepayId);

            return response (1001, '支付参数获取成功', $config);
        } catch (\Exception $e) {
            return response (4001, $e->getMessage ());
        }
    }


    public function app()
    {
        //APP支付  https://www.easywechat.com/docs/master/payment/order

        try {
            $app = Facade::payment ();

            $user = getUser ();

            $payType = request ('pay_type');
            $title = "微信支付";
            $totaFee = 0;
            if ($payType == "level") {
                $totaFee = $this->getLevelPrice ();
            }
            if ($totaFee == 0) {
                throw  new \Exception('付款金额不能为0');
            }

            if ($user->wx_openid1 == null) {
                throw  new \Exception('请先授权app微信登录');
            }
            $result = $app->order->unify ([
                'body' => $title,
                'out_trade_no' => date ("YmdHis") . range (10000, 99999),
                'total_fee' => $totaFee,
//                'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                'trade_type' => 'APP', // 请对应换成你的支付方式对应的值类型
                'openid' => $user->wx_openid1,
            ]);

            return json (1001, "支付信息请求成功", $result);


        } catch (\Exception $e) {
            return json (5001, $e->getMessage ());
        }
    }


    public function notify()
    {
        //判断支付是否成功  然后升级等等
    }


    /**
     * 付费升级
     * @return \Illuminate\Database\Query\Builder|mixed
     * @throws \Exception
     */
    protected function getLevelPrice()
    {
        $user = getUser ();
        $level_id = request ('level_id');
        $type = request ('type');//1月2季3年4永久

        if (!in_array ($type, [1, 2, 3, 4])) {
            throw new \Exception('传参错误');
        } else {
            $column = 'price' . $type;
        }

        $level = db ('levels')->find ($level_id);
        if (!$level) {
            throw new \Exception('等级不存在');
        }
        $level_user = db ('levels')->find ($user->level_id);
        if ($level_user->level > $level->level) {
            throw new \Exception('当前等级大于所要升级的等级');
        }

        return $level->$column * 100;
//        if ($level[$column] == $money) {
//            if ($type == 1) {
//                $time = now ()->addDays (30);
//            } elseif ($type == 2) {
//                $time = now ()->addMonths (3);
//            } elseif ($type == 3) {
//                $time = now ()->addYears (1);
//            } else {
//                $time = null;
//            }
//            db ('users')->where ('id', $user->id)->update ([
//                'level_id' => $level->id,
//                'expired_time' => $time
//            ]);
//        } else {
//            throw new \Exception('升级失败');
//        }
    }
}
