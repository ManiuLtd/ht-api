<?php
/**
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/10/7
 * Time: 16:18
 */

namespace App\Http\Controllers\Api\Taoke;

use App\Http\Controllers\Controller;
use Ixudra\Curl\Facades\Curl;


/**
 *
 * Class TrackOrdersController
 * @package App\Http\Controllers\Api\Taoke
 */
class TrackOrdersController extends Controller
{
    /**
     * 淘宝生成领劵地址
     * @return \Illuminate\Http\JsonResponse
     */
    public function track()
    {
        $itemID = request('item_id');
        if(!$itemID){
            return json('4001','商品id不存在');
        }
        $pidModel = db('tbk_pids')
            ->where([
                'user_id' => getUserId(),
                'type' => 1
            ])
            ->orderByRaw('RAND()')
            ->first();

        if ($pidModel) {
            $pid = $pidModel->pid;
        }
        $response = Curl::to('https://www.heimataoke.com/api-zhuanlian')
            ->withData([
                'appkey' => '193298714',
                'appsecret' => '33591f90704bcfc868871794c80ac185',
                'pid' => $pid,
                'sid' => '1942',
                'num_iid' => $itemID,
            ])
            ->get();
        $response = json_decode($response);
        if (!isset($response->coupon_click_url)) {
            return json('4001','高佣金转链失败');
        }
        $coupon_click_url = $response->coupon_click_url;

        return json('1001','获取成功',$coupon_click_url);
    }
}