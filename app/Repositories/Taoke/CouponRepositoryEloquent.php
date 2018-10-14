<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Coupon;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\CouponValidator;
use Prettus\Repository\Eloquent\BaseRepository;
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
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CouponValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function searchGoods()
    {
        $sort = request('sort');
        $q = request('q');
        if (! $q) {
            return [
                'code' => 4001,
                'message' => '关键词必能为空',
            ];
        }
        //sort 1最新 2低价 3高价 4销量 5佣金 6综合
        $order = 'receive_num';
        $orderAsc = 'desc';
        switch ($sort) {
            case 1:
                $order = 'id';
                $orderAsc = 'desc';
                break;
            case 2:
                $order = 'final_price';
                $orderAsc = 'asc';
                break;
            case 3:
                $order = 'final_price';
                $orderAsc = 'desc';
                break;
            case 4:
                $order = 'volume';
                $orderAsc = 'desc';
                break;
            case 5:
                $order = 'coupon_price';
                $orderAsc = 'desc';
                break;
            case 6:
                break;
            default:
                break;
        }

        $coupon = db('tbk_coupons')->where([
            'type' => 2,
            'status' => 1,
        ])->orderBy($order, $orderAsc)->where('title', 'like', "%$q")->paginate(20);

        return [
            'code' => 1001,
            'message' => '获取成功',
            'data' => $coupon,
        ];
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }
}
