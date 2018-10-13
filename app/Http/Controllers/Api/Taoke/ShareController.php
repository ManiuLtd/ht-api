<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use App\Models\Taoke\Coupon;
use App\Tools\Qrcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class ShareController
 * @package App\Http\Controllers\Api\Taoke
 */
class ShareController extends Controller
{


    /**
     * 分享
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $qrcode = new Qrcode(public_path ('images/share.png'));
        $qrcode->width  = 564;
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
                'height' => 545
            ]
        ] ;
        $textArr = [
            [
                'text' => $str1,
                'position' => 'bottom',
                'x' => 20,
                'size' => 20,
                'color' => '#444',
                'valign' => 'left',
                'y' => 600
            ],
            [
                'text' => $str2,
                'position' => 'bottom',
                'x' => 30,
                'size' => 20,
                'color' => '#444',
                'valign' => 'left',
                'y' => 630
            ],
            [
                'text' => $data['final_price'],
                'position' => 'bottom',
                'x' => 140,
                'size' => 20,
                'color' => '#444',
                'valign' => 'left',
                'y' => 570
            ]

        ] ;
        $qrcode->setImageArray ($imageArr);
        $qrcode->setTextArray ($textArr);
        $res = $qrcode->generate ();
        dd ($res);
        return json('1001','分享成功',$res);
    }

//    public function index()
//    {
//        //目录路径
//        $templateImage = public_path ('images/share.png');
//        //文件名
//        $fileName = 'bbbb.jpeg';
//        //图片
//        $qrcodeImage = public_path('images/') . $fileName;
//        //生成图片
//        $template = Image::make($templateImage);
//        $headimage = Image::make('https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1539434833642&di=e4d715bdf71fbcd240e4f1747c0d0f34&imgtype=0&src=http%3A%2F%2Fimg3.cache.netease.com%2Fgame%2F2013%2F11%2F21%2F20131121100455f28b0.png')->resize(350, 350);
//
//        $template->insert($headimage, 'bottom-left', 115, 50);
//        //金额
//        $template->text('123', 280, 1185, function ($font) {
//            $font->file(public_path('fonts/msyh.ttf'));
//            $font->size(47);
//            $font->color('#9b9b9b');
//            $font->valign('left');
//        });
//        $str1 = str_limit('【限时亏本】10支新款软毛牙刷成人牙刷情侣牙刷竹炭儿童牙刷套装', 50, '');
//        $str2 = str_replace($str1, '', '【限时亏本】10支新款软毛牙刷成人牙刷情侣牙刷竹炭儿童牙刷套装');
//        $template->text($str1, 40, 1250, function ($font) {
//            $font->file(public_path('fonts/msyh.ttf'));
//            $font->size(40);
//            $font->color('#444');
//            $font->align('center');
//            $font->valign('left');
//        });
//        $template->text(str_limit($str2, 32, '...'), 55, 1300, function ($font) {
//            $font->file(public_path('fonts/msyh.ttf'));
//            $font->size(40);
//            $font->color('#444');
//            $font->align('center');
//            $font->valign('left');
//        });
//        $template->text('10'.'元', 80, 1420, function ($font) {
//            $font->file(public_path('fonts/msyh.ttf'));
//            $font->size(35);
//            $font->color('#0E0E0E');
//            $font->valign('left');
//        });
//
//        $template->text('销量:' . '131', 350, 1420, function ($font) {
//            $font->file(public_path('fonts/msyh.ttf'));
//            $font->size(40);
//            $font->color('#9b9b9b');
//            $font->valign('left');
//        });
//        $pic = Image::make('http://t00img.yangkeduo.com/goods/images/2018-10-09/19a53251208f9c09d927cad24a0445f0.jpeg')->resize(1123, 1123);
//        $template->insert($pic, 'top', 0, 0);
//        $template->save($qrcodeImage);
//        dd($qrcodeImage);
//        return asset ($qrcodeImage);
//
//    }

}
