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

    /**
     * @return array|bool|mixed|string
     * @throws \Exception
     */
    public function searchGoods()
    {
        $sort = request('sort');
        if ($sort == 7) {
            $sort = 'coupon_price';
            $sort_a = 'desc'; //倒序
        } elseif ($sort == 8) {
            $sort = 'coupon_price';
            $sort_a = 'asc'; //升序
        } elseif ($sort == 4) {
            $sort = 'price';
            $sort_a = 'asc';
        } elseif ($sort == 5) {
            $sort = 'price';
            $sort_a = 'desc';
        } elseif ($sort == 2) {
            $sort = 'volume';
            $sort_a = 'asc';
        } elseif ($sort == 6) {
            $sort = 'commission_rate';
            $sort_a = 'asc';
        } else {
            $sort = 'id';
            $sort_a = 'asc';
        }

        $q = request('q');
        $type = request('type');

        if (! $q) {
            throw new \Exception('请输入关键词');
        }
        $rest = $this->searchByTKL($q);

        if ($rest) {
            $coupon = db('coupons')->where([
                'item_id' => $rest,
            ])
                ->orderBy($sort, $sort_a)
                ->get()
                ->toArray();

            if ($coupon) {
                return $coupon;
            }

            return $rest;
        }

        return $q;
    }
}
