<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Haohuo;
use App\Repositories\Interfaces\Taoke\HaohuoRepository;
use App\Validators\Taoke\HaohuoValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class HaohuoRepositoryEloquent
 * @package App\Repositories\Taoke
 */
class HaohuoRepositoryEloquent extends BaseRepository implements HaohuoRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Haohuo::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return HaohuoValidator::class;
    }

    /**
     * @return string
     */
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
