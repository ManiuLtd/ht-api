<?php

namespace App\Repositories\Shop;

use App\Models\Shop\Goods;
use App\Criteria\RequestCriteria;
use App\Validators\Shop\GoodsValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ShopGoodsRepository;

/**
 * Class GoodsRepositoryEloquent.
 */
class GoodsRepositoryEloquent extends BaseRepository implements ShopGoodsRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Goods::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return GoodsValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
