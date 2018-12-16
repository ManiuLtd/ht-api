<?php

namespace App\Repositories\Taoke;

use App\Criteria\RequestCriteria;
use App\Models\Taoke\DiyAds;
use App\Repositories\Interfaces\Taoke\DiyAdsRepository;
use App\Validators\Taoke\DiyAdsValidator;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class DianRepositoryEloquent.
 */
class DiyAdsRepositoryEloquent extends BaseRepository implements DiyAdsRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title'=>'like',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return DiyAds::class;
    }

    /**
     * @return null|string
     */
    public function validator()
    {
        return DiyAdsValidator::class;
    }

    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * @return string
     */
    public function presenter()
    {
        return 'Prettus\\Repository\\Presenter\\ModelFractalPresenter';
    }

}
