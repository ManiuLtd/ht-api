<?php

namespace App\Http\Controllers\Api\Taoke;

use App\Tools\Taoke\Taobao;
use Hashids\Hashids;
use App\Tools\Qrcode\Qrcode;
use Illuminate\Http\Request;
use App\Tools\Qrcode\TextEnum;
use App\Tools\Qrcode\ImageEnum;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Validators\Taoke\QrcodeValidator;

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
     */
    public function share(Request $request)
    {
        try {
            $item_id = request('item_id');
            $type    = request('type');
            $tool    = new Taobao();
            $data    = $tool->getDetail([
                'itemid' => $item_id
            ]);
            $userid = getUserId();
            $hashids = new Hashids(config('hashids.SALT'), config('hashids.LENGTH'), config('hashids.ALPHABET'));
            //邀请码
            $hashids = $hashids->encode($userid);
            $couponPrice = intval($data['coupon_price']).'元';
            $qrcode = new Qrcode(public_path('images/share.png'));
            $qrcode->width = 564;
            $qrcode->height = 971;
            $qrcode->savePath = 'images/cache/'.$hashids.'_'.$item_id.'.jpg';
            $couponQrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->encoding('UTF-8')
                ->generate('http://v2.easytbk.com/api/taoke/coupon/detail'.'?type='.$type.'&item_id='.$item_id);
            $imgname = 'qrcodeImg'.'.png';
            Storage::disk('public')->put($imgname, $couponQrcode);
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
                new TextEnum('销量:'.$data['volume'], 180, 690, 20, '#9b9b9b'),
            ];
            $qrcode->setImageEnumArray($imageEnumArray);
            $qrcode->setTextEnumArray($textEnumArray);
            $res = $qrcode->make();

            return json('1001', '二维码生成分享成功', $res);
        } catch (\Exception $e) {
            return json('5001', $e->getMessage());
        }
    }

    /**
     * 邀请海报.
     * @return \Illuminate\Http\JsonResponse
     */
    public function invite()
    {
        $userid = getUserId();
        for ($i = 1; $i <= 3; $i++) {
            $templateName = "template{$i}";
            $qrcode = new Qrcode(public_path("images/{$templateName}.jpg"));
            $qrcode->width = 928;
            $qrcode->height = 1470;
            $hashids = new Hashids(config('hashids.SALT'), config('hashids.LENGTH'), config('hashids.ALPHABET'));
            //邀请码
            $hashids = $hashids->encode($userid);
            $qrcode->savePath = "images/invite{$i}.jpg";
            $fileName = $hashids.'_'.$templateName.'.png';
            $cacheImage = public_path('images/cache/').$fileName;
            //生成二维码
            $redirectUrl = url('http://www.baidu.com?unionid='.$userid);
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->generate($redirectUrl, $cacheImage);
            $cache = Image::make($cacheImage)->resize(156, 141);
            $imageEnumArray = [
                new ImageEnum($cacheImage, 300, 300, 'bottom', 100, 140),
            ];
            $textEnumArray = [
                new TextEnum($hashids, 350, 1400, 50),
            ];
            $qrcode->setImageEnumArray($imageEnumArray);
            $qrcode->setTextEnumArray($textEnumArray);
            $res[] = $qrcode->make();
        }

        return json('1001', '邀请海报', $res);
    }
}
