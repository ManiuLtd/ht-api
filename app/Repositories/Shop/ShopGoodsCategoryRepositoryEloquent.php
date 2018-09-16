<?php

namespace App\Repositories\Shop;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopGoodsCategoryRepository;
use App\Models\Shop\ShopGoodsCategory;
use App\Validators\Shop\ShopGoodsCategoryValidator;

/**
 * Class ShopGoodsCategoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShopGoodsCategoryRepositoryEloquent extends BaseRepository implements ShopGoodsCategoryRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'status',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ShopGoodsCategory::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ShopGoodsCategoryValidator::class;
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
