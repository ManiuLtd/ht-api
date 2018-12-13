<?php

namespace App\Repositories\User;

use App\Models\User\CreditLog;
use App\Criteria\RequestCriteria;
use App\Validators\User\CreditLogValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\User\CreditLogRepository;

/**
 * Class CreditLogRepositoryEloquent.
 */
class CreditLogRepositoryEloquent extends BaseRepository implements CreditLogRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'type',
        'created_at',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return CreditLog::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CreditLogValidator::class;
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
