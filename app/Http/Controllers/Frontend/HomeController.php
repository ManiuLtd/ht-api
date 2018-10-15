<?php

namespace App\Http\Controllers\Frontend;

use App\Tools\Qrcode;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * 淘宝 534939574752
     * 京东 20294254021
     * 拼多多 74872615.
     */
    public function index()
    {
        $qrcode = new Qrcode(public_path('images/template.jpg'));
        $qrcode->width = 1500;
        $qrcode->height = 1950;
        $qrcode->savePath = 'images/aaa.jpg';

        $imageArr = [
            [
                'image' => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1539428738128&di=3a45b2473bcc37de37b6627932b449fa&imgtype=0&src=http%3A%2F%2Fg.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2Fd50735fae6cd7b8960afd68e022442a7d8330ef8.jpg',
                'position' => 'top',
                'x' => 100,
                'y' => 200,
            ],
            [
                'image' => 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=4136376869,3527986770&fm=58&bpow=484&bpoh=473',
                'position' => 'bottom',

                'x' => 100,
                'y' => 200,
            ],
        ];
        $textArr = [
            [
                'text' => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1539428738128&di=3a45b2473bcc37de37b6627932b449fa&imgtype=0&src=http%3A%2F%2Fg.hiphotos.baidu.com%2Fimage%2Fpic%2Fitem%2Fd50735fae6cd7b8960afd68e022442a7d8330ef8.jpg',
                'position' => 'top',
                'x' => 0,
                'color' => '#444',
                'valign' => 'left',
                'size' => 100,
                'y' => 200,
            ],
            [
                'text' => 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=4136376869,3527986770&fm=58&bpow=484&bpoh=473',
                'position' => 'bottom',
                'x' => 0,
                'size' => 100,
                'color' => '#444',
                'valign' => 'left',
                'y' => 200,
            ],
        ];
        $qrcode->setImageArray($imageArr);
        $qrcode->setTextArray($textArr);
        $res = $qrcode->generate();
        dd($res);
    }
}
