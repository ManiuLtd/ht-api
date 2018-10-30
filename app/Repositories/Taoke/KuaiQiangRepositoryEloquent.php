<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\Kuaiqiang;
use App\Validators\Taoke\KuaiQiangValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\KuaiQiangRepository;

/**
 * Class KuaiQiangRepositoryEloquent.
 */
class KuaiQiangRepositoryEloquent extends BaseRepository implements KuaiQiangRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'title' => 'like'
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Kuaiqiang::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return KuaiQiangValidator::class;
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
