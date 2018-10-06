<?php

namespace App\Tools\Spider;


use Ixudra\Curl\Facades\Curl;

class TaoBao
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {

        $config = config('coupon');
        $this->apiKey = data_get($config,'taobao.TB_API_KEY');
        $this->apiUrl = data_get($config,'taobao.TB_API_URL');
    }

    public function DTKSpider($type='total', $all='true',$page=1)
    {
        $params = [
            'r' => 'Port/index',
            'appkey' => $this->apiKey,
            'v' => '2',
            'page' => $page
        ];

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
        if (!isset($response->data)) {

            return [
                'code'=>4001,
                'message' => '接口内容获取失败'
            ];
        }
        $total = $response->data->total_num ?? 0;
        if ($total <= 0) {
            return [
                'code'=>4001,
                'message' => '没有获取到产品'
            ];
        }
        $totalPage = (int)ceil($total / 50);
        //不爬取所有的
        if ($all == 'false') {
            $totalPage = 3;
        }

        return [
            'code' => '2000',
            'message' => '',
            'data' => [
                'totalPage' => $totalPage,
                'total' => $total,
                'result' => $response->result,

            ],
        ];





    }
}