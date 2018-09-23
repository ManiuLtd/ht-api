<?php

namespace App\Repositories\Shop;

use App\Models\Shop\CouponCategory;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Validators\Shop\CouponCategoryValidator;
use App\Repositories\Interfaces\ShopCouponCategoryRepository;

/**
 * Class CouponCategoryRepositoryEloquent.
 */
class CouponCategoryRepositoryEloquent extends BaseRepository implements ShopCouponCategoryRepository
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
        return CouponCategory::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CouponCategoryValidator::class;
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
