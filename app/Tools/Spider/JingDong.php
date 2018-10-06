<?php
/**
 * Created by PhpStorm.
 * User: hongtang
 * Date: 2018/10/6
 * Time: 15:58
 */

namespace App\Tools\Spider;


use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class JingDong
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

    public function __construct()
    {
        $config = config('coupon');
        $this->appid = data_get($config,'jingdong.JD_APPID');
        $this->appkey = data_get($config,'jingdong.JD_APPKEY');
        $this->applisturl = data_get($config,'jingdong.JD_LIST_APPURL');
    }

    public function JTTSpider($page=1)
    {
        $params = [
            'appid' => $this->appid,
            'appkey' => $this->appkey,
            'num' => 100,
            'page' => $page
        ];
        $response = Curl::to($this->applisturl)
            ->withData($params)
            ->post();
        $response = json_decode($response);
        if ($response->return != 0) {
            return [
                'code' => 4001,
                'message' => $response->result
            ];
        }
        return [
            'code' => 200,
            'data' => [
                'totalPage' => $response->result->total_page,
                'data' => $response->result->data,
            ],

        ];

    }


}