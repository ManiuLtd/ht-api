<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\MemberOrder;
use App\Validators\Taoke\MemberOrderValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\MemberOrderRepository;

/**
 * Class MemberOrderRepositoryEloquent.
 */
class MemberOrderRepositoryEloquent extends BaseRepository implements MemberOrderRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return MemberOrder::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return MemberOrderValidator::class;
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
