<?php

namespace App\Tools\Taoke;

use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;
use App\Models\Taoke\Favourite;
use mysql_xdevapi\Exception;

class JingDong implements TBKInterface
{
    use TBKCommon;

    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getCouponUrl(array $array = [])
    {
        $itemID = $array['itemID'];
        $coupon_url = $array['coupon_url'];

        $pids = $this->getPids ();
        if (!isset($pids->jingdong)) {
            throw new \InvalidArgumentException('请先设置系统京东推广位id');
        }
        $setting = $this->getSettings();
        if (!$setting) {
            throw new \InvalidArgumentException('请先完成设置');
        }
        $jingdong = $setting->jingdong;

        if (!isset($jingdong['apikey'])) {
            throw new \InvalidArgumentException('请先设置好京客apikey');
        }
        // 返回领券地址
        $result = Curl::to ('http://api-gw.haojingke.com/index.php/api/index/myapi')
            ->withData ([
                'type' => 'unionurl',
                'apikey' => $jingdong['apikey'],
                'materialIds' => $itemID,
                'couponUrl' => $coupon_url,
                'positionId' => $pids->jingdong,
            ])
            ->asJsonResponse ()
            ->post ();

        if (!$result) {
            return null;
        }
        if ($result->status_code != 200) {
            throw new \InvalidArgumentException($result->message);
        }
        return $result->data;
    }

    /**
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    public function getDetail(array $params = [])
    {
        $this->checkUser ();

        $id = $params['itemid'] ?? request('itemid');

        $params = [
            'type' => 'goodsdetail',
            'apikey' => data_get (config ('coupon'), 'jingdong.JD_HJK_APIKEY'),
            'skuid' => $id,
        ];
        $response = Curl::to ('http://api-gw.haojingke.com/index.php/api/index/myapi')
            ->withData ($params)
            ->post ();
        $response = json_decode ($response);

        if ($response->status_code != 200) {
            throw new \InvalidArgumentException($response->message);
        }
        if (!$response->data) {
            throw new \InvalidArgumentException($response->message);
        }

        //领券地址
        $link = null;

        $link = $this->getCouponUrl ([
            'itemID' => $id,
            'coupon_url' => $response->data->couponList,
        ]);

        if (!$link) {
            $link = $response->data->couponList;
        }

        $resCoupon = $this->getCoupon ([
            'url' => $response->data->couponList,
        ]);
        //判断优惠卷是否被收藏
        $favourite = 0;

        $user = getUser ();
        $favouritesModel = Favourite::query ()->where ([
            'user_id' => $user->id,
            'item_id' => $id,
            'type' => 2,
        ])->first ();
        if ($favouritesModel) {
            $favourite = $favouritesModel->id; //已收藏
        }


        $data = $response->data;

        //获取优惠卷信息
        $arr = [];
        $arr['title'] = $data->skuName; //标题
        $arr['item_id'] = $data->skuId; //商品id
        $arr['user_type'] = null; //京东  拼多多 null  1淘宝 2天猫
        $arr['volume'] = rand (1000,9999); //销量
        $arr['price'] = floatval ($data->wlPrice); //原价
        $arr['final_price'] = floatval ($data->wlPrice_after); //最终价
        $arr['coupon_price'] = floatval ($data->discount); //优惠价
        $arr['commossion_rate'] = $data->wlCommissionShare; //佣金比例
        $arr['coupon_start_time'] = isset($data->beginTime) ? Carbon::createFromTimestamp ($data->beginTime)->toDateString () : Carbon::now ()->toDateString (); //优惠卷开始时间
        $arr['coupon_end_time'] = isset($data->endTime) ? Carbon::createFromTimestamp ($data->endTime)->toDateString () : Carbon::now ()->addDay (3)->toDateString (); //优惠卷结束时间
        $arr['coupon_remain_count'] = isset($resCoupon->remainnum) ? $resCoupon->remainnum : 0; //已使用优惠卷数量
        $arr['coupon_total_count'] = isset($resCoupon->num) ? $resCoupon->num : 10000; //优惠卷总数
        $arr['pic_url'] = $data->picUrl; //商品主图
        $arr['small_images'] = [$data->picUrl]; //商品图
        $arr['images'] = null; //商品详情图
        $arr['kouling'] = null; //淘口令
        $arr['introduce'] = $data->skuDesc; //描述
        $arr['favourite'] = $favourite; //0是没有收藏
        $arr['coupon_link'] = ['url' => $link]; //领劵地址
        $arr['finalCommission'] =  floatval(round($this->getFinalCommission($data->wlCommission),2));

        return $arr;
    }



    /**
     * 全网搜索.
     * @return array|mixed
     * @throws \Exception
     */
    public function search()
    {
        //排序没写，测试接口是否正常

        $page = request ('page', 1);
        $q = request ('q');
        $sort = request ('sort');

        $searchType = request ('searchtype',2);
        if($searchType == 1){
            return $this->localSearch ($q);
        }

        $params = [
            'type' => 'goodslist',
            'apikey' => data_get (config ('coupon'), 'jingdong.JD_HJK_APIKEY'),
            'keyword' => $q,
            'page' => $page,

        ];
        //1.综合，2.销量（高到低），3.销量（低到高），4.价格(低到高)，5.价格（高到低），6.佣金比例（高到低） 7. 卷额(从高到低) 8.卷额(从低到高)
        switch ($sort) {
            case 1: //
                break;
            case 2: //
                $params['sort'] = 4;
                $params['sortby'] = 'desc';
                break;
            case 3: //
                $params['sort'] = 4;
                $params['sortby'] = 'asc';
                break;
            case 4: //
                $params['sort'] = 1;
                $params['sortby'] = 'asc';
                break;
            case 5: //
                $params['sort'] = 1;
                $params['sortby'] = 'desc';
                break;
            case 6: //
                $params['sort'] = 3;
                $params['sortby'] = 'desc';
                break;
            case 7: //
                $params['sort'] = 2;
                $params['sortby'] = 'desc';
                break;
            case 8: //
                $params['sort'] = 2;
                $params['sortby'] = 'asc';
                break;

            default:
                $params['sort'] = 0;
                break;
        }
        $response = Curl::to ('http://api-gw.haojingke.com/index.php/api/index/myapi?type=goodslist')
            ->withData ($params)
            ->asJsonResponse ()
            ->post ();

        if ($response->status_code != 200) {
            throw new \InvalidArgumentException($response->message);
        }
        $data = [];
        foreach ($response->data as $datum) {
            $temp['title'] = $datum->skuName;
            $temp['pic_url'] = $datum->picUrl;
            $temp['item_id'] = $datum->skuId;
            $temp['price'] = round ($datum->wlPrice);
            $temp['final_price'] = round ($datum->wlPrice_after);
            $temp['coupon_price'] = round ($datum->discount);
            $temp['commission_rate'] = round ($datum->wlCommissionShare);
            $temp['type'] = 2;
            $temp['volume'] = rand (1000,9999);
            $data[] = $temp;
            $temp = [];
        }

        //当前页面地址
        $uri = request ()->getUri ();
        //验证是否填写page参数
        if (!str_contains ('page=', $uri)) {
            $uri = $uri . '&page=1';
        }

        //页码信息
        $totalPage = $response->totalpage;
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        //页码不对
        if ($page > $totalPage) {
            throw new \InvalidArgumentException('超出最大页码');
        }

        return [
            'data' => $data,
            'links' => [
                'first' => str_replace ("page={$page}", 'page=1', $uri),
                'last' => str_replace ("page={$page}", "page={$totalPage}", $uri),
                'prev' => $page == 1 ? null : str_replace ("page={$page}", "page={$prevPage}", $uri),
                'next' => str_replace ("page={$page}", "page={$nextPage}", $uri),
            ],
            'meta' => [
                'current_page' => (int)$page,
                'from' => 1,
                'last_page' => $totalPage,
                'path' => request ()->url (),
                'per_page' => 20,
                'to' => 20 * $page,
                'total' => count ($response->data),

            ],
        ];
    }

    /**
     * 获取订单.
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getOrders(array $array = [])
    {
        $setting = setting (1);
        $unionid = $setting->unionid;

        if (!isset($unionid['jingdong'])) {
            throw new \InvalidArgumentException('请先设置京东联盟id');
        }

        $page = $array['page'] ?? 1;
        $time = now ()->toDateTimeString ();
        $params = [
            'method' => 'jingdong.UnionService.queryOrderList',
            'access_token' => data_get (config ('coupon'), 'jingdong.access_token'),
            'app_key' => data_get (config ('coupon'), 'jingdong.JDM_APP_KEY'),
            'timestamp' => $time,
            'v' => '2.0',
        ];

        $urlparams = [
            'unionId' => $unionid['jingdong'],
            'key' => data_get (config ('coupon'), 'jingdong.JDMEDIA_APPKEY'),
            'time' => date ('YmdH', time ()),
            'pageIndex' => $page,
            'pageSize' => 500,
        ];

        $signparams = array_merge ($params, $urlparams);
        ksort ($signparams);
        $sign = http_build_query ($signparams);
        $sign = strtoupper (md5 (data_get (config ('coupon'), 'jingdong.JDM_APP_SECRET') . $sign . data_get (config ('coupon'), 'jingdong.JDM_APP_SECRET')));
        $params['sign'] = $sign;
        $params['360buy_param_json'] = json_encode ($urlparams);
        $response = Curl::to ('https://api.jd.com/routerjson')
            ->withData ($params)
            ->get ();
        $response = json_decode ($response);

        if (isset($response->error_response)) {
            throw new \InvalidArgumentException($response->error_response->zh_desc);
        }
        $result = json_decode ($response->jingdong_UnionService_queryOrderList_responce->result);

        if ($result->success != 1) {
            throw new \InvalidArgumentException($result->msg);
        }

        if (!isset($result->data)) {
            throw new \InvalidArgumentException('没有订单数据');
        }

        return $result;
    }



    /**
     * 爬虫.好京客.
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    public function spider(array $params)
    {
        $page = $params['page'] ?? 1;
        $apikey = [
            '773477d6b009b1a0',
            '8baf004e74c0b239',
            '1895f66dffd43f94',
        ];
        shuffle($apikey);
        shuffle($apikey);
        shuffle($apikey);
        $params = [
            'type' => 'goodslist',
            'apikey' => $apikey[0],
            'pageSize' => 100,
            'page' => $page,
        ];
        $response = Curl::to ('http://api-gw.haojingke.com/index.php/api/index/myapi')
            ->withData ($params)
            ->post ();
        $response = json_decode ($response);
        if (!$response) {
            throw new \InvalidArgumentException('接口没有数据');
        }
        if ($response->status_code != 200) {
            throw new \InvalidArgumentException($response->message);
        }

        return [
            'totalPage' => $response->totalpage,
            'data' => $response->data,
        ];
    }

    /**
     * @return array|mixed
     */
    public function hotSearch()
    {
        return [];
    }



    /**
     * 创建推广位
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function createPid(array $array = [])
    {
        $spaceName = rand(1,999999);
        $group_id = $array['group_id'];
        $setting = $this->getSettings($group_id);
        if (!$setting) {
            throw new \InvalidArgumentException('请联系管理员设置系统');
        }
        $unionid = $setting->unionid;
        $jingdong = $setting->jingdong;
        if (!isset($unionid['jingdong']) || !isset($jingdong['apikey']) || !isset($jingdong['key'])) {
            throw new \InvalidArgumentException('请先设置京东信息');
        }

        $params = [
            'type' => 'createjdpid',
            'apikey' => $jingdong['apikey'],
            'unionId' => $unionid['jingdong'],
            'key' => $jingdong['key'],
            'pidtype' => 4,
            'spaceName' => $spaceName,
        ];
        $rest = Curl::to('http://api-gw.haojingke.com/index.php/api/index/myapi')
            ->withData($params)
            ->asJsonResponse()
            ->post();
        if ($rest->status_code != 200) {
            throw new \InvalidArgumentException($rest->message);
        }
        return $rest->data->resultList;
    }


    /**
     * 获取优惠卷详情.
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    protected function getCoupon(array $array = [])
    {
        $url = $array['url'];
        if ($url == '') {
            return false;
        }
        $params = [
            'appid' => data_get (config ('coupon'), 'jingdong.JD_APPID'),
            'appkey' => data_get (config ('coupon'), 'jingdong.JD_APPKEY'),
            'url' => $url,
        ];
        $response = Curl::to ('http://japi.jingtuitui.com/api/get_coupom_info')
            ->withData ($params)
            ->post ();

        $response = json_decode ($response);

        if ($response->return != 0) {
            throw new \InvalidArgumentException($response->result);
        }

        return $response->result;
    }

}
