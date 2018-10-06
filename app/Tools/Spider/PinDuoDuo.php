<?php
/**
 * Created by PhpStorm.
 * User: hongtang
 * Date: 2018/10/6
 * Time: 16:49
 */

namespace App\Tools\Spider;


use Ixudra\Curl\Facades\Curl;

class PinDuoDuo
{
    protected $client_id;
    protected $client_secret;

    /**
     * Duoduoke constructor.
     */
    public function __construct()
    {
        $config = config('coupon');
        $this->client_id = data_get($config,'pinduoduo.PDD_CLIENT_ID');
        $this->client_secret = data_get($config,'pinduoduo.PDD_CLIENT_SECRET');
    }

    /**
     * @param int $page
     * @return array
     */
    public function PDDSearch($page=1)
    {
        $time = time();


        $params = [
            'client_id' => $this->client_id,
            'page' => $page,
            'page_size' => 100,
            'sort_type' => 0,
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.search',
//            'with_coupon' => 'true',
        ];

        $str = 'client_id'.$this->client_id.'page'.$page.'page_size100'.'sort_type0'.'timestamp'.$time.'typepdd.ddk.goods.search';
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
                    'total_count' => $result->goods_search_response->total_count,
                    'goods_list' => $result->goods_search_response->goods_list,
                ],
            ];
        }
    }


}