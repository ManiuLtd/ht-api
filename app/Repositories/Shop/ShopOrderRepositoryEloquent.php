<?php

namespace App\Repositories\Shop;

use App\Models\Shop\ShopOrder;
use App\Criteria\RequestCriteria;
use App\Validators\Shop\ShopOrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ShopOrderRepository;

/**
 * Class ShopOrderRepositoryEloquent.
 */
class ShopOrderRepositoryEloquent extends BaseRepository implements ShopOrderRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'orderno' => 'like',
        'status',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return ShopOrder::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ShopOrderValidator::class;
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
