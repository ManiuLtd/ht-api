<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Coupon;
use App\Criteria\RequestCriteria;
use Orzcc\TopClient\Facades\TopClient;
use Prettus\Repository\Eloquent\BaseRepository;
use TopClient\request\WirelessShareTpwdQueryRequest;
use App\Repositories\Interfaces\Taoke\CouponRepository;

/**
 * Class CouponRepositoryEloquent.
 */
class CouponRepositoryEloquent extends BaseRepository implements CouponRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'title' => 'like',
        'introduce' => 'like',
        'item_id',
        'cat',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Coupon::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @return array|bool|mixed|string
     * @throws \Exception
     */
    public function searchGoods()
    {
        $sort = request('sort');
        $q = request('q');
        $type = request('type');

        if (! $q) {
            return [
                'code' => 4001,
                'message' => '关键词必能为空',
            ];
        }
        $rest = $this->searchByTKL($q);

        if ($rest) {
            $coupon = $this->model->where([
                'item_id' => $rest,
            ])->get()->toArray();

            if ($coupon) {
                return $coupon;
            }

            return $rest;
        }

        return $q;

//        //sort 1最新 2低价 3高价 4销量 5佣金 6综合
//        $order = 'receive_num';
//        $orderAsc = 'desc';
//        switch ($sort) {
//            case 1:
//                $order = 'id';
//                $orderAsc = 'desc';
//                break;
//            case 2:
//                $order = 'final_price';
//                $orderAsc = 'asc';
//                break;
//            case 3:
//                $order = 'final_price';
//                $orderAsc = 'desc';
//                break;
//            case 4:
//                $order = 'volume';
//                $orderAsc = 'desc';
//                break;
//            case 5:
//                $order = 'coupon_price';
//                $orderAsc = 'desc';
//                break;
//            case 6:
//                break;
//            default:
//                break;
//        }
//
//        $coupon = db('tbk_coupons')->where([
//            'type' => 2,
//            'status' => 1,
//        ])->orderBy($order, $orderAsc)->where('title', 'like', "%$q")->paginate(20);
//
//        return [
//            'code' => 1001,
//            'message' => '获取成功',
//            'data' => $coupon,
//        ];
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * 淘口令解密.
     * @param $keywords
     * @return array|bool|mixed|string
     * @throws \Exception
     */
    protected function searchByTKL($keywords)
    {
        //验证淘口令
        if (substr_count($keywords, '￥') == 2 || substr_count($keywords, '《') == 2 || substr_count($keywords, '€') == 2) {
            $req = new WirelessShareTpwdQueryRequest();

            $req->setPasswordContent($keywords);
            $topclient = TopClient::connection();
            $response = $topclient->execute($req);
            //淘口令解密失败
            if (! $response->suc) {
                return false;
            }
            if (str_contains($response->url, 'a.m.taobao.com/i')) {
                $pos = strpos($response->url, '?');
                $str = substr($response->url, 0, $pos);
                $str = str_replace('https://a.m.taobao.com/i', '', $str);
                $str = str_replace('.htm', '', $str);

                return $str;
            }
            $pos = strpos($response->url, '?');
            $query_string = substr($response->url, $pos + 1, strlen($response->url));
            $arr = \League\Uri\extract_query($query_string);

            if (isset($arr['activity_id'])) {
                return false;
            }

            if (! isset($arr['id'])) {
                return false;
            }

            return $arr['id'];
        }

        return false;
    }
}
