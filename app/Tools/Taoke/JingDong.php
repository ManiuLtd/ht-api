<?php

namespace App\Tools\Taoke;

use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;

class JingDong implements TBKInterface
{
    /**
     * @var
     */
    protected $appid;
    /**
     * @var
     */
    protected $appkey;
    /**
     * @var
     */
    protected $applisturl;
    /**
     * @var
     */
    protected $access_token;
    /**
     * @var mixed
     */
    protected $jdm_app_key;

    /**
     * @var
     */
    protected $jdm_app_secret;

    /**
     * JingDong constructor.
     */
    public function __construct()
    {
        $this->appid = data_get (config ('coupon'), 'jingdong.JD_APPID');
        $this->appkey = data_get (config ('coupon'), 'jingdong.JD_APPKEY');
        $this->applisturl = data_get (config ('coupon'), 'jingdong.JD_LIST_APPURL');
        $this->access_token = data_get (config ('coupon'), 'jingdong.access_token');
        $this->jdm_app_key = data_get (config ('coupon'), 'jingdong.JDM_APP_KEY');
        $this->jdm_app_secret = data_get (config ('coupon'), 'jingdong.JDM_APP_SECRET');
    }

    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed
     */
    public function getCouponUrl(array $array)
    {
        // TODO: Implement getCouponUrl() method.
    }

    /**
     * 获取详情.
     * @param array $array
     * @return array|mixed
     */
    public function getDetail(array $array)
    {
        // Implement getDetail() method.
        $id = data_get ($array, 'id');
        if (!is_numeric ($id)) {
            return [
                'code' => 4004,
                'message' => '商品id类型错误！',
            ];
        }
        $params = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'gid' => $id,
        ];
        $response = Curl::to ('http://japi.jingtuitui.com/api/get_goods_info')
            ->withData ($params)
            ->post ();
        $response = json_decode ($response);
        if ($response->return != 0) {
            return [
                'code' => 4004,
                'message' => '接口调用失败！',
            ];
        }

        return [
            'code' => 1001,
            'message' => '获取成功',
            'data' => $response->result,
        ];
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function search(array $array)
    {
        //  Implement search() method.
        $page = data_get ($array, 'page', 1);
        $q = $array['q'];
        $sort = $array['sort'];
        $time = now ()->toDateTimeString ();
        $params = [
            'method' => 'jingdong.union.search.queryCouponGoods',
            'access_token' => $this->access_token,
            'app_key' => $this->jdm_app_key,
            'timestamp' => $time,
            'v' => '2.0',
        ];
        $urlparams = [

            'goodsKeyword' => $q,
            'pageIndex' => $page,

        ];
        //sort 1最新 2低价 3高价 4销量 5佣金 6综合
//        switch ($sort) {
//            case 1:
//                $urlparams['sort_name'] = 'inOrderComm30Days';
//                $urlparams['sort'] = 'desc';
//                break;
//            case 2:
//                $urlparams['sort'] = 'pcPrice';
//                $urlparams['sort_type'] = 'asc';
//                break;
//            case 3:
//                $urlparams['sort'] = 'pcPrice';
//                $urlparams['sort_type'] = 'desc';
//                break;
//            case 4:
//                $urlparams['sort'] = 'inOrderCount30Days';
//                $urlparams['sort_type'] = 'desc';
//                break;
//            case 5:
//                $urlparams['sort'] = 'pcCommission';
//                $urlparams['sort_type'] = 'desc';
//                break;
//            case 6:
//                break;
//            default:
//                break;
//        }
        $signparams = array_merge ($params, $urlparams);
        ksort ($signparams);
        $sign = http_build_query ($signparams);
        $sign = strtoupper (md5 ($this->jdm_app_secret . $sign . $this->jdm_app_secret));
        $params['sign'] = $sign;
        $params['360buy_param_json'] = json_encode ($urlparams);
        $response = Curl::to ('https://api.jd.com/routerjson')
            ->withData ($params)
            ->get ();
        dd ($response);

        if ($response->return != 0) {
            return [
                'code' => 4001,
                'message' => $response->result,
            ];
        }
        $data = [];
        foreach ($response->result->data as $datum) {
            $temp['title'] = $datum->goods_name;
            $temp['cat'] = '';
            $temp['pic_url'] = $datum->goods_img;
            $temp['item_id'] = $datum->goods_id;
            $temp['item_url'] = $datum->goods_link;
            $temp['price'] = $datum->goods_price;
            $temp['final_price'] = $datum->coupon_price;
            $temp['coupon_price'] = $datum->discount_price;
            $temp['coupon_link'] = $datum->discount_link;
            $temp['commission_rate'] = $datum->commission;
            $temp['introduce'] = $datum->goods_content;
            $temp['type'] = 2;
            $temp['status'] = 0;
            $temp['start_time'] = Carbon::createFromTimestamp (intval ($datum->discount_start / 1000))->toDateTimeString ();
            $temp['end_time'] = Carbon::createFromTimestamp (intval ($datum->discount_end / 1000))->toDateTimeString ();
            $temp['created_at'] = Carbon::now ()->toDateTimeString ();
            $temp['updated_at'] = Carbon::now ()->toDateTimeString ();
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
        $totalPage = $response->result->total_page;
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        //页码不对
        if ($page > $totalPage) {
            return response ()->json ([
                'code' => 4001,
                'message' => '超出最大页码',
            ]);
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
                'total' => count ($data),
            ],
            'code' => 1001,
            'message' => '优惠券获取成功',
        ];
    }

    /**
     * 获取订单.
     * @param array $array
     * @return mixed
     */
    public function getOrders(array $array = [])
    {
        // TODO: Implement getOrders() method.
    }

    /**
     * 自动绑定订单.
     * @param array $array
     * @return mixed
     */
    public function autoBindOrder(array $array = [])
    {
        // TODO: Implement autoBindOrder() method.
    }

    /**
     * 手动提交订单.
     * @param array $array
     * @return mixed
     */
    public function submitOrder(array $array)
    {
        // TODO: Implement submitOrder() method.
    }

    /**
     * 爬虫.
     * @param array $array
     * @return array|mixed
     */
    public function spider(array $array = [])
    {
        // TODO: Implement spider() method.

        $page = data_get ($array, 'page', 1);

        $params = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'num' => 100,
            'page' => $page,
        ];
        $response = Curl::to ($this->applisturl)
            ->withData ($params)
            ->post ();
        $response = json_decode ($response);
        if ($response->return != 0) {
            return [
                'code' => 4001,
                'message' => $response->result,
            ];
        }

        return [
            'code' => 1001,
            'message' => '优惠券获取成功',
            'data' => [
                'totalPage' => $response->result->total_page,
                'data' => $response->result->data,
            ],

        ];
    }

    /**
     * @return array|mixed
     */
    public function hotSearch()
    {
        return [];
    }
}
