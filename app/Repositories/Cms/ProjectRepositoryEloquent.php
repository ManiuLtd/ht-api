<?php

namespace App\Repositories\Cms;

use App\Models\Cms\Project;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Cms\ProjectRepository;
use App\Validators\Cms\ProjectsValidator;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProjectRepositoryEloquent
 * @package App\Repositories\Cms
 */
class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'category_id',
        'title'=>'like',
        'keywords'=>'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Project::class;
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
