<?php

namespace App\Tools\Taoke;

use Ixudra\Curl\Facades\Curl;

class PinDuoDuo implements TBKInterface
{
    /**
     * @var mixed
     */
    protected $client_id;
    /**
     * @var mixed
     */
    protected $client_secret;

    /**
     * Duoduoke constructor.
     */
    public function __construct()
    {
        $config = config('coupon');
        $this->client_id = data_get($config, 'pinduoduo.PDD_CLIENT_ID');
        $this->client_secret = data_get($config, 'pinduoduo.PDD_CLIENT_SECRET');
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

        $id = data_get($array,'id');
        if (!is_numeric($id)) {
            return [
                'code'=>4004,
                'message' => '商品id类型错误！',
            ];
        }
        $time = time();
        $params = [
            'client_id' => $this->client_id,
            'goods_id_list' => "[$id]",
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.detail',

        ];

        $str = 'client_id'.$this->client_id.'goods_id_list['.$id.']timestamp'.$time.'typepdd.ddk.goods.detail';
        $sign = strtoupper(md5($this->client_secret.$str.$this->client_secret));

        $params['sign'] = $sign;

        $result = Curl::to('http://gw-api.pinduoduo.com/api/router')
            ->withData($params)
            ->post();
        $result = json_decode($result);

        if (isset($result->error_response)) {
            return [
                'code' => 4004,
                'message' => $result->error_response,
            ];
        }

        return [
            'code' => 1001,
            'message' => '获取成功',
            'data' => data_get($result,'goods_detail_response.goods_details.0',[]),
        ];


    }

    /**
     * @param array $array
     * @return mixed
     */
    public function search(array $array)
    {
        // TODO: Implement search() method.
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
        $page = data_get($array, 'page', 1);
        if ($page > 600) {
            return [
                'code' => 4004,
                'message' => '爬取完成',
            ];
        }
        $time = time();
        $params = [
            'client_id' => $this->client_id,
            'page' => $page,
            'page_size' => 100,
            'sort_type' => 6,
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.search',
            'with_coupon' => 'true',
        ];

        $str = 'client_id'.$this->client_id.'page'.$page.'page_size100'.'sort_type6'.'timestamp'.$time.'typepdd.ddk.goods.search'.'with_coupontrue';
        $sign = strtoupper(md5($this->client_secret.$str.$this->client_secret));

        $params['sign'] = $sign;
        $result = Curl::to('http://gw-api.pinduoduo.com/api/router')
            ->withData($params)
            ->post();
        $result = json_decode($result);

        if (isset($result->error_response)) {
            return [
                'code' => 4004,
                'message' => $result->error_response->error_msg,
            ];
        }

        if (isset($result->goods_search_response)) {
            return [
                'code' => 1001,
                'data' => [
                    'total_count' => $result->goods_search_response->total_count > 60000 ? 60000 : $result->goods_search_response->total_count,
                    'goods_list' => $result->goods_search_response->goods_list,
                ],
            ];
        }
    }
}
