<?php

namespace App\Repositories\Taoke;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\JingXuanRepository;
use App\Models\Taoke\JingXuan;
use App\Validators\Taoke\JingxuanDpValidator;

/**
 * Class JingXuanRepositoryEloquent.
 *
 * @package namespace App\Repositories\Taoke;
 */
class JingXuanRepositoryEloquent extends BaseRepository implements JingXuanRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return JingXuan::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return JingxuanDpValidator::class;
    }

    /**
     * @return string
     */
    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
