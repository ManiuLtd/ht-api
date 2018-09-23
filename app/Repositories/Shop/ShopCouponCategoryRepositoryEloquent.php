<?php

namespace App\Repositories\Shop;

use App\Models\Shop\ShopCouponCategory;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Validators\Shop\ShopCouponCategoryValidator;
use App\Repositories\Interfaces\ShopCouponCategoryRepository;

/**
 * Class ShopCouponCategoryRepositoryEloquent.
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
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return ShopCouponCategory::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ShopCouponCategoryValidator::class;
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
