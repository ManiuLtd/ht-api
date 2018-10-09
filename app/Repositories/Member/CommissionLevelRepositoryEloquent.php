<?php

namespace App\Repositories\Member;

use App\Criteria\RequestCriteria;
use App\Models\Member\CommissionLevel;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Validators\Member\CommissionLevelValidator;
use App\Repositories\Interfaces\Member\CommissionLevelRepository;

/**
 * Class CommissionLevelRepositoryEloquent.
 */
class CommissionLevelRepositoryEloquent extends BaseRepository implements CommissionLevelRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
        'type'
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return CommissionLevel::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return CommissionLevelValidator::class;
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
