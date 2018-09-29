<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Categories;
use App\Models\Image\Banner;
use App\Criteria\RequestCriteria;
use App\Validators\Cms\CategoriesValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Image\BannerRepository;

/**
 * Class CategoriesRepositoryEloquent.
 */
class CategoriesRepositoryEloquent extends BaseRepository implements BannerRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Categories::class;
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
