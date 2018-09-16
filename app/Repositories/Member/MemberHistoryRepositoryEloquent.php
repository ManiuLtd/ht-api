<?php

namespace App\Repositories\Member;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\MemberHistoryRepository;
use App\Models\Member\MemberHistory;
use App\Validators\Member\MemberHistoryValidator;

/**
 * Class MemberHistoryRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MemberHistoryRepositoryEloquent extends BaseRepository implements MemberHistoryRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
    ];


    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MemberHistory::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {

        return MemberHistoryValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria (app (RequestCriteria::class));
    }

}
