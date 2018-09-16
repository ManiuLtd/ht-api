<?php

namespace App\Repositories\Member;

use App\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\MemberLevelRepository;
use App\Models\Member\MemberLevel;
use App\Validators\Member\MemberLevelValidator;

/**
 * Class MemberLevelRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MemberLevelRepositoryEloquent extends BaseRepository implements MemberLevelRepository
{

    protected $fieldSearchable = [
        'name' => 'like',
        'status',
        'sort',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MemberLevel::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return MemberLevelValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }


    /**
     * @return string
     */
    public function presenter()
    {
        return "Prettus\\Repository\\Presenter\\ModelFractalPresenter";
    }
}
