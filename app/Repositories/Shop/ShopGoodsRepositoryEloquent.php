<?php

namespace App\Repositories\Shop;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopGoodsRepository;
use App\Models\Shop\ShopGoods;
use App\Validators\Shop\ShopGoodsValidator;

/**
 * Class ShopGoodsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShopGoodsRepositoryEloquent extends BaseRepository implements ShopGoodsRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShopGoods::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ShopGoodsValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }

}
