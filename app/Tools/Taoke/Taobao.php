<?php

namespace App\Tools\Taoke;

use Ixudra\Curl\Facades\Curl;
use Orzcc\TopClient\Facades\TopClient;
use TopClient\request\TbkItemInfoGetRequest;
use TopClient\request\WirelessShareTpwdQueryRequest;

class Taobao implements TBKInterface
{
    /**
     * @var mixed
     */
    protected $apiKey;

    /**
     * @var mixed
     */
    protected $apiUrl;
    /**
     * @var
     */
    protected $qtk_app_key;

    /**
     * Taobao constructor.
     */
    public function __construct()
    {
        $this->apiKey = data_get(config('coupon'), 'taobao.TB_API_KEY');
        $this->apiUrl = data_get(config('coupon'), 'taobao.TB_API_URL');
        $this->qtk_app_key = data_get(config('coupon'), 'qingtaoke.APP_KEY');
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
     * @return mixed
     */
    public function getDetail(array $array)
    {
        //  Implement getDetail() method.
        $id = data_get($array, 'id');
        if (! is_numeric($id)) {
            return [
                'code'=>4004,
                'message' => '商品id类型错误！',
            ];
        }
        $topclient = TopClient::connection();
        $req = new TbkItemInfoGetRequest();
        $req->setFields('title,small_images,pict_url,zk_final_price,user_type,volume');
        $req->setNumIids($id);
        $resp = $topclient->execute($req);

        if (! isset($resp->results->n_tbk_item)) {
            return [
                'code' => 4004,
                'message' => '淘宝客接口调用失败',
            ];
        }
        $data = (array) $resp->results->n_tbk_item[0];

        return [
            'code' => 1001,
            'message' => '商品详情获取成功',
            'data' => $data,
        ];
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function search(array $array)
    {
        //Implement search() method.
        $page = $array['page'];
        $type = $array['type'];
        $sort = $array['sort'];
        $q = $array['q'];
        $keywords = $array['keywords'];
        if ($keywords) {
            $data_q = $this->searchByTKL($keywords);
            if ($data_q['code'] == 4001) {
                return [
                    'code' => 4001,
                    'message' => $data_q['message'],
                ];
            }
            $q = data_get($data_q, 'data.itemid');
        }

        $params = [
            'appkey' => 'a702d09d248becb575dc798b6e432d88',
            'k' => $q,
            'page' => $page,
        ];
        //sort 1最新 2低价 3高价 4销量 5佣金 6综合
        switch ($sort) {
            case 1:
                $params['sort'] = 'coupon';
                $params['sort_type'] = 'desc';
                break;
            case 2:
                $params['sort'] = 'price';
                $params['sort_type'] = 'asc';
                break;
            case 3:
                $params['sort'] = 'price';
                $params['sort_type'] = 'desc';
                break;
            case 4:
                $params['sort'] = 'sales';
                $params['sort_type'] = 'desc';
                break;
            case 5:
                $params['sort'] = 'comm_rate';
                $params['sort_type'] = 'desc';
                break;
            case 6:
                break;
            default:
                break;
        }
        //获取接口内容
        $response = Curl::to('http://api.tkjidi.com/checkWhole')
            ->withData($params)
            ->get();
        $response = json_decode($response);

        //接口信息获取失败
        if ($response->status != 200) {
            return [
                'code' => 4001,
                'message' => $response->msg,
            ];
        }
        //当前页面地址
        $uri = request()->getUri();
        //验证是否填写page参数
        if (! str_contains('page=', $uri)) {
            $uri = $uri.'&page=1';
        }

        //页码信息
        $totalPage = intval(floor($response->data->total / 20) + 1);
        $prevPage = $page - 1;
        $nextPage = $page + 1;
        //页码不对
        if ($page > $totalPage) {
            return response()->json([
                'code' => 4001,
                'message' => '超出最大页码',
            ]);
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
                'created_at' => '',
                'updated_at' => '',
            ];
            array_push($data, $temp);
        }

        return [
            'data' => $data,
            'links' => [
                'first' => str_replace("page={$page}", 'page=1', $uri),
                'last' => str_replace("page={$page}", "page={$totalPage}", $uri),
                'prev' => $page == 1 ? null : str_replace("page={$page}", "page={$prevPage}", $uri),
                'next' => str_replace("page={$page}", "page={$nextPage}", $uri),
            ],
            'meta' => [
                'current_page' => (int) $page,
                'from' => 1,
                'last_page' => $totalPage,
                'path' => request()->url(),
                'per_page' => 20,
                'to' => 20 * $page,
                'total' => $response->data->total,
            ],
            'code' => 1001,
            'message' => '优惠券获取成功',
        ];
    }

    /**
     * 淘口令.
     * @return array
     */
    public function searchByTKL($keywords)
    {
        //验证淘口令
        if (substr_count($keywords, '￥') == 2 || substr_count($keywords, '《') == 2 || substr_count($keywords, '€') == 2) {
            $req = new WirelessShareTpwdQueryRequest();

            $req->setPasswordContent($keywords);
            $topclient = TopClient::connection();
            $response = $topclient->execute($req);
            //淘口令解密失败
            if (! $response->suc) {
                return [
                    'code' => 4001,
                    'message' => '淘口令解密失败',
                ];
            }
            if (str_contains($response->url, 'a.m.taobao.com/i')) {
                $pos = strpos($response->url, '?');
                $str = substr($response->url, 0, $pos);
                $str = str_replace('https://a.m.taobao.com/i', '', $str);
                $str = str_replace('.htm', '', $str);

                return [
                    'code' => 1001,
                    'message' => '产品ID获取成功',
                    'data' => [
                        'itemid' => $str,
                    ],
                ];
            }
            $pos = strpos($response->url, '?');
            $query_string = substr($response->url, $pos + 1, strlen($response->url));
            $arr = \League\Uri\extract_query($query_string);

            if (isset($arr['activity_id'])) {
                return [
                    'code' => 4001,
                    'message' => '该淘口令为优惠券淘口令，不需要转换，打开手淘即可领券。',
                ];
            }
            if (! isset($arr['id'])) {
                return [
                    'code' => 4001,
                    'message' => '不支持该淘口令',
                ];
            }

            return [
                'code' => 1001,
                'message' => '产品ID获取成功',
                'data' => [
                    'itemid' => $arr['id'],
                ],
            ];
        }

        return [
            'code' => 4001,
            'message' => '该字符串不包含淘口令',
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
     * @return mixed
     */
    public function spider(array $array = [])
    {
        // TODO: Implement spider() method.
        $type = data_get($array, 'type', 'total');
        $all = data_get($array, 'all', true);
        $page = data_get($array, 'page', 1);

        $params = [
            'r' => 'Port/index',
            'appkey' => $this->apiKey,
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
            return [
                'code' => 4001,
                'message' => '接口内容获取失败',
            ];
        }
        $total = $response->data->total_num ?? 0;
        if ($total <= 0) {
            return [
                'code' => 4001,
                'message' => '没有获取到产品',
            ];
        }
        $totalPage = (int) ceil($total / 50);

        //不爬取所有的
        if ($all == 'false') {
            $totalPage = 3;
        }

        return [
            'code' => 1001,
            'message' => '优惠券获取成功',
            'data' => [
                'totalPage' => $totalPage,
                'total' => $total,
                'result' => $response->result,

            ],
        ];
    }

    /**.
     * @param array $array
     * @return array|mixed
     */
    public function hotSearch(array $array = [])
    {
        $params = [
            'app_key' => $this->qtk_app_key,
            'v' => '1.0',
            't' => 1,
        ];
        $resp = Curl::to('http://openapi.qingtaoke.com/hot')
            ->withData($params)
            ->get();
        $resp = json_decode($resp);

        if ($resp->er_code != 10000) {
            return [
                'code' => 4001,
                'message' => $resp->er_msg,
            ];
        }

        return [
            'code' => 1001,
            'message' => '获取成功',
            'data' => array_slice($resp->data, 0, 20),
        ];
    }
}
