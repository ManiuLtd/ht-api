<?php

namespace App\Repositories\Image;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Criteria\RequestCriteria;
use App\Repositories\Interfaces\BannerRepository;
use App\Models\Image\Banner;
use App\Validators\Image\BannerValidator;

/**
 * Class BannerRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class BannerRepositoryEloquent extends BaseRepository implements BannerRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tag' => 'like',
        'status',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Banner::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    public function validator()
    {
        return BannerValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
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
