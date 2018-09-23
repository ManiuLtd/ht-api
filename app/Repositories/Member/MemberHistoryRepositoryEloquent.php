<?php

namespace App\Repositories\Member;

use App\Criteria\RequestCriteria;
use App\Models\Member\MemberHistory;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Validators\Member\MemberHistoryValidator;
use App\Repositories\Interfaces\MemberHistoryRepository;

/**
 * Class MemberHistoryRepositoryEloquent.
 */
class MemberHistoryRepositoryEloquent extends BaseRepository implements MemberHistoryRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'member_id',
        'user_id',
        'created_at',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return MemberHistory::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return MemberHistoryValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
