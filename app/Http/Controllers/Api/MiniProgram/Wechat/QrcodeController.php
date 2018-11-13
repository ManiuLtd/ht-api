<?php

namespace App\Http\Controllers\Api\MiniProgram\Wechat;

use Overtrue\LaravelWeChat\Facade;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

/**
 * Class QrcodeController.
 */
class QrcodeController extends Controller
{
    /**
     * 生成小程序码
     * @return bool|\Illuminate\Http\JsonResponse|string
     */
    public function appCode()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $miniProgram = Facade::miniProgram(); // 小程序

            //目录路径
            $templateImage = public_path('images/template.jpg');
            $openId = $user->wx_openid2;
            //文件名
            $fileName = $openId.'.jpeg';
            //图片
            $cacheImage = public_path('images/cache/').$fileName;
            $qrcodeImage = public_path('images/qrcode/').$fileName;
            //缓存下来的头像和带参数二维码
            if (file_exists($qrcodeImage)) {
                return $qrcodeImage;
            }
            //缓存二维码 最终小程序码地址为 pages/index/index?senne=你的openid
            $response = $miniProgram->app_code->getUnlimit($openId, [
                'page' => 'pages/index/index',
            ]);
            $response->saveAs(public_path('images/cache'), $fileName);

            if (! file_exists($cacheImage)) {
                return false;
            }
            //生成图片
            $template = Image::make($templateImage);
            $cache = Image::make($cacheImage)->resize(420, 420);
            //插入图片
            $template->insert($cache, 'bottom-left', 163, 220);
            $template->save($qrcodeImage);

            return $qrcodeImage;
        } catch (\Exception $e) {
            return json(5001, $e->getMessage());
        }
    }

    //TODO  生成小程序二维码
    public function qrCode()
    {
    }
}
