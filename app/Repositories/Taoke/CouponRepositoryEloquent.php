<?php

namespace App\Repositories\Taoke;

use App\Criteria\Taoke\CouponCriteria;
use App\Models\Taoke\Coupon;
use App\Criteria\RequestCriteria;
use Orzcc\TopClient\Facades\TopClient;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use TopClient\request\WirelessShareTpwdQueryRequest;
use App\Repositories\Interfaces\Taoke\CouponRepository;

/**
 * Class CouponRepositoryEloquent.
 */
class CouponRepositoryEloquent extends BaseRepository implements CouponRepository
{
    use CacheableRepository;
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'title' => 'like',
        'introduce' => 'like',
        'item_id',
        'cat',
        'shop_type',
        'activity_type',
        'status',
        'is_recommend',
        'tag'
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
    protected function searchByTKL()
    {
        $keywords = request('q');
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
