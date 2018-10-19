<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\HaoHuo;
use App\Repositories\Interfaces\Taoke\HaoHuoRepository;
use App\Validators\Taoke\HaoHuoValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class HaohuoRepositoryEloquent
 * @package App\Repositories\Taoke
 */
class HaoHuoRepositoryEloquent extends BaseRepository implements HaoHuoRepository
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
        return HaoHuo::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return HaoHuoValidator::class;
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
