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
        $qrcode = new Qrcode(public_path ('images/template.jpg'));
        $qrcode->width  = 928;
        $qrcode->height = 1470;
        $qrcode->savePath = 'images/aaa.jpg';

        $imageArr = [
            [
                'image' => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1539428738128&di=3a45b2473bcc37de37b6627932b449fa&imgtype=0&src=http%3A%2F%2Fg.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2Fd50735fae6cd7b8960afd68e022442a7d8330ef8.jpg',
                'position' => 'top',
                'x' => 100,
                'y' => 200
            ],
            [
                'image' => 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=4136376869,3527986770&fm=58&bpow=484&bpoh=473',
                'position' => 'bottom',
                'x' => 100,
                'y' => 200
            ]
        ] ;
        $textArr = [
            [
                'text' => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1539428738128&di=3a45b2473bcc37de37b6627932b449fa&imgtype=0&src=http%3A%2F%2Fg.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2Fd50735fae6cd7b8960afd68e022442a7d8330ef8.jpg',
                'position' => 'top',
                'x' => 0,
                'color' => '#444',
                'valign' => 'left',
                'size' => 100,
                'y' => 200
            ],
            [
                'text' => 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=4136376869,3527986770&fm=58&bpow=484&bpoh=473',
                'position' => 'bottom',
                'x' => 0,
                'size' => 100,
                'color' => '#444',
                'valign' => 'left',
                'y' => 200
            ]
        ] ;
        $qrcode->setImageArray ($imageArr);
        $qrcode->setTextArray ($textArr);
        $res = $qrcode->generate ();
        return json('1001','邀请海报',$res);
    }
}
