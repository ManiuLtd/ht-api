<?php

namespace App\Tools\Taoke;

use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Orzcc\TopClient\Facades\TopClient;
use TopClient\request\TbkItemInfoGetRequest;
use TopClient\request\WirelessShareTpwdQueryRequest;

class Taobao implements TBKInterface
{
    /**
     * 大淘客API KEY
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $DTK_API_KEY;

    /**
     * 大淘客接口地址
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $DTK_API_URL;

    /**
     * 轻淘客API KEY
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $QTK_API_KEY;

    /**
     * 轻淘客接口地址
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $QTK_API_URL;

    /**
     * 淘客基地API KEY
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $TKJD_API_KEY;

    /**
     * 淘客接地接口地址
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $TKJD_API_URL;
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $HMTK_APP_KEY;
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $HMTK_APP_SECRET;
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $HDK_APIKEY;
    /**
     * Taobao constructor.
     */
    public function __construct()
    {
        $this->DTK_API_KEY = config('coupon.taobao.DTK_API_KEY');
        $this->DTK_API_URL = config('coupon.taobao.DTK_API_URL');
        $this->QTK_API_KEY = config('coupon.taobao.QTK_API_KEY');
        $this->QTK_API_URL = config('coupon.taobao.QTK_API_URL');
        $this->TKJD_API_KEY = config('coupon.taobao.TKJD_API_KEY');
        $this->TKJD_API_URL = config('coupon.taobao.TKJD_API_URL');
        $this->HMTK_APP_KEY = config('coupon.taobao.HMTK_APP_KEY');
        $this->HMTK_APP_SECRET = config('coupon.taobao.HMTK_APP_SECRET');
        $this->HDK_APIKEY = config('coupon.taobao.HDK_APIKEY');
    }

    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed|void
     */
    public function getCouponUrl(array $array = [])
    {
        // TODO: Implement getCouponUrl() method.
    }

    /**
     * 获取详情.
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getDetail(array $array = [])
    {
        $itemID = request('item_id');
        if (! is_numeric($itemID)) {
            throw  new \InvalidArgumentException('商品id类型错误');
        }

        $topclient = TopClient::connection();
        $req = new TbkItemInfoGetRequest();
        $req->setFields('title,small_images,pict_url,zk_final_price,user_type,volume');
        $req->setNumIids($itemID);
        $resp = $topclient->execute($req);

        if (! isset($resp->results->n_tbk_item)) {
            throw new \Exception('淘宝客接口调用失败');
        }

        return $resp->results->n_tbk_item[0];
    }

    /**
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function search(array $array = [])
    {
        //TODO  修改
        $page = request('page') ?? 1;
        $limit = request('limit') ?? 20;
        $q = request('q') ?? '';

        //TODO 检查关键词是否包含淘口令，如果包含淘口令，使用产品地址搜索，调用下面的searchByTKL方法

        $params = [
            'appkey' => $this->TKJD_API_KEY,
            'k' => $q,
            'page' => $page,
            'page_size' => $limit,
        ];

        //排序字段
        $params['sort'] = 'sales';
        switch (request('orderBy')) {
            case 'sales':
                $params['sort'] = 'sales';
                break;
            case 'coupon':
                $params['sort'] = 'coupon';
                break;
            case 'commission':
                $params['sort'] = 'comm_rate';
                break;
            default:
                break;
        }
        //排序方式
        $params['sort_type'] = request('sortedBy') == 'asc' ? 'asc' : 'desc';
        //获取接口内容
        $response = Curl::to('http://api.tkjidi.com/checkWhole')
            ->withData($params)
            ->get();

        $response = json_decode($response);

        //接口信息获取失败
        if ($response->status != 200) {
            throw new \Exception('淘客基地接口请求失败');
        }
        //当前页面地址
        $uri = request()->getUri();
        //验证是否填写page参数
        if (! str_contains('page=', $uri)) {
            $uri = $uri.'&page=1';
        }

        //页码信息
        $totalPage = intval(floor($response->data->total / $limit) + 1);

        //页码不对
        if ($page > $totalPage) {
            throw new \Exception('超出最大页码');
        }

        //重组字段
        $data = [];
        foreach ($response->data->data as $list) {
            $temp = [
                'title' => $list->goods_name,
                'pic_url' => $list->pic,
                'cat' => '',
                'shop_type' => $list->tmall ? 2 : 1,
                'item_id' => $list->goods_id,
                'item_url' => $list->goods_url,
                'volume' => $list->sales,
                'price' => $list->price,
                'final_price' => $list->price_after_coupons,
                'coupon_price' => $list->price_coupons,
                'coupon_link' => '',
                'activity_id' => '',
                'commission_rate' => $list->rate,
                'type' => 1,
                'introduce' => '',
                'start_time' => $list->quan_starttime,
                'end_time' => $list->quan_endtime,
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ];
            array_push($data, $temp);
        }

        return [
            'data' => $data,
            //分页信息只要这四个参数就够了
            'meta' => [
                'current_page' => (int) $page,
                'last_page' => $totalPage,
                'per_page' => $limit,
                'total' => $response->data->total,
            ],
        ];
    }

    /**
     * 获取订单.
     * @param array $array
     * @return mixed
     */
    public function getOrders(array $array = [])
    {
        //  Implement getOrders() method.
        $params = [
            'appkey' => $this->HMTK_APP_KEY,
            'appsecret' => $this->HMTK_APP_SECRET,
            'sid' => '1942',
            'start_time' => now()->subMinutes(9)->toDateTimeString(),
            'span' => 600,
            'signurl' => 0,
            'page_no' => data_get($array,'page',1),
            'page_size' => 100,
        ];

        $resp = Curl::to('https://www.heimataoke.com/api-qdOrder')
            ->withData($params)
            ->get();
        $resp = json_decode($resp);

        if (!isset($resp->n_tbk_order)) {
            return [
                'code' => 5001,
                'message' => '没有数据',
            ];
        }

        return [
            'code' => 1001,
            'message' => '获取成功',
            'data' => $resp->n_tbk_order,
        ];

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
     * 爬虫
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
//    public function spider(array $params = [])
//    {
//        $type = $params['type'] ?? 'total';
//        $all = $params['all'] ?? true;
//        $page = $params['page'] ?? 1;
//
//        $params = [
//            'r' => 'Port/index',
//            'appkey' => $this->DTK_API_KEY,
//            'v' => '2',
//            'page' => $page,
//        ];
//        //爬虫类型
//        switch ($type) {
//            case 'total':
//                $params['type'] = 'total';
//                break;
//            case 'paoliang':
//                $params['type'] = 'paoliang';
//                break;
//            case 'top100':
//                $params['type'] = 'top100';
//                break;
//            default:
//                $params['type'] = 'total';
//                break;
//        }
//        $response = Curl::to($this->DTK_API_URL)
//            ->withData($params)
//            ->get();
//        $response = json_decode($response);
//
//        //验证
//        if (! isset($response->data)) {
//            throw new \Exception('大淘客接口内容获取失败');
//        }
//        $total = $response->data->total_num ?? 0;
//        if ($total <= 0) {
//            throw new \Exception('打通可爬虫没有获取到产品');
//        }
//        $totalPage = (int) ceil($total / 50);
//
//        //不爬取所有的
//        if (! $all) {
//            $totalPage = 3;
//        }
//
//        return [
//            'data' => $response->result,
//            'totalPage' => $totalPage,
//            'total' => $total,
//        ];
//    }

    /**
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function hotSearch(array $array = [])
    {
        $params = [
            'apikey' => $this->HDK_APIKEY,
            'hot' => 1,
        ];

        $resp = Curl::to('http://v2.api.haodanku.com/hot_key')
            ->withData($params)
            ->get();
        $resp = json_decode($resp);

        if ($resp->code != 1) {
            throw new \Exception($resp->msg);
        }
        return [
            'code'=>1001,
            'message'=>$resp->msg,
            'data'=>$resp->data
        ];
//        return array_slice($resp->data, 0, 20);
    }

    /**
     * 淘口令解密.
     * @param $keywords
     * @return array|bool|mixed|string
     * @throws \Exception
     */
    protected function searchByTKL($keywords)
    {
        //验证淘口令
        if (substr_count($keywords, '￥') == 2 || substr_count($keywords, '《') == 2 || substr_count($keywords, '€') == 2) {
            $req = new WirelessShareTpwdQueryRequest();

            $req->setPasswordContent($keywords);
            $topclient = TopClient::connection();
            $response = $topclient->execute($req);
            //淘口令解密失败
            if (! $response->suc) {
                return false;
            }
            if (str_contains($response->url, 'a.m.taobao.com/i')) {
                $pos = strpos($response->url, '?');
                $str = substr($response->url, 0, $pos);
                $str = str_replace('https://a.m.taobao.com/i', '', $str);
                $str = str_replace('.htm', '', $str);

                return $str;
            }
            $pos = strpos($response->url, '?');
            $query_string = substr($response->url, $pos + 1, strlen($response->url));
            $arr = \League\Uri\extract_query($query_string);

            if (isset($arr['activity_id'])) {
                return false;
            }

            if (! isset($arr['id'])) {
                return false;
            }
            return $arr['id'];
        }

        return false;
    }

    /**
     * @param array $array
     * @return array|mixed
     */
    public function spider(array $array = [])
    {
        $type = $array['type'] ?? 3;

        $min_id = $array['min_id'] ?? 1;

        if (!in_array($type,[1,2,3,4,5])) {
            return [
                'code' => 4001,
                'message' => 'type不合法',
            ];
        }
        $params = [
            'apikey' => $this->HDK_APIKEY,
            'nav' => $type,
            'cid' => 0,
            'back' => 100,
            'min_id' => $min_id,
        ];
        $resp = Curl::to('http://v2.api.haodanku.com/itemlist')
            ->withData($params)
            ->get();
        $resp = json_decode($resp);
        if ($resp->code != 1) {
            return [
                'code' => 4001,
                'message' => $resp->msg,
            ];
        }
        return [
            'code' => 1001,
            'message' => $resp->msg,
            'data' => [
                'min_id' => $resp->min_id,
                'data' => $resp->data,
            ],
        ];

    }


    /**
     *  好货专场
     * @param array $array
     * @return mixed
     */
    public function HaohuoZC(array $array = [])
    {
        $min_id = data_get($array, 'min_id', 1);
        $params = [
            'apikey' => $this->HDK_APIKEY,
            'min_id' => $min_id,
        ];
        $resp = Curl::to('http://v2.api.haodanku.com/subject_hot')
            ->withData($params)
            ->get();
        return $resp;
    }

    /**
     * 精选单品
     * @param array $array
     * @return mixed
     */
    public function JingxuanDP(array $array = [])
    {
        $min_id = data_get($array,'min_id',1);
        $params = [
            'apikey' => $this->HDK_APIKEY,
            'min_id' => $min_id,
        ];
        $rest = Curl::to('http://v2.api.haodanku.com/selected_item')
            ->withData($params)
            ->get();
        $rest = json_decode($rest);
        if ($rest->code != 1) {
            return [
                'code' => 4001,
                'message' => $rest->msg
            ];
        }
        return [
            'code' => 1001,

            'message' => $rest->msg,
            'data' => [
                'data' => $rest->data,
                'min_id' => $rest->min_id,
            ],
        ];
    }

    /**
     * 精选专题
     * @param array $array
     * @return mixed
     */
    public function JingxuanZT(array $array = [])
    {
        $params = [
            'apikey' => $this->HDK_APIKEY
        ];
        $resp = Curl::to('http://v2.api.haodanku.com/get_subject')
            ->withData($params)
            ->get();
        $res = json_decode($resp);
        if ($res->code == 0){
            return json('5001','获取失败');
        }
        return $res;
    }

    /**
     * 快抢商品
     * @param array $array
     * @return mixed
     */
    public function KuaiqiangShop(array $array = [])
    {
        $type = $params['hour_type'] ?? 7;
        $min_id = $params['min_id'] ?? 1;
        $params = [
            'apikey' => $this->HDK_APIKEY,
            'hour_type' => $type,
            'min_id' => $min_id,
        ];
        $rest = Curl::to('http://v2.api.haodanku.com/fastbuy')
            ->withData($params)
            ->get();
        $rest = json_decode($rest);
        if ($rest->code != 1) {
            return [
                'code' => 4001,
                'message' => $rest->msg
            ];
        }
        return [
            'code' => 1001,
            'message' => $rest->msg,
            'data' => [
                'data' => $rest->data,
                'min_id' => $rest->min_id,
            ],
        ];
    }


    /**
     * 定时拉取
     * @param array $array
     * @return mixed
     */
    public function TimingItems(array $array = [])
    {
        //获取最近整点时间
        $timestamp = date('H',time());//当前时间的整点
        $min_id = data_get($array,'min_id',1);
        $params = [
            'apikey' => $this->HDK_APIKEY,
            'start' => $timestamp,
            'end' => $timestamp+1,
            'min_id' => $min_id,
            'back' => 100 //请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回
        ];
        $results = Curl::to('http://v2.api.haodanku.com/timing_items')
            ->withData($params)
            ->get();
        return $results;
    }

    /**
     * 商品更新
     * @param array $array
     * @return mixed
     */
    public function UpdateItem(array $array = [])
    {

    }

    /**
     * 失效商品列表
     * @param array $array
     * @return mixed
     */
    public function DownItems(array $array = [])
    {

    }



}
