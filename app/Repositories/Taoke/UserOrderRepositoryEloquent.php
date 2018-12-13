<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\UserOrder;
use App\Validators\Taoke\UserOrderValidator;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\UserOrderRepository;

/**
 * Class UserOrderRepositoryEloquent.
 */
class UserOrderRepositoryEloquent extends BaseRepository implements UserOrderRepository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return UserOrder::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return UserOrderValidator::class;
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
