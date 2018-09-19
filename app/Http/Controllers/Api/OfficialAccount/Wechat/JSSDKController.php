<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:13
 */

namespace App\Http\Controllers\Api\OfficialAccount\Wechat;


use App\Http\Controllers\Controller;

/**
 * Class JSSDKController
 * @package App\Http\Controllers\Api\OfficialAccount\Wechat
 */
class JSSDKController extends Controller
{
    /**
     * 获取微信JSSDK所需参数
     * 文档： https://www.easywechat.com/docs/master/zh-CN/basic-services/jssdk
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $app = factory ('wechat.official_account');
            $config = $app->jssdk->buildConfig (array('onMenuShareQQ', 'onMenuShareWeibo'), true);
            return response (1001, 'JSSDK参数获取成功', $config);

        } catch (\Exception $e) {
            return response (4001, $e->getMessage ());
        }
    }

}