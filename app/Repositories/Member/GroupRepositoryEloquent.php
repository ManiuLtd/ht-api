<?php

namespace App\Repositories\Member;

use App\Models\Member\Group;
use App\Criteria\RequestCriteria;
use App\Validators\Member\GroupValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\GroupRepository;

/**
 * Class GroupRepositoryEloquent.
 */
class GroupRepositoryEloquent extends BaseRepository implements GroupRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
        'pid',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Group::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return GroupValidator::class;
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
