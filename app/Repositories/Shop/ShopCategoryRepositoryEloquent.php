<?php

namespace App\Repositories\Shop;

use App\Criteria\RequestCriteria;
use App\Models\Shop\ShopCategory;
use App\Validators\Shop\ShopCategoryValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ShopCategoryRepository;

/**
 * Class ShopCategoryRepositoryEloquent.
 */
class ShopCategoryRepositoryEloquent extends BaseRepository implements ShopCategoryRepository
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
        return ShopCategory::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ShopCategoryValidator::class;
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
