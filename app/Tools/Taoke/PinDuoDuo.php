<?php

namespace App\Tools\Taoke;

use Carbon\Carbon;
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

        $id = data_get($array, 'id');
        if (! is_numeric($id)) {
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
            'data' => data_get($result, 'goods_detail_response.goods_details.0', []),
        ];
    }

    /**
     * @param array $array
     * @return mixed
     */
    public function search(array $array)
    {
        //  Implement search() method.
        $page = data_get($array, 'page', 1);
        $q = $array['q'];
        $sort = $array['sort'];

        if ($page > 600) {
            return [
                'code' => 4004,
                'message' => '爬取完成',
            ];
        }
        $time = time();

        //sort 1最新 2低价 3高价 4销量 5佣金 6综合
        $sort_type = 0;
        switch ($sort) {
            case 1:
                $sort_type = 12;
                break;
            case 2:
                $sort_type = 3;
                break;
            case 3:
                $sort_type = 4;
                break;
            case 4:
                $sort_type = 6;
                break;
            case 5:
                $sort_type = 2;
                break;
            case 6:
                $sort_type = 0;
                break;
        }
        $params = [
            'client_id' => $this->client_id,
            'keyword' => $q,
            'page' => $page,
            'page_size' => 100,
            'sort_type' => $sort_type,
            'timestamp' => $time,
            'type' => 'pdd.ddk.goods.search',

        ];
        $str = 'client_id'.$this->client_id.'keyword'.$q.'page'.$page.'page_size100'.'sort_type'.$sort_type.'timestamp'.$time.'typepdd.ddk.goods.search';

        $sign = strtoupper(md5($this->client_secret.$str.$this->client_secret));

        $params['sign'] = $sign;
        $result = Curl::to('http://gw-api.pinduoduo.com/api/router')
            ->withData($params)
            ->post();
        $result = json_decode($result);

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
                $temp['cat'] = $item->category_id;
                $temp['pic_url'] = $item->goods_image_url;
                $temp['item_id'] = $item->goods_id;
                $temp['volume'] = $item->sold_quantity;
                $temp['price'] = $item->min_group_price / 100;
                $temp['final_price'] = $item->min_group_price / 100 - $item->coupon_discount / 100;
                $temp['coupon_price'] = $item->coupon_discount / 100;
                $temp['commission_rate'] = $item->promotion_rate / 10;
                $temp['introduce'] = $item->goods_desc;
                $temp['total_num'] = $item->coupon_total_quantity;
                $temp['receive_num'] = $item->coupon_total_quantity - $item->coupon_remain_quantity;
                $temp['type'] = 3;
                $temp['status'] = 0;
                $temp['start_time'] = Carbon::createFromTimestamp(intval($item->coupon_start_time / 1000))->toDateTimeString();
                $temp['end_time'] = Carbon::createFromTimestamp(intval($item->coupon_end_time / 1000))->toDateTimeString();
                $temp['created_at'] = Carbon::now()->toDateTimeString();
                $temp['updated_at'] = Carbon::now()->toDateTimeString();
                $data[] = $temp;
                $temp = [];
            }

            //当前页面地址
            $uri = request()->getUri();
            //验证是否填写page参数
            if (! str_contains('page=', $uri)) {
                $uri = $uri.'&page=1';
            }

            //页码信息
            $totalPage = intval(floor($result->goods_search_response->total_count / 20) + 1);
            $prevPage = $page - 1;
            $nextPage = $page + 1;
            //页码不对
            if ($page > $totalPage) {
                return response()->json([
                    'code' => 4001,
                    'message' => '超出最大页码',
                ]);
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
                    'total' => $result->goods_search_response->total_count,
                ],
                'code' => 1001,
                'message' => '优惠券获取成功',
            ];
        }
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
        //  Implement spider() method.
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

    /**
     * @return array|mixed
     */
    public function hotSearch()
    {
        return [];
    }
}
