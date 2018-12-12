<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Says;
use App\Repositories\Interfaces\Taoke\SaysRepository;
use App\Validators\Taoke\SaysValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class SaysRepositoryEloquent
 * @package App\Repositories\Taoke
 */
class SaysRepositoryEloquent extends BaseRepository implements SaysRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'label',
        'topdata',
        'newdata',
        'clickdata',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Says::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return SaysValidator::class;
    }

    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
