<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Categories;
use App\Models\Cms\Projects;
use App\Models\Image\Banner;
use App\Criteria\RequestCriteria;
use App\Validators\Cms\CategoriesValidator;
use App\Validators\Cms\ProjectsValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Image\BannerRepository;

/**
 * Class ProjectsRepositoryEloquent.
 */
class ProjectsRepositoryEloquent extends BaseRepository implements BannerRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'category_id',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Projects::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ProjectsValidator::class;
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
