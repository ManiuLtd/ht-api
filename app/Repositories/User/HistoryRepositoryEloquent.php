<?php

namespace App\Repositories\User;

use App\Models\User\History;
use App\Criteria\RequestCriteria;
use App\Validators\User\HistoryValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\User\HistoryRepository;

/**
 * Class HistoryRepositoryEloquent.
 */
class HistoryRepositoryEloquent extends BaseRepository implements HistoryRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
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
