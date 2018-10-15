<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;


use App\Http\Controllers\Controller;
use App\Tools\Qrcode;

/**
 * 邀请海报
 * Class QrcodeController
 * @package App\Http\Controllers\Api\Member
 */
class QrcodeController extends Controller
{

    /**
     * QrcodeController constructor.
     */
    public function __construct()
    {
    }

    //TODO 邀请海报，多张
    public function inviter()
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

    //TODO 商品分享海报，一张
    public function share()
    {

    }
}