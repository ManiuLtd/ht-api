<?php

namespace App\Http\Controllers\Api\Taoke;

use Hashids\Hashids;
use App\Tools\Taoke\Taobao;
use App\Tools\Qrcode\Qrcode;
use Illuminate\Http\Request;
use App\Tools\Taoke\JingDong;
use App\Tools\Qrcode\TextEnum;
use App\Tools\Taoke\PinDuoDuo;
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
            $type = request('type');
            $pic_url = request('pic_url');
            if ($type == 1) {
                $tool = new Taobao();
                $mes = ' 淘 宝';
            } elseif ($type == 2) {
                $tool = new JingDong();
                $mes = ' 京 东';
            } elseif ($type == 3) {
                $tool = new PinDuoDuo();
                $mes = '拼多多';
            }
            $data = $tool->getDetail([
                'itemid' => $item_id,
            ]);
            if ($type == 1) {
                //获取设置信息
                $setting = setting(1);
                $kuaizhan = $setting->kuaizhan;
                $url = $kuaizhan.'/?kf=('.$data['kouling'].')&zr='.$data['kouling'].'&base=enI=&sku='.$data['item_id'].'&rand=3';
            } else {
                $url = data_get($data, 'coupon_link.url');
            }
            $userid = getUserId();
            $hashids = new Hashids(config('hashids.SALT'), config('hashids.LENGTH'), config('hashids.ALPHABET'));
            //邀请码
            $hashids = $hashids->encode($userid);
            $qrcode = new Qrcode(public_path('images/share.png'));
            $qrcode->width = 564;
            $qrcode->height = 971;
            $qrcode->savePath = 'images/cache/'.$hashids.'_'.$item_id.'.jpg';
            $redirectUrl = route('wechat.login', [
                'redirect_url' => $url,
                'inviter'      => $hashids,
            ]);
            $couponQrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                ->encoding('UTF-8')
                ->generate($redirectUrl);
            $imgname = $hashids.'_'.$item_id.'_qrcode'.'.png';
            Storage::disk('public')->put($imgname, $couponQrcode);
            $str1 = str_limit($data['title'], 20, '');
            $str2 = str_replace($str1, '', $data['title']);
            $str3 = str_limit($str2, 27, '');
            $str4 = str_replace($str3, '', $str2);
            $data['qrcode_img'] = public_path().'/images/cache/'.$hashids.'_'.$item_id.'_qrcode.png';
            $imageEnumArray = [
                new ImageEnum($pic_url, 650, 678, 'top', 0, 0),
                new ImageEnum($data['qrcode_img'], 200, 200, 'right-top', 0, 690),
            ];
            $textEnumArray = [
                new TextEnum($data['final_price'], 160, 838, 35, '#DD6470'),
                new TextEnum($data['price'], 185, 895, 32, '#6A6A6A'),
                new TextEnum($mes, 20, 725, 25, '#FFE3EA'),
                new TextEnum($str1, 100, 725, 25, '#4C4C4C'),
                new TextEnum($str3, 20, 760, 25, '#4C4C4C'),
                new TextEnum($str4, 20, 795, 25, '#4C4C4C'),
                new TextEnum(intval($data['coupon_price']), 70, 895, 32, '#D4A5B2'),
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
        //获取设置信息
        $setting = setting(1);
        for ($i = 1; $i <= 5; $i++) {
            $templateName = "template{$i}";
            $qrcode = new Qrcode(public_path("images/{$templateName}.jpg"));
            $qrcode->width = 928;
            $qrcode->height = 1470;
            $hashids = new Hashids(config('hashids.SALT'), config('hashids.LENGTH'), config('hashids.ALPHABET'));
            //邀请码
            $hashids = $hashids->encode($userid);
            //海报
            $qrcode->savePath = "images/cache/{$hashids}_{$templateName}.jpg";
            //二维码
            $cacheImage = public_path('images/cache/').$hashids.'_invite'.'.png';
            //生成二维码
            $redirectUrl = route('wechat.login', [
                'redirect_url' => $setting->download,
                'inviter'      => $hashids,
            ]);
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->generate($redirectUrl, $cacheImage);
            $cache = Image::make($cacheImage)->resize(156, 141);
            $imageEnumArray = [
                new ImageEnum($cacheImage, 330, 330, 'bottom', 100, 140),
            ];

            $qrcode->setImageEnumArray($imageEnumArray);
            $res[] = $qrcode->make();
        }

        return json('1001', '邀请海报', $res);
    }
}
