<?php

namespace App\Repositories\Member;

use App\Criteria\RequestCriteria;
use App\Models\Member\History;
use App\Validators\Member\HistoryValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Member\HistoryRepository;

/**
 * Class HistoryRepositoryEloquent.
 */
class HistoryRepositoryEloquent extends BaseRepository implements HistoryRepository
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
        return History::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return HistoryValidator::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
