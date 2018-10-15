<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Qrcode\Qrcode;
use Illuminate\Http\Request;
use App\Tools\Qrcode\TextEnum;
use App\Tools\Qrcode\ImageEnum;
use App\Http\Controllers\Controller;

/**
 * Class QrcodeController.
 */
class QrcodeController extends Controller
{
    /**
     * 商品详情分享二维码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function share(Request $request)
    {
        $data = $request->all();
        $qrcode = new Qrcode(public_path('images/share.png'));
        $qrcode->width = 564;
        $qrcode->height = 971;
        $qrcode->savePath = 'images/aaa.jpg';

        $str1 = str_limit($data['title'], 50, '');
        $str2 = str_replace($str1, '', '【限时亏本】10支新款软毛牙刷成人牙刷情侣牙刷竹炭儿童牙刷套装');

        $imageEnumArray = [
            new ImageEnum($data['pic_url'], 565, 545, 'top', 0, 0),
        ];

        $textEnumArray = [
            new TextEnum($str1, 20, 20, 20),
        ];
        $qrcode->setImageEnumArray($imageEnumArray);
        $qrcode->setTextEnumArray($textEnumArray);
        $res = $qrcode->make();

        return json('1001', '二维码生成分享成功', $res);
    }

    public function invite()
    {
        //TODO 邀请二维码 可生成多张，然后使用接口返回所有二维码。
    }
}
