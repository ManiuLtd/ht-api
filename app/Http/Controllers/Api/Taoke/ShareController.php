<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Qrcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

/**
 * Class ShareController.
 */
class ShareController extends Controller
{
    /**
     * 分享.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $qrcode = new Qrcode(public_path('images/share.png'));
        $qrcode->width = 564;
        $qrcode->height = 971;
        $qrcode->savePath = 'images/aaa.jpg';
        $str1 = str_limit($data['title'], 50, '');
        $str2 = str_replace($str1, '', '【限时亏本】10支新款软毛牙刷成人牙刷情侣牙刷竹炭儿童牙刷套装');
        $imageArr = [
            [
                'image' => $data['pic_url'],
                'position' => 'top',
                'x' => 0,
                'y' => 0,
                'width' => 565,
                'height' => 545,
            ],
        ];
        $textArr = [
            [
                'text' => $str1,
                'position' => 'bottom',
                'x' => 20,
                'size' => 20,
                'color' => '#444',
                'valign' => 'left',
                'y' => 605,
            ],
            [
                'text' => $str2,
                'position' => 'bottom',
                'x' => 30,
                'size' => 20,
                'color' => '#444',
                'valign' => 'left',
                'y' => 630,
            ],
            [
                'text' => $data['final_price'],
                'position' => 'bottom',
                'x' => 140,
                'size' => 20,
                'color' => '#9b9b9b',
                'valign' => 'left',
                'y' => 575,
            ],
            [
                'text' => $data['coupon_price'],
                'position' => 'bottom',
                'x' => 47,
                'size' => 20,
                'color' => '#444',
                'valign' => 'left',
                'y' => 690,
            ],
            [
                'text' => '销量:'.$data['volume'],
                'position' => 'bottom',
                'x' => 180,
                'size' => 20,
                'color' => '#9b9b9b',
                'valign' => 'left',
                'y' => 690,
            ],
        ];
        $qrcode->setImageArray($imageArr);
        $qrcode->setTextArray($textArr);
        $res = $qrcode->generate();
        dd($res);

        return json('1001', '二维码生成分享成功', $res);
    }
}
