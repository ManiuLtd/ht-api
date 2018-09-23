<?php

namespace App\Repositories\Shop;

use App\Models\Shop\ShopGoods;
use App\Criteria\RequestCriteria;
use App\Validators\Shop\ShopGoodsValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ShopGoodsRepository;

/**
 * Class ShopGoodsRepositoryEloquent.
 */
class ShopGoodsRepositoryEloquent extends BaseRepository implements ShopGoodsRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return ShopGoods::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ShopGoodsValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
