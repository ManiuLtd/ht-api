<?php

namespace App\Repositories\Shop;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopOrderRepository;
use App\Models\Shop\ShopOrder;
use App\Validators\Shop\ShopOrderValidator;

/**
 * Class ShopOrderRepositoryEloquent.
 *
 * @package namespace App\Repositories;
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
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShopOrder::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ShopOrderValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }


    /**
     * @return string
     */
    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }
}
