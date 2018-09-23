<?php

namespace App\Repositories\Shop;

use App\Models\Shop\RefundOrder;
use App\Validators\Shop\RefundOrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopRefundOrderRepository;

/**
 * Class RefundOrderOrderRepositoryEloquent.
 */
class RefundOrderOrderRepositoryEloquent extends BaseRepository implements ShopRefundOrderRepository
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
        return RefundOrder::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return RefundOrderValidator::class;
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
