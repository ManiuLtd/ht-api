<?php

namespace App\Tools\Taoke;

use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

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
     * Taobao constructor.
     */
    public function __construct()
    {
        $this->apiKey = data_get (config ('coupon'), 'taobao.TB_API_KEY');
        $this->apiUrl = data_get (config ('coupon'), 'taobao.TB_API_URL');
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
        // TODO: Implement getDetail() method.
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
        $type = data_get($array,'type','total');
        $all = data_get($array,'all',true);
        $page = data_get($array,'page',1);

        $params = [
            'r' => 'Port/index',
            'appkey' => $this->apiKey,
            'v' => '2',
            'page' => $page
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
        $response = Curl::to ($this->apiUrl)
            ->withData ($params)
            ->get ();
        $response = json_decode ($response);

        //验证
        if (!isset($response->data)) {
            return [
                'code' => 4001,
                'message' => '接口内容获取失败'
            ];
        }
        $total = $response->data->total_num ?? 0;
        if ($total <= 0) {
            return [
                'code' => 4001,
                'message' => '没有获取到产品'
            ];
        }
        $totalPage = (int)ceil ($total / 50);

        //不爬取所有的
        if ($all== 'false') {
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
}
