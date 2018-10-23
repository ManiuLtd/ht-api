<?php

namespace App\Tools\Taoke;

use Carbon\Carbon;
use Ixudra\Curl\Facades\Curl;

class JingDong implements TBKInterface
{
    /**
     * 获取优惠券地址
     * @param array $array
     * @return mixed
     */
    public function getCouponUrl(array $array = [])
    {
        // TODO: Implement getCouponUrl() method.
    }

    /**
     * @param array $array
     * @return mixed
     * @throws \Exception
     */
    public function getDetail(array $array = [])
    {
        // TODO 改为咱们自己的

        $id = request('itemid');
        if (! is_numeric($id)) {
            throw new \Exception('商品id类型错误');
        }
        $params = [
            'appid' => data_get(config('coupon'), 'jingdong.JD_APPID'),
            'appkey' => data_get(config('coupon'), 'jingdong.JD_APPKEY'),
            'gid' => $id,
        ];
        $response = Curl::to('http://japi.jingtuitui.com/api/get_goods_info')
            ->withData($params)
            ->post();
        $response = json_decode($response);
        if ($response->return != 0) {
            throw new \Exception('接口调用失败');
        }

        return $response->result;
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function search()
    {
        //  Implement search() method.
        $page = request('page', 1);
        $q = request('q');
        $sort = request('sort');
        $time = now()->toDateTimeString();
        $params = [
            'method' => 'jingdong.union.search.queryCouponGoods',
            'access_token' => data_get(config('coupon'), 'jingdong.access_token'),
            'app_key' => data_get(config('coupon'), 'jingdong.JDM_APP_KEY'),
            'timestamp' => $time,
            'v' => '2.0',
        ];
        $urlparams = [

            'goodsKeyword' => $q,
            'pageIndex' => $page,

        ];

        $signparams = array_merge($params, $urlparams);
        ksort($signparams);
        $sign = http_build_query($signparams);
        $sign = strtoupper(md5(data_get(config('coupon'), 'jingdong.JDM_APP_SECRET').$sign.data_get(config('coupon'), 'jingdong.JDM_APP_SECRET')));
        $params['sign'] = $sign;
        $params['360buy_param_json'] = json_encode($urlparams);
        $response = Curl::to('https://api.jd.com/routerjson')
            ->withData($params)
            ->get();
        dd($response);

        if ($response->return != 0) {
            return [
                'code' => 4001,
                'message' => $response->result,
            ];
        }
        $data = [];
        foreach ($response->result->data as $datum) {
            $temp['title'] = $datum->goods_name;
            $temp['cat'] = '';
            $temp['pic_url'] = $datum->goods_img;
            $temp['item_id'] = $datum->goods_id;
            $temp['item_url'] = $datum->goods_link;
            $temp['price'] = $datum->goods_price;
            $temp['final_price'] = $datum->coupon_price;
            $temp['coupon_price'] = $datum->discount_price;
            $temp['coupon_link'] = $datum->discount_link;
            $temp['commission_rate'] = $datum->commission;
            $temp['introduce'] = $datum->goods_content;
            $temp['type'] = 2;
            $temp['status'] = 0;
            $temp['start_time'] = Carbon::createFromTimestamp(intval($datum->discount_start / 1000))->toDateTimeString();
            $temp['end_time'] = Carbon::createFromTimestamp(intval($datum->discount_end / 1000))->toDateTimeString();
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
        $totalPage = $response->result->total_page;
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
                'total' => count($data),
            ],
            'code' => 1001,
            'message' => '优惠券获取成功',
        ];
    }

    /**
     * 获取订单.
     * @param array $array
     * @return mixed
     */
    public function getOrders(array $array = [])
    {
        // Implement getOrders() method.
        //  Implement search() method.
        $page = $array['page'] ?? 1;
        $time = now()->toDateTimeString();
        $params = [
            'method' => 'jingdong.UnionService.queryOrderListWithKey',
            'access_token' => data_get(config('coupon'), 'jingdong.access_token'),
            'app_key' => data_get(config('coupon'), 'jingdong.JDM_APP_KEY'),
            'timestamp' => $time,
            'v' => '2.0',
        ];
        $urlparams = [
            'unionId' => 29047,
            'key' => data_get(config('coupon'), 'jingdong.JDMEDIA_APPKEY'),
            'time' => date('YmdH', time()),
            'pageIndex' => $page,
            'pageSize' => 500,
        ];

        $signparams = array_merge($params, $urlparams);
        ksort($signparams);
        $sign = http_build_query($signparams);
        $sign = strtoupper(md5(data_get(config('coupon'), 'jingdong.JDM_APP_SECRET').$sign.data_get(config('coupon'), 'jingdong.JDM_APP_SECRET')));
        $params['sign'] = $sign;
        $params['360buy_param_json'] = json_encode($urlparams);
        $response = Curl::to('https://api.jd.com/routerjson')
            ->withData($params)
            ->get();
        $response = json_decode($response);
        //TODO 数据测试
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
    public function submitOrder(array $array = [])
    {
        // TODO: Implement submitOrder() method.
    }

    /**
     * 爬虫.
     * @param array $params
     * @return array|mixed
     * @throws \Exception
     */
    public function spider(array $params)
    {
        // TODO: Implement spider() method.

        $page = $params['page'] ?? 1;

        $params = [
            'appid' => data_get(config('coupon'), 'jingdong.JD_APPID'),
            'appkey' => data_get(config('coupon'), 'jingdong.JD_APPKEY'),
            'num' => 100,
            'page' => $page,
        ];
        $response = Curl::to(data_get(config('coupon'), 'jingdong.JD_LIST_APPURL'))
            ->withData($params)
            ->post();
        $response = json_decode($response);
        if ($response->return != 0) {
            throw new \Exception($response->result);
        }

        return [
            'totalPage' => $response->result->total_page,
            'data' => $response->result->data,
        ];
    }

    /**
     * @return array|mixed
     */
    public function hotSearch()
    {
        return [];
    }
}
