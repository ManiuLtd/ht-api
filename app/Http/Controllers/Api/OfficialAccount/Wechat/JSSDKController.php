<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:13.
 */

namespace App\Http\Controllers\Api\OfficialAccount\Wechat;

use Overtrue\LaravelWeChat\Facade;
use App\Http\Controllers\Controller;

/**
 * Class JSSDKController.
 */
class JSSDKController extends Controller
{
    /**
     * 获取微信JSSDK所需参数
     * 文档： https://www.easywechat.com/docs/master/zh-CN/basic-services/jssdk.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function index()
    {
        try {
            $app = Facade::officialAccount();
            $config = $app->jssdk->buildConfig(['onMenuShareQQ', 'onMenuShareWeibo'], true);

            return response(1001, 'JSSDK参数获取成功', $config);
        } catch (\Exception $e) {
            return response(4001, $e->getMessage());
        }
    }
}
