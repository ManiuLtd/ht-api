<?php

namespace App\Repositories\Shop;

use App\Models\Shop\CouponLog;
use App\Validators\Shop\CouponLogValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopCouponLogRepository;

/**
 * Class CouponLogRepositoryEloquent.
 */
class CouponLogRepositoryEloquent extends BaseRepository implements ShopCouponLogRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ordersn' => 'like',
        'name' => 'like',
        'get_type',
        'pay_type',
        'status',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return CouponLog::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CouponLogValidator::class;
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
}
