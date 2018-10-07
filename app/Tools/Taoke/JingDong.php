<?php

namespace App\Tools\Taoke;



use Illuminate\Support\Facades\Log;
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
     * JingDong constructor.
     */
    public function __construct()
    {
        $this->appid = data_get (config ('coupon'), 'jingdong.JD_APPID');
        $this->appkey = data_get (config ('coupon'), 'jingdong.JD_APPKEY');
        $this->applisturl = data_get (config ('coupon'), 'jingdong.JD_LIST_APPURL');
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
     * @return array|mixed
     */
    public function spider(array $array = [])
    {
        // TODO: Implement spider() method.

        $page = data_get($array,'page',1);

        $params = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'num' => 100,
            'page' => $page
        ];
        $response = Curl::to ($this->applisturl)
            ->withData ($params)
            ->post ();
        $response = json_decode ($response);
        if ($response->return != 0) {
            return [
                'code' => 4001,
                'message' => $response->result
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
}
