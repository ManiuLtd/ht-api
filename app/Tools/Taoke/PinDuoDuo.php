<?php

namespace App\Tools\Taoke;

use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;
use App\Models\Taoke\Favourite;

class PinDuoDuo implements TBKInterface
{
    use TBKCommon;

    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getCouponUrl(array $array = [])
    {
        $id = $array['id'];
        // 返回拼多多领券地址
        $url = 'http://mobile.yangkeduo.com/goods2.html?goods_id=' . $id;
        $pids = $this->getPids ();

        if (!isset($pids->pinduoduo)) {
            throw new \Exception('请先设置系统拼多多推广位id');
        }
        $time = time ();
        $params = [
            'client_id' => data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID'),
            'pid' => $pids->pinduoduo,
            'source_url' => "$url",
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.zs.unit.url.gen',
        ];

        $str = 'client_id' . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID') . 'pid' . $pids->pinduoduo . 'source_url' . $url . 'timestamp' . $time . 'typepdd.ddk.goods.zs.unit.url.gen';

        $sign = strtoupper (md5 (data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET') . $str . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET')));

        $params['sign'] = $sign;
        $resp = Curl::to ('http://gw-api.pinduoduo.com/api/router')
            ->withData ($params)
            ->post ();
        $resp = json_decode ($resp);

        if (isset($resp->error_response)) {
            throw new \Exception($resp->error_response->error_msg);
        }

        if (isset($resp->goods_zs_unit_generate_response)) {
            return $resp->goods_zs_unit_generate_response;
        }
        throw new \Exception('未知错误');
    }

    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function getDetail(array $params = [])
    {
        $id = $params['itemid'] ?? request('itemid');
        if (!is_numeric ($id)) {
            throw new \InvalidArgumentException('商品id类型错误');
        }

        $params = [
            'type' => 'goodsdetail',
            'apikey' => data_get (config ('coupon'), 'jingdong.JD_HJK_APIKEY'),
            'skuid' => $id,

        ];

        $result = Curl::to ('http://api-gw.haojingke.com/index.php/api/pdd/myapi')
            ->withData ($params)
            ->post ();
        $result = json_decode ($result);
        if (!$result) {
            db('tbk_coupons')->where([
                'type' => 3,
                'item_id' => $id,
            ])->delete();
            throw new \Exception('优惠券不存在');
        }

        if ($result->status_code != 200) {
            throw new \Exception($result->message);
        }

        $data = $result->data;

        $data->introduce = $data->skuDesc;
        $link = $this->getCouponUrl (['id' => $id]);

        //判断优惠卷是否被收藏
        $favourite = 0;
        if (getUserId ()) {
            $user = getUser ();
            $favouritesModel = Favourite::query ()->where ([
                'user_id' => $user->id,
                'item_id' => $id,
                'type' => 3,
            ])->first ();
            if ($favouritesModel) {
                $favourite = $favouritesModel->id; //已收藏
            }
        }

        $coupon = db ('tbk_coupons')->where ([
            'item_id' => $id,
            'type' => 3,
        ])->first ();
        //优惠券第一张图片
        $pic_url = $data->picUrls;
        $small_images = [$pic_url];
        if ($coupon) {
            $pic_url = $coupon->pic_url;
            $small_images = [$pic_url];
        }

        //在商品图前加上商品主图
        if (isset($data->picUrls)){
            $small_images = $data->picUrls;
            array_unshift($small_images,$pic_url);
        }

        //重组字段
        $arr = [];
        $arr['title'] = $data->skuName; //标题
        $arr['item_id'] = $data->skuId; //商品id
        $arr['user_type'] = null; //京东  拼多多 null  1淘宝 2天猫
        $arr['volume'] = $data->sales; //销量
        $arr['price'] = floatval ($data->min_group_price) + floatval ($data->discount); //原价
        $arr['final_price'] = floatval ($data->min_group_price); //最终价
        $arr['coupon_price'] = floatval ($data->discount); //优惠价
        $arr['commossion_rate'] = $data->wlCommissionShare; //佣金比例
        $arr['coupon_start_time'] = $data->beginTime ? Carbon::createFromTimestamp (intval ($data->beginTime))->toDateString () : Carbon::now ()->toDateString (); //优惠卷开始时间
        $arr['coupon_end_time'] = $data->endTime ? Carbon::createFromTimestamp (intval ($data->endTime))->toDateString () : Carbon::now ()->addDay (3)->toDateString (); //优惠卷结束时间
        $arr['coupon_remain_count'] = $data->coupon_total_quantity - $data->coupon_remain_quantity; //已使用优惠卷数量
        $arr['coupon_total_count'] = $data->coupon_total_quantity ?? 1000; //优惠卷总数
        $arr['pic_url'] = $data->picUrl; //商品主图
        $arr['small_images'] = $small_images; //商品图
        $arr['images'] = $data->picUrls;//商品详情图
        $arr['kouling'] = null; //淘口令
        $arr['introduce'] = $data->skuDesc; //描述
        $arr['favourite'] = $favourite;
        $arr['coupon_link'] = $link; //领劵地址
        $arr['finalCommission'] = floatval(round($this->getFinalCommission($data->wlCommission),2));
        $arr['favourite'] = $favourite;

        return $arr;
    }

    /**
     * 全网搜索.
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function search(array $array = [])
    {
        $page = request ('page', 1);
        $q = request('q');
        $sort = request ('sort');

        $searchType = request ('searchtype',2);
        if($searchType == 1){
            return $this->localSearch ($q);
        }

        $time = time ();

        //1.综合，2.销量（高到低），3.销量（低到高），4.价格(低到高)，5.价格（高到低），6.佣金比例（高到低） 7. 卷额(从高到低) 8.卷额(从低到高)
        $sort_type = 0;
        switch ($sort) {
            case 1:
                $sort_type = 0;
                break;
            case 2:
                $sort_type = 6;
                break;
            case 3:
                $sort_type = 5;
                break;
            case 4:
                $sort_type = 3;
                break;
            case 5:
                $sort_type = 4;
                break;
            case 6:
                $sort_type = 2;
                break;
            case 7:
                $sort_type = 8;
                break;
            case 8:
                $sort_type = 7;
                break;
            default:
                $sort_type = 0;
                break;
        }
        $params = [
            'client_id' => data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID'),
            'keyword' => $q,
            'page' => $page,
            'page_size' => 20,
            'sort_type' => $sort_type,
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.search',

        ];
        $str = 'client_id' . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID') . 'keyword' . $q . 'page' . $page . 'page_size20' . 'sort_type' . $sort_type . 'timestamp' . $time . 'typepdd.ddk.goods.search';

        $sign = strtoupper (md5 (data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET') . $str . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET')));

        $params['sign'] = $sign;
        $result = Curl::to ('http://gw-api.pinduoduo.com/api/router')
            ->withData ($params)
            ->post ();
        $result = json_decode ($result);

        if (isset($result->error_response)) {
            return [
                'code' => 4001,
                'message' => $result->error_response->error_msg,
            ];
        }

        if (isset($result->goods_search_response)) {
            $data = [];
            foreach ($result->goods_search_response->goods_list as $item) {
                $temp['title'] = $item->goods_name;
                $temp['pic_url'] = $item->goods_thumbnail_url;
                $temp['item_id'] = $item->goods_id;
                $temp['price'] = $item->min_group_price / 100;
                $temp['final_price'] = round ($item->min_group_price / 100 - $item->coupon_discount / 100, 2);
                $temp['coupon_price'] = $item->coupon_discount / 100;
                $temp['commission_rate'] = $item->promotion_rate / 10;
                $temp['type'] = 3;
                $temp['volume'] = $item->sold_quantity;
                $data[] = $temp;
                $temp = [];
            }

            //当前页面地址
            $uri = request ()->getUri ();
            //验证是否填写page参数
            if (!str_contains ('page=', $uri)) {
                $uri = $uri . '&page=1';
            }

            //页码信息
            $totalPage = intval (floor ($result->goods_search_response->total_count / 20) + 1);
            $prevPage = $page - 1;
            $nextPage = $page + 1;
            //页码不对
            if ($page > $totalPage) {
                throw new \Exception('超出最大页码');
            }

            return [
                'data' => $data,
                'links' => [
                    'first' => str_replace ("page={$page}", 'page=1', $uri),
                    'last' => str_replace ("page={$page}", "page={$totalPage}", $uri),
                    'prev' => $page == 1 ? null : str_replace ("page={$page}", "page={$prevPage}", $uri),
                    'next' => str_replace ("page={$page}", "page={$nextPage}", $uri),
                ],
                'meta' => [
                    'current_page' => (int)$page,
                    'from' => 1,
                    'last_page' => $totalPage,
                    'path' => request ()->url (),
                    'per_page' => 20,
                    'to' => 20 * $page,
                    'total' => $result->goods_search_response->total_count,
                ],
            ];
        }
    }

    /**
     * 获取订单.
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function getOrders(array $array = [])
    {
        //  Implement getOrders() method.
        $time = time ();
        $start_update_time = now ()->subDays (30)->timestamp;
        $page = data_get ($array, 'page', 1);
        $params = [
            'client_id' => data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID'),
            'start_update_time' => $start_update_time,
            'end_update_time' => $time,
            'timestamp' => $time,
            'page' => $page,
            'type' => 'pdd.ddk.order.list.increment.get',
        ];

        $str = 'client_id' . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID') . 'end_update_time' . $time . 'page' . $page . 'start_update_time' . $start_update_time . 'timestamp' . $time . 'typepdd.ddk.order.list.increment.get';

        $sign = strtoupper (md5 (data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET') . $str . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET')));

        $params['sign'] = $sign;

        $result = Curl::to ('http://gw-api.pinduoduo.com/api/router')
            ->withData ($params)
            ->post ();

        $data = json_decode ($result);

        if (isset($data->error_response)) {
            throw new \Exception($data->error_response->error_msg);
        }

        if (isset($data->order_list_get_response)) {
            return $data->order_list_get_response;
        }
    }

    /**
     * 爬虫.  好京客.
     * @param array $array
     * @return array|mixed
     * @throws \Exception
     */
    public function spider(array $array)
    {
        //  Implement spider() method.
        $page = $array['page'] ?? 1;
        if ($page > 600) {
            throw new \Exception('爬取完成');
        }
        $params = [
            'type' => 'goodslist',
            'apikey' => data_get (config ('coupon'), 'jingdong.JD_HJK_APIKEY'),
            'iscoupon' => 1,
            'page' => $page,
            'pageSize' => 100,
        ];

        $result = Curl::to ('http://api-gw.haojingke.com/index.php/api/pdd/myapi')
            ->withData ($params)
            ->post ();
        $result = json_decode ($result);
        if (!$result) {
            throw new \Exception('接口返回数据为空');
        }
        if ($result->status_code != 200) {
            throw new \Exception($result->message);
        }

        return [
            'code' => 1001,
            'data' => [
                'total_count' => $result->total > 60000 ? 60000 : $result->total,
                'goods_list' => $result->data,
            ],
        ];
    }

    /**
     * @return array|mixed
     */
    public function hotSearch()
    {
        return [];
    }

    /**
     * @return array|mixed
     */
    public function super_category()
    {
        return [];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function createPid()
    {
        $time = time ();
        $params = [
            'client_id' => data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID'),
            'number' => '1',
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.pid.generate',
        ];

        $str = 'client_id' . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_ID').'number1' . 'timestamp' . $time . 'typepdd.ddk.goods.pid.generate';

        $sign = strtoupper (md5 (data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET') . $str . data_get (config ('coupon'), 'pinduoduo.PDD_CLIENT_SECRET')));

        $params['sign'] = $sign;

        $result = Curl::to ('http://gw-api.pinduoduo.com/api/router')
            ->withData ($params)
            ->post ();

        $data = json_decode ($result);

        if (isset($data->error_response)) {
            throw new \Exception($data->error_response->error_msg);
        }
        if (isset($data->p_id_generate_response)) {
            return $data->p_id_generate_response->p_id_list;
        }
    }
}
