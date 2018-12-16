<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\DiyZhuanti;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\DiyZhuantiValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\DiyZhuantiRepository;

/**
 * Class DianRepositoryEloquent.
 */
class DiyZhuantiRepositoryEloquent extends BaseRepository implements DiyZhuantiRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'=>'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return DiyZhuanti::class;
    }

    /**
     * @return null|string
     */
    public function validator()
    {
        return DiyZhuantiValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
