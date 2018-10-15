<?php

namespace App\Tools\Taoke;

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
     * Taobao constructor.
     */
    public function __construct()
    {
        $this->DTK_API_KEY = config('taobao.DTK_API_KEY');
        $this->DTK_API_URL = config('taobao.DTK_API_URL');
        $this->QTK_API_KEY = config('taobao.QTK_API_KEY');
        $this->QTK_API_URL = config('taobao.QTK_API_URL');
        $this->TKJD_API_KEY = config('taobao.TKJD_API_KEY');
        $this->TKJD_API_URL = config('taobao.TKJD_API_URL');
    }

    /**
     * 获取优惠券地址
     * @return mixed|void
     */
    public function getCouponUrl()
    {
        // TODO: Implement getCouponUrl() method.
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getDetail()
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
     * @return array|\Illuminate\Http\JsonResponse|mixed
     * @throws \Exception
     */
    public function search()
    {
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
     * 爬虫
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    public function spider(array $params = [])
    {
        $type = $params['type'] ?? 'total';
        $all = $params['all'] ?? true;
        $page = $params['page'] ?? 1;

        $params = [
            'r' => 'Port/index',
            'appkey' => $this->DTK_API_KEY,
            'v' => '2',
            'page' => $page,
        ];
        //爬虫类型
        switch ($type) {
            case 'total':
                $params['type'] = 'total';
                break;
            case 'paoliang':
                $params['type'] = 'paoliang';
                break;
            case 'top100':
                $params['type'] = 'top100';
                break;
            default:
                $params['type'] = 'total';
                break;
        }
        $response = Curl::to($this->apiUrl)
            ->withData($params)
            ->get();
        $response = json_decode($response);

        //验证
        if (! isset($response->data)) {
            throw new \Exception('大淘客接口内容获取失败');
        }
        $total = $response->data->total_num ?? 0;
        if ($total <= 0) {
            throw new \Exception('打通可爬虫没有获取到产品');
        }
        $totalPage = (int) ceil($total / 50);

        //不爬取所有的
        if (! $all) {
            $totalPage = 3;
        }

        return [
            'data' => $response->result,
            'totalPage' => $totalPage,
            'total' => $total,
        ];
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function hotSearch()
    {
        $params = [
            'app_key' => $this->QTK_API_KEY,
            'v' => '1.0',
            't' => 1,
        ];
        $resp = Curl::to($this->QTK_API_URL.'/hot')
            ->withData($params)
            ->get();
        $resp = json_decode($resp);

        if ($resp->er_code != 10000) {
            throw new \Exception($resp->er_msg);
        }

        return array_slice($resp->data, 0, 20);
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
}
