<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Entrance;
use App\Validators\Taoke\EntranceValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\EntranceRepository;

/**
 * Class EntranceRepositoryEloquent.
 */
class EntranceRepositoryEloquent extends BaseRepository implements EntranceRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title' => 'like',
        'category_id',
        'is_home',
        'type',
        'status',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Entrance::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return EntranceValidator::class;
    }

    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
