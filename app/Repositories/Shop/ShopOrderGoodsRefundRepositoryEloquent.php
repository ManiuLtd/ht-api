<?php

namespace App\Repositories\Shop;

use App\Models\Shop\ShopOrderGoodsRefund;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Validators\Shop\ShopOrderGoodsRefundValidator;
use App\Repositories\Interfaces\ShopOrderGoodsRefundRepository;

/**
 * Class ShopOrderGoodsRefundRepositoryEloquent.
 */
class ShopOrderGoodsRefundRepositoryEloquent extends BaseRepository implements ShopOrderGoodsRefundRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'refundno' => 'like',
        'status',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return ShopOrderGoodsRefund::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ShopOrderGoodsRefundValidator::class;
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
