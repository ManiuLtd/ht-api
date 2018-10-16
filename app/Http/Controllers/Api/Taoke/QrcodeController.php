<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Qrcode\Qrcode;
use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Tools\Qrcode\TextEnum;
use App\Tools\Qrcode\ImageEnum;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

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
    /**
     * 邀请海报
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function invite()
    {
        $memberid = getMemberId();
        $i = rand(1,3);
        $templateName = "template{$i}";
        $qrcode = new Qrcode(public_path ("images/{$templateName}.jpg"));
        $qrcode->width  = 928;
        $qrcode->height = 1470;
        $qrcode->savePath = "images/invite{$i}.jpg";
        $fileName = $memberid . '_' . $templateName . '.png';
        $cacheImage = public_path('images/cache/') . $fileName;
        //生成二维码
        $redirectUrl = url('http://www.baidu.com?unionid='.$memberid);
        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->generate($redirectUrl,$cacheImage);
        $cache = Image::make($cacheImage)->resize(156,141);
        $hashids = new Hashids('hongtang', 6, 'abcdefghijklmnopqrstuvwxyz0123456789');
        //邀请码
        $hashids = $hashids->encode($memberid);
        $imageEnumArray = [
            new ImageEnum($cacheImage,300,300,'bottom',100, 140),
        ];
        $textEnumArray = [
            new TextEnum($hashids, 350, 1400, 50),
        ] ;
        $qrcode->setImageEnumArray($imageEnumArray);
        $qrcode->setTextEnumArray($textEnumArray);
        $res = $qrcode->make();
        return json('1001','邀请海报',$res);
    }
}
