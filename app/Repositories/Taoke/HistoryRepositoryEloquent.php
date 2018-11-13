<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\History;
use App\Criteria\RequestCriteria;
use App\Validators\Taoke\HistoryValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Interfaces\Taoke\HistoryRepository;

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
     * @return null|string
     */
    public function validator()
    {
        return HistoryValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
