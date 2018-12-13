<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Category;
use App\Criteria\RequestCriteria;
use App\Validators\Cms\CategoriesValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Cms\CategoriesRepository;

/**
 * Class CategoriesRepositoryEloquent.
 */
class CategoriesRepositoryEloquent extends BaseRepository implements CategoriesRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'status',
        'category_id',
        'name' => 'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CategoriesValidator::class;
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
