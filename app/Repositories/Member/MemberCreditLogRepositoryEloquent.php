<?php

namespace App\Repositories\Member;

use App\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\MemberCreditLogRepository;
use App\Models\Member\MemberCreditLog;
use App\Validators\Member\MemberCreditLogValidator;

/**
 * Class MemberCreditLogRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MemberCreditLogRepositoryEloquent extends BaseRepository implements MemberCreditLogRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
        'type',
        'created_at'
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MemberCreditLog::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return MemberCreditLogValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
