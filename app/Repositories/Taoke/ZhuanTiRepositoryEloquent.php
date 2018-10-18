<?php

namespace App\Repositories\Taoke;

use App\Models\Taoke\ZhuanTi;
use App\Validators\Taoke\ZhuanTiValidator;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Interfaces\Taoke\ZhuanTiRepository;

/**
 * Class ZhuanTiRepositoryEloquent.
 */
class ZhuanTiRepositoryEloquent extends BaseRepository implements ZhuanTiRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return ZhuanTi::class;
    }

    /**
     * Specify Validator class name.
     *
     * @return mixed
     */
    public function validator()
    {
        return ZhuanTiValidator::class;
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
