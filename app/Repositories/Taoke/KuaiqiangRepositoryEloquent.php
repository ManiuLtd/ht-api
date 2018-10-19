<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Kuaiqiang;
use App\Validators\Taoke\KuaiqiangValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\KuaiqiangRepository;

/**
 * Class KuaiqiangRepositoryEloquent.
 */
class KuaiqiangRepositoryEloquent extends BaseRepository implements KuaiqiangRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Kuaiqiang::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return KuaiqiangValidator::class;
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
