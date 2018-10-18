<?php

namespace App\Repositories\Taoke;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\JingxuanDpRepository;
use App\Models\Taoke\JingxuanDp;
use App\Validators\Taoke\JingxuanDpValidator;

/**
 * Class JingxuanDpRepositoryEloquent.
 *
 * @package namespace App\Repositories\Taoke;
 */
class JingxuanDpRepositoryEloquent extends BaseRepository implements JingxuanDpRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return JingxuanDp::class;
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
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
