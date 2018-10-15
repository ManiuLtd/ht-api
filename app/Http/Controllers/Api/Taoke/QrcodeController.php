<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Qrcode\Qrcode;
use App\Validators\Taoke\QrcodeValidator;
use function EasyWeChat\Kernel\data_to_array;
use Illuminate\Http\Request;
use App\Tools\Qrcode\TextEnum;
use App\Tools\Qrcode\ImageEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


/**
 * Class QrcodeController.
 */
class QrcodeController extends Controller
{
    /**
     * @var
     */
    protected $validator;

    /**
     * QrcodeController constructor.
     * @param QrcodeValidator $validator
     */
    public function __construct(QrcodeValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * 商品分享二维码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function share(Request $request)
    {
        $data = $request->all();
        $this->validator->with($data)->passesOrFail();
        $couponPrice = intval($data['coupon_price']).'元';
        $qrcode = new Qrcode(public_path('images/share.png'));
        $qrcode->width = 564;
        $qrcode->height = 971;
        $qrcode->savePath = 'images/couponShare.jpg';
        $couponQrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
            ->encoding('UTF-8')
            ->generate('http://lv5.vaiwan.com:8081/'.$data['item_id'].'?type'.$data['type']);
        $imgname= 'qrcodeImg'.'.png';
        Storage::disk('public')->put($imgname,$couponQrcode);
        $str1 = str_limit($data['title'], 50, '');
        $str2 = str_replace($str1, '', $data['title']);
        $data['qrcode_img'] = public_path().'/images/qrcodeImg.png';
        $imageEnumArray = [
            new ImageEnum($data['pic_url'], 565, 545, 'top', 0, 0),
            new ImageEnum($data['qrcode_img'], 210, 210, 'left-top', 30, 750),
        ];
        $textEnumArray = [
            new TextEnum($data['final_price'], 140, 575, 20),
            new TextEnum($str1, 20, 605, 20),
            new TextEnum($str2, 30, 630, 20),
            new TextEnum($couponPrice, 47, 690, 20),
            new TextEnum('销量:'.$data['volume'], 180, 690, 20,'#9b9b9b'),
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
