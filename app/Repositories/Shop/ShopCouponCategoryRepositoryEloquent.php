<?php

namespace App\Repositories\Shop;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\ShopCouponCategoryRepository;
use App\Models\Shop\ShopCouponCategory;
use App\Validators\Shop\ShopCouponCategoryValidator;

/**
 * Class ShopCouponCategoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ShopCouponCategoryRepositoryEloquent extends BaseRepository implements ShopCouponCategoryRepository
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
        return ShopCouponCategory::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return ShopCouponCategoryValidator::class;
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
