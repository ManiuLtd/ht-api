<?php

namespace App\Tools\Taoke;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Orzcc\TopClient\Facades\TopClient;
use TopClient\request\TbkItemInfoGetRequest;
use TopClient\request\TbkTpwdCreateRequest;


class Taobao implements TBKInterface
{
    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getCouponUrl(array $array = [])
    {

        $pids = $this->getPids ();

        $oauth = DB::table ('tbk_oauth')->find ($pids->oauth_id);

        //  Implement getCouponUrl() method.
        $params = [
            'appkey' => config ('coupon.taobao.HMTK_APP_KEY'),
            'appsecret' => config ('coupon.taobao.HMTK_APP_SECRET'),
            'sid' => $oauth->sid,
            'pid' => $pids->taobao,
            'num_iid' => $array['item_id'],
        ];
        $resp = Curl::to ('https://www.heimataoke.com/api-zhuanlian')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);

        if (isset($resp->error_response)) {
            throw new \Exception($resp->error_response->sub_msg);
        }
        return $resp;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|null|object
     */
    protected function getPids()
    {
        $member = getMember ();
        $member_pid = DB::table ('tbk_pids')->where ('member_id', $member->id)->first ();

        if ($member_pid) {
            return $member_pid;
        }
        $inviter_pid = DB::table ('tbk_pids')->where ('member_id', $member->inviter_id)->first ();
        if ($inviter_pid) {
            return $inviter_pid;
        }
        $group = DB::table ('groups')->find ($member->group_id);
        $group_pid = DB::table ('tbk_pids')->where ('member_id', $group->member_id)->first ();
        return $group_pid;

    }

    /**
     * 获取详情.
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getDetail(array $array = [])
    {
        $itemID = $array['id'];
        if (!is_numeric ($itemID)) {
            throw  new \InvalidArgumentException('商品id类型错误');
        }

        //通过转链接口获取券额
        $topclient = TopClient::connection ();
        $req = new TbkItemInfoGetRequest();
        $req->setFields ('title,small_images,pict_url,zk_final_price,user_type,volume');
        $req->setNumIids ($itemID);
        $resp = $topclient->execute ($req);

        if (!isset($resp->results->n_tbk_item)) {
            throw new \Exception('淘宝客接口调用失败');
        }
        $data = $resp->results->n_tbk_item[0];
        $data->coupon = $this->getCouponUrl (['item_id' => $itemID]);
        $kouling = $this->taokouling ([
            'coupon_click_url' => $data->coupon->coupon_click_url,
            'pict_url' => $data->pict_url,
            'title' => $data->title,
        ]);
        $data->kouling = $kouling;
        return $data;
    }

    /**
     * 搜索
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function search(array $array = [])
    {
        $page = request ('page') ?? 1;
        $limit = request ('limit') ?? 20;
        $q = $array['q'] ?? request ('q');


        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'keyword' => $q,
            'back' => $limit,
            'min_id' => 1,
            'tb_p' => 1,
        ];
        $response = Curl::to ('http://v2.api.haodanku.com/supersearch')
            ->withData ($params)
            ->get ();

        $response = json_decode ($response);

        //接口信息获取失败
        if ($response->code != 1) {
            throw new \Exception('淘客基地接口请求失败');
        }
        //当前页面地址
        $uri = request ()->getUri ();
        //验证是否填写page参数
        if (!str_contains ('page=', $uri)) {
            $uri = $uri . '&page=1';
        }

        //页码信息
        $totalPage = intval (floor ($response->total / $limit) + 1);

        //页码不对
        if ($page > $totalPage) {
            throw new \Exception('超出最大页码');
        }

        //重组字段
        $data = [];

        foreach ($response->data as $list) {
            $temp = [
                'title' => $list->itemtitle,
                'pic_url' => $list->itempic,
                'cat' => '',
                'shop_type' => $list->shoptype == 'B' ? 2 : 1,
                'item_id' => $list->itemid,
                'volume' => $list->itemsale,
                'price' => $list->itemprice,
                'final_price' => $list->itemendprice,
                'coupon_price' => $list->couponmoney,
                'coupon_link' => '',
                'activity_id' => '',
                'commission_rate' => $list->tkrates,
                'type' => 1,
                'introduce' => '',
                'start_time' => $list->couponstarttime ? date ('Y-m-d H:i:s', $list->couponstarttime) : '',
                'end_time' => $list->couponendtime ? date ('Y-m-d H:i:s', $list->couponendtime) : '',

            ];
            array_push ($data, $temp);
        }

        return [
            'data' => $data,
            //分页信息只要这四个参数就够了
            'meta' => [
                'current_page' => (int)$page,
                'last_page' => $totalPage,
                'per_page' => $limit,
                'total' => $response->total,
            ],
        ];
    }

    /**
     * 获取订单
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getOrders(array $array = [])
    {
        //  Implement getOrders() method.
        $params = [
            'appkey' => config ('coupon.taobao.HMTK_APP_KEY'),
            'appsecret' => config ('coupon.taobao.HMTK_APP_SECRET'),
            'sid' => data_get ($array, 'sid', 1942),  //淘宝  京东 拼多多 授权 并保存授权信息
            'start_time' => now ()->subMinutes (9)->toDateTimeString (),
            'span' => 600,
            'signurl' => 0,
            'page_no' => data_get ($array, 'page', 1),
            'page_size' => 100,
        ];

        $resp = Curl::to ('https://www.heimataoke.com/api-qdOrder')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);

        if (!isset($resp->n_tbk_order)) {
            throw  new \Exception('没有数据');
        }

        return $resp->n_tbk_order;

    }

    /**
     * 热搜
     * @return mixed
     * @throws \Exception
     */
    public function hotSearch()
    {
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
        ];

        $resp = Curl::to ('http://v2.api.haodanku.com/hot_key')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);

        if ($resp->code != 1) {
            throw new \Exception($resp->msg);
        }
        return $resp->data;
    }


    /**
     * 获取全网优惠卷
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function spider(array $array = [])
    {
        $type = $array['type'] ?? 3;

        $min_id = $array['min_id'] ?? 1;

        if (!in_array ($type, [1, 2, 3, 4, 5])) {
            return [
                'code' => 4001,
                'message' => 'type不合法',
            ];
        }
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'nav' => $type,
            'cid' => 0,
            'back' => 100,
            'min_id' => $min_id,
        ];
        $resp = Curl::to ('http://v2.api.haodanku.com/itemlist')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);
        if ($resp->code != 1) {
            throw new \Exception($resp->msg);
        }
        return [
            'data' => $resp->data,
            'min_id' => $resp->min_id,
        ];

    }


    /**
     * 好货专场
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function haohuo(array $params)
    {
        $min_id = $params['min_id'] ?? 1;
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'min_id' => $min_id,
        ];
        $resp = Curl::to ('http://v2.api.haodanku.com/subject_hot')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);
        if ($resp->code != 1) {
            throw new \Exception($resp->msg);
        }
        return $resp;
    }

    /**
     * 精选单品
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function danpin(array $params)
    {
        $min_id = $params['min_id'] ?? 1;
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'min_id' => $min_id,
        ];
        $resp = Curl::to ('http://v2.api.haodanku.com/selected_item')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);
        if ($resp->code != 1) {
            throw new \Exception($resp->msg);
        }
        return [
            'data' => $resp->data,
            'min_id' => $resp->min_id,
        ];
    }

    /**
     * 精选专题
     * @return mixed
     * @throws \Exception
     */
    public function zhuanti()
    {
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY')
        ];
        $resp = Curl::to ('http://v2.api.haodanku.com/get_subject')
            ->withData ($params)
            ->get ();
        $res = json_decode ($resp);
        if ($res->code != 1) {
            throw new \Exception($res->msg);
        }
        return $res;
    }

    /**
     * 快抢商品
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function kuaiQiang(array $params)
    {
        $type = $params['hour_type'] ?? 7;
        $min_id = $params['min_id'] ?? 1;
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'hour_type' => $type,
            'min_id' => $min_id,
        ];
        $rest = Curl::to ('http://v2.api.haodanku.com/fastbuy')
            ->withData ($params)
            ->get ();
        $rest = json_decode ($rest);
        if ($rest->code != 1) {
            throw  new \Exception($rest->msg);
        }
        return [
            'data' => $rest->data,
            'min_id' => $rest->min_id,
        ];
    }


    /**
     * 定时拉取
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function timingItems(array $params)
    {
        //获取最近整点时间
        $timestamp = date ('H', time ());//当前时间的整点
        $min_id = $params['min_id'] ?? 1;
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'start' => $timestamp,
            'end' => $timestamp + 1,
            'min_id' => $min_id,
            'back' => 100 //请在1,2,10,20,50,100,120,200,500,1000中选择一个数值返回
        ];
        $results = Curl::to ('http://v2.api.haodanku.com/timing_items')
            ->withData ($params)
            ->get ();
        $results = json_decode ($results);
        if ($results->code != 1) {
            throw  new \Exception($results->msg);
        }
        return [
            'data' => $results->data,
            'min_id' => $results->min_id,
        ];
    }

    /**
     * 更新优惠券
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function updateCoupon(array $params)
    {
        $sort = $params['sort'] ?? 1;
        $back = $params['back'] ?? 500;
        $min_id = $params['min_id'] ?? 1;
        if (!in_array ($back, [1, 2, 10, 20, 50, 100, 120, 200, 500, 1000])) {
            throw new \Exception('每页条数不合法');
        }
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'sort' => $sort,
            'back' => $back,
            'min_id' => $min_id,
        ];
        $rest = Curl::to ('http://v2.api.haodanku.com/update_item')
            ->withData ($params)
            ->get ();
        $rest = json_decode ($rest);
        if ($rest->code != 1) {
            throw new \Exception($rest->msg);

        }
        return [
            'data' => $rest->data,
            'min_id' => $rest->min_id,
        ];
    }

    /**
     * 失效商品
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function deleteCoupon(array $params)
    {
        $start = $params['start'];
        $end = $params['end'];
        $params = [
            'apikey' => config ('coupon.taobao.HDK_APIKEY'),
            'start' => $start,
            'end' => $end
        ];
        $resp = Curl::to ('http://v2.api.haodanku.com/get_down_items')
            ->withData ($params)
            ->get ();
        $resp = json_decode ($resp);

        if ($resp->code != 1) {
            throw new \Exception($resp->msg);

        }
        return $resp->data;
    }

    /**
     * 转换淘口令
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function taokouling(array $params)
    {
        // 根据pid item 图片地址生成淘口令，如果我不是会员，则用无上级的pid，如果上级也不是超级会员，就用组长的pid

        $topclient = TopClient::connection ();
        //获取淘口令
        $req = new TbkTpwdCreateRequest;

        $req->setUrl ($params['coupon_click_url']);
        $req->setLogo ($params['pict_url']);
        $req->setText ($params['title']);
        $resp = $topclient->execute ($req);
        if (!isset($resp->data->model)) {
            throw new \Exception('淘口令生成失败');
        }
        $taokouling = $resp->data->model;
        return $taokouling;
    }
}
